<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateApiRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Services\AuthService;
use App\Http\Services\ModelService;
use App\Mail\ForgotMail;
use App\Mail\VerifyUser;
use App\Models\ApiData;
use App\Models\TraceSystem;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mail;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(): Factory|View|Application
    {
        return view('auth.login');
    }

    public function loginPost(LoginRequest $request): RedirectResponse
    {
        if ($this->authService->loginPost($request)) {
            session()->flash('notif', 'Đăng nhập thành công!');
            TraceSystem::setTrace([
                'mgs' => 'Đăng nhập!',
                'ip' => $request->ip()
            ]);

            if (is_admin()) {
                return redirect()->route('admin.home');
            }

            $pathRedirect = session()->pull('url-redirect', '/');
            return redirect()->to($pathRedirect);
        }
        session()->flash('notif', 'Thông tin tài khoản hoặc mật khẩu không chính xác!');
        return redirect()->back()->withInput();
    }

    public function register(): Factory|View|Application
    {
        return view('auth.register');
    }

    public function registerPost(RegisterRequest $request): RedirectResponse
    {
        $this->authService->registerPost($request);
        if (!$this->sendMailVerify($request->email)) {
            session()->flash('mgs_error', 'Chúng tôi không thể gửi email xác nhận ngay bây giờ. Vui lòng thử lại sau 30 phút. Nếu không giải quyết được vấn đề, hãy liên hệ với Admin để được xử lý!');
        } else {
            Auth::attempt([
                'username' => $request->username,
                'password' => $request->password
            ]);
            session()->flash('notif', 'Chúng tôi đã gửi 1 mã xác minh tới email của bạn!');
        }
        TraceSystem::setTrace([
            'mgs' => "Tạo tài khoản",
            'ip' => $request->ip(),
            'user_name' => $request->username
        ]);
        return redirect()->route('auth.view');
    }

    public function verify(): Factory|View|Application
    {
        return view('auth.verify');
    }

    public function verifyPost(Request $request): RedirectResponse
    {
        $verifyCode = $request->verify_code;
        if (empty($verifyCode)) {
            session()->flash('mgs_error', 'Không nhập mã thì lấy gì xác minh????');
            return back();
        }
        $user = User::whereId(user()->id)->first();
        if (!Hash::check($verifyCode, $user->hash_verify)) {
            session()->flash('mgs_error', 'Mã xác nhận không chính xác!');
            return back();
        }

        $user->verified = 1;
        $user->hash_verify = null;
        $user->save();

        session()->flash('notif', 'Xác minh thành công!');
        Auth::logout();

        return redirect()->route('auth.view');
    }

    public function forgot($step = 'start', $hash = ''): Factory|View|Application
    {
        return view('auth.forgot-password', compact('step', 'hash'));
    }

    public function forgotVerify($hash): View|Factory|Application|RedirectResponse
    {
        $hash = urldecode($hash);
        $user = User::whereHashForgot($hash)->first();
        if ($user == null) {
            session()->flash('mgs_error', 'Mã hash xác nhận quên mật khẩu không tồn tại hoặc đã hết hạn!');
            return redirect()->to('/');
        }
        return $this->forgot('new', $hash);
    }

    public function forgotPost(Request $request): Application|Factory|RedirectResponse|View
    {
        $step = $request->step;

        if ($step == 'start') {
            return $this->sendMailForgot($request);
        }

        if ($step == 'verify') {
            return $this->verifyForgot($request);
        }

        if ($step == 'new') {
            return $this->renewPassword($request);
        }

        session()->flash('mgs_error', 'Một lỗi đã xảy ra!');
        return $this->forgot();
    }

    public function resendMailVerify(): JsonResponse
    {
        $status = $this->sendMailVerify(user()->email);
        $mgs = match ($status) {
            true => 'Gửi lại mã thành công!',
            false => 'Gửi lại mã thất bại!',
        };
        return response()->json([
            'success' => $status,
            'message' => $mgs
        ]);
    }

    private function sendMailVerify($email): bool
    {
        $user = User::whereEmail($email)->first();

        $code = rand(100000, 999999);
        $hash = Hash::make($code);

        $details['code'] = $code;
        $details['link'] = url('/auth/verify-link', ['hash' => urlencode($hash)]);

        try {
            Mail::to($user->email)->send(new VerifyUser($details));
            $user->hash_verify = $hash;
            $user->save();
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    public function userVerifyHash($hash): RedirectResponse
    {
        $user = User::whereHashVerify(urldecode($hash))->first();
        if (empty($user)) {
            session()->flash('mgs_error', 'Mã xác minh không chính xác hoặc không tồn tại!');
            return redirect()->to('/');
        }

        $user->verified = 1;
        $user->hash_verify = null;
        $user->save();

        session()->flash('notif', 'Xác minh thành công!');
        Auth::logout();
        return redirect()->route('auth.view');
    }

    private function sendMailForgot($request): View|Factory|Application|RedirectResponse
    {
        $username = $request->dasfsada;
        if (empty($username)) {
            session()->flash('mgs_error', 'Email hoặc tên người dùng chưa được nhập!');
            return back();
        }

        $user = User::whereUsername($username)->orWhere('email', $username)->first();
        if ($user == null) {
            session()->flash('mgs_error', 'Không tìm thấy thông tin người dùng!');
            return back();
        }

        $code = rand(100000, 999999);
        $hash = Hash::make($code);

        $details['code'] = $code;
        $details['link'] = url('forgot-password', ['hash' => urlencode($hash)]);

        try {
            Mail::to($user->email)->send(new ForgotMail($details));
            $user->hash_forgot = $hash;
            $user->save();

            TraceSystem::setTrace("User có username là \"{$username}\" đang thực hiện quên mật khẩu!");
        } catch (Exception $exception) {
            session()->flash('mgs_error', 'Chúng tôi không thể gửi mail xác nhận ngay bây giờ!');
            return back();
        }

        return $this->forgot('verify', $username);
    }

    private function verifyForgot($request): Factory|View|Application
    {
        $username = $request->dasfsada;
        $verifyCode = $request->verify_code;

        if (empty($username)) {
            session()->flash('mgs_error', 'Email hoặc tên người dùng chưa được nhập!');
            return $this->forgot();
        }

        if (empty($verifyCode)) {
            session()->flash('mgs_error', 'Mã xác nhận chưa được nhập!');
            return $this->forgot('verify', $username);
        }

        $user = User::whereUsername($username)->orWhere('email', $username)->first();
        if ($user == null) {
            session()->flash('mgs_error', 'Không tìm thấy thông tin người dùng!');
            return $this->forgot();
        }
        if (empty($user->hash_forgot)) {
            session()->flash('mgs_error', 'Một lỗi đã xảy ra!');
            return $this->forgot();
        }

        if (!Hash::check($verifyCode, $user->hash_forgot)) {
            session()->flash('mgs_error', 'Mã xác nhận không chính xác!');
            return $this->forgot('verify', $username);
        }

        return $this->forgot('new', $user->hash_forgot);
    }

    private function renewPassword($request): Factory|View|RedirectResponse|Application
    {
        if (empty($request->hash)) {
            session()->flash('mgs_error', 'Mã hash không tồn tại. Hãy thao tác lại!');
            return $this->forgot();
        }

        if (empty($request->new_password) || empty($request->new_password_2)) {
            session()->flash('mgs_error', 'Phải nhập đầy đủ 2 ô mật khẩu!');
            return $this->forgot('new', $request->hash);
        }

        if ($request->new_password != $request->new_password_2) {
            session()->flash('mgs_error', '2 mật khẩu được nhập không giống nhau!');
            return $this->forgot('new', $request->hash);
        }

        $user = User::whereHashForgot($request->hash)->first();
        if ($user == null) {
            session()->flash('mgs_error', 'Không tìm thấy thông tin người dùng. Hãy thao tác lại!');
            return $this->forgot();
        }

        $user->password = Hash::make($request->new_password);
        $user->hash_forgot = null;
        $user->save();

        session()->flash('notif', 'Mật khẩu đã được thay đổi thành công. Vui lòng đăng nhập lại!');
        TraceSystem::setTrace("User có username là \"{$user->username}\" thực hiện quên mật khẩu thành công!");
        return redirect()->to('/login');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        return redirect()->route('auth.view');
    }

    public function createApiKey(CreateApiRequest $request): JsonResponse
    {
        $name = trim($request->name);
        $now = strtotime(now());
        $apiKey = sha1(Hash::make($now));

        ModelService::insert(ApiData::class, [
            'api_name' => $name,
            'api_callback' => $request->callback,
            'api_key' => $apiKey,
            'api_expire' => $now
        ]);

        return response()->json([
            'success' => 1,
            'datas' => [
                'name' => $name,
                'callback' => $request->callback,
                'key' => $apiKey
            ]
        ]);
    }
}

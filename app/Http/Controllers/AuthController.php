<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateApiRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Services\AuthService;
use App\Http\Services\ModelService;
use App\Models\ApiData;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        session()->flash('notif', 'Tạo tài khoản thành công!');
        return redirect()->route('auth.view');
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

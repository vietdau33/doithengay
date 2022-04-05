<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Services\AuthService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

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
}

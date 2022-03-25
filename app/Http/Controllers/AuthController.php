<?php

namespace App\Http\Controllers;

use App\Http\Services\AuthService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

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

    public function loginPost()
    {
        return $this->authService->verify_login();
    }

    public function register(): Factory|View|Application
    {
        return view('auth.register');
    }

    public function registerPost()
    {
        return $this->authService->verify_login();
    }
}

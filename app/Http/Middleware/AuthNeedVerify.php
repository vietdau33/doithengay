<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthNeedVerify
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response|RedirectResponse|JsonResponse
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse
    {
        if(!Auth::check()) {
            return redirect()->to('/login');
        }
        if(user()->inactive === 1){
            Auth::logout();
            session()->flash('mgs_error', 'Tài khoản đã bị khóa.');
            return redirect()->to('/login');
        }
        if(user()->verified === 1){
            session()->flash('mgs_error', 'Tài khoản đã bị khóa.');
            return redirect()->to('/');
        }
        return $next($request);
    }
}

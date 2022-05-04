<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class Authenticated
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Response|RedirectResponse|JsonResponse
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse
    {
        if(!Auth::check()) {
            session()->put('url-redirect', request()->fullUrl());
            return redirect()->to('/login');
        }
        if(user()->inactive === 1){
            Auth::logout();
            session()->flash('mgs_error', 'Tài khoản đã bị khóa.');
            return redirect()->to('/login');
        }
        if(user()->verified === 0){
            return redirect()->to('/verify');
        }
        return $next($request);
    }
}

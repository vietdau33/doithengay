<?php

namespace App\Http\Middleware;

use App\Models\SystemSetting;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CountVisit
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if(!session()->has('____counted_visit____')){
            $couting = (int)SystemSetting::getSetting('____counted_visit____', 'system', 0);
            SystemSetting::setSetting('____counted_visit____', $couting + 1);
            session()->put('____counted_visit____', true);
        }
        return $next($request);
    }
}

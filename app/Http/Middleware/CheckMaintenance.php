<?php

namespace App\Http\Middleware;

use App\Models\SystemSetting;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckMaintenance
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
        $isUrlMaintenance = $request->is('maintenance');
        $isMaintenance = SystemSetting::getSetting('system_active', 'system', '1') == '0';
        $isMaintenance &= logined() && !is_admin();
        if($isMaintenance && !$isUrlMaintenance){
            return redirect('maintenance');
        }
        if(!$isMaintenance && $isUrlMaintenance){
            return redirect('/');
        }
        return $next($request);
    }
}

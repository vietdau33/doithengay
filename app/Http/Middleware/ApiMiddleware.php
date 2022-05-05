<?php

namespace App\Http\Middleware;

use App\Http\Services\ApiService;
use App\Models\ApiData;
use App\Models\SystemSetting;
use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use ReflectionClass;

class ApiMiddleware
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
        if (isset($request->admin_api_key)) {
            return $this->checkAdminCallApi($request, $next);
        }
        $apiKey = $request->api_key;
        if ($apiKey == null) {
            return $this->error('API_KEY_NOTFOUND');
        }

        $apiData = ApiData::with('user')->whereApiKey($apiKey)->first();
        if ($apiData == null) {
            return $this->error('API_KEY_NOTFOUND');
        }

        if ($apiData->active === 0) {
            return $this->error('API_KEY_NOTACTIVE');
        }

        if (empty($apiData->user)) {
            return $this->error('API_KEY_NOTSEEOWNER');
        }

        //if($apiData->api_expire < strtotime(now())){
        //    return $this->error('API_KEY_EXPIRE');
        //}

        $request->user = $apiData->user;
        return $next($request);
    }

    private function checkAdminCallApi($request, $next): JsonResponse
    {
        $apiAdminKey = SystemSetting::getSetting('api_admin_hash');
        if($apiAdminKey != $request->admin_api_key){
            return $this->error('ADMIN_API_KEY_ERROR');
        }
        return $next($request);
    }

    private function error(string $code): JsonResponse
    {
        $refl = new ReflectionClass(ApiService::class);
        $n_code = $refl->getConstants()[$code] ?? 500;
        return response()->json([
            'success' => 0,
            'message' => ApiService::getMgsError($n_code),
            'hash' => '',
            'code' => $code
        ]);
    }
}

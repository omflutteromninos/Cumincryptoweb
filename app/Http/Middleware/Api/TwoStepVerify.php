<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TwoStepVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!empty(Auth::user())) {
            if( !empty(Auth::user()->is_verified)) {
                if(Auth::user()->status == STATUS_ACTIVE) {
                    if(Auth::user()->role == USER_ROLE_USER) {
                        if(Auth::user()->g2f_enabled){
                            $verify_info = Cache::get('g2f_checked');
                            if ($verify_info) {
                                return $next($request);
                            } else {
                                $data = ['g2f_verify'=>true];
                                return response()->json(['success' => false, 'data' => $data, 'message' => __('Need two step verification!')]);
                            }
                        }else{
                            return $next($request);
                        }
                    } else {
                        $data = ['force_logout'=>true];
                        Auth::user()->token()->revoke();
                        Cache::forget('g2f_checked');
                        return response()->json(['success' => false, 'data' => $data, 'message' => __('You are not eligible user!')]);
                    }
                } else {
                    $data = ['force_logout'=>true];
                    Auth::user()->token()->revoke();
                    Cache::forget('g2f_checked');
                    return response()->json(['success' => false, 'data' => $data, 'message' => __('Your account is currently deactivate, Please contact to admin')]);
                }
            } else {
                $data = ['force_logout'=>true];
                Auth::user()->token()->revoke();
                Cache::forget('g2f_checked');
                return response()->json(['success' => false, 'data' => $data, 'message' => __('You are logged out! Before login please verify your email!')]);
            }
        }
        else {
            $data = ['force_logout'=>true];
            Auth::user()->token()->revoke();
            Cache::forget('g2f_checked');
            return response()->json(['success' => false, 'data' => $data, 'message' => __('Login again!')]);
        }

    }
}

<?php

namespace App\Http\Middleware;

use App\Model\AdminSetting;
use Closure;

class CheckSwapStatus
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
        $data = AdminSetting::where(['slug' => 'swap_enabled'])->first();
        if($data){
            if($data->value){
                return $next($request);
            }else{
                if($request->headers->get('accept') == 'application/json'){
                    return response()->json(['success'=>false,'data'=>[],'message'=>__('Swap feature disabled now! Please try later..')]);
                }else{
                    return redirect()->route('userDashboard')->with(['dismiss'=>__('Swap feature disabled now! Please try later..')]);
                }
            }
        }else{
            return $next($request);
        }
    }
}

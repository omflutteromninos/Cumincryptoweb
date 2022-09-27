<?php

namespace App\Http\Middleware;

use Closure;

class CoWalletFeatureMiddleware
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
        if(co_wallet_feature_active()) return $next($request);
        else return back();
    }
}

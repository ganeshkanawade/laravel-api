<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiJwtMiddleware
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
        try{
            $url =  $request->root();
            $url_details = parse_url($url);
            $payload =  (array)JWTAuth::parseToken()->getPayload(JWTAuth::getToken())->get('domain');
            if($payload[0] != $url_details['host'])
            {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        }
        catch(JWTException $e)
        {
            
        }
        
        return $next($request);
    }
}

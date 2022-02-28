<?php

namespace App\Http\Middleware;

use Closure;
use Response;


class CorsMiddleware
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

        // // Версия 0.1
        // $response = $next($request);

        // $response->header('Access-Control-Allow-Origin','*');

        // // Версия 0.1.1
        // $response->header('Access-Control-Allow-Headers','Content-Type,Authorization');
        // $response->header('Access-Control-Allow-Methods','GET,PUT,POST,DELETE,OPTIONS');

        // return $response;


        // Версия 0.2
        $headers = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, Origin',
            'Access-Control-Max-Age' => '3600',
            'Cache-Control' => 'nocache, no-store, max-age=0, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => 'Sun, 02 Jan 1990 00:00:00 GMT'
        ];

        if($request->getMethod() == "OPTIONS") {
            // The client-side application can set only headers allowed in Access-Control-Allow-Headers
            return response()->make('OK', 200, $headers);
        }

        $response = $next($request);
        foreach($headers as $key => $value)
            $response->headers->set($key, $value);
        return $response;

    }
}




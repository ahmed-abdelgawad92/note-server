<?php
namespace App\Http\Middleware;

use Closure;

class Cors {
    /*
   public function handle($request, Closure $next)
   {
        return $next($request)
        ->header("Access-Control-Allow-Origin: *")
        ->header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With, x-xsrf-token')
        ->header('Access-Control-Allow-Credentials: true');
   }
   */
    public function handle($request, Closure $next)
    {
        if ($request->isMethod('OPTIONS')){
            $response = Response::make();
        } else {
            $response = $next($request);
        }
        return $response
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application');
    }
}
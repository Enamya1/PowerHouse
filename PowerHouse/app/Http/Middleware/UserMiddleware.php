<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
     {


        $auth = $request->user();
        // check for the user authontication 
        if (!$auth){
            return response()->json([
                'mesaage'=>'unauthorise user'
            ],401);
        };

    
        if ($auth->role_id != 1 ){
            return response()->json([
                'message'=>'mother fucker back off you are not a admin !!!'
            ],403);
        };

        
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModeratorMiddleware
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
        
        // check if the user is a moderator ( by the role id 1 admin 2 user 3 moderator)
        if ($auth->role_id !== 3 ){
            return response()->json([
                'message'=>'mother fucker back off you are not a moderator !!!'
            ],403);
        };
        
        // check if the user is active
        if (!$auth->is_active){
            return response()->json([
                'message'=>'your account is not active yet ❌'
            ],403);
        }

        return $next($request);
    }
}

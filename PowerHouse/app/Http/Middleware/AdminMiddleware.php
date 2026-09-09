<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
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

        // check if the user is a admin ( by the reole id 1 admin 2 user 3 modirator)
        if ($auth->role_id != 1 ){
            return response()->json([
                'message'=>'mother fucker back off you not a admin '
            ],403);
        };

        
        return $next($request);
    }
}

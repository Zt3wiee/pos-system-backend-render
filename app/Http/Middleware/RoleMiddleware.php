<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 2️⃣ Check if user is authenticated and has the required role
        // if (!Auth::check() || Auth::user()->role !== $role) {
        //     return response()->json([
        //         'message' => 'Unauthorized'
        //     ], 403);   
        // }
        
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        //for one role one permission
        // if (Auth::user()->role !== $role) {
        //     return response()->json([
        //         'message' => 'you do not have permission to access this resource'
        //     ], 403);
        // }


        //for multiple roles same permission
        //Split roles by comma
       
        // Check if current user's role is allowed
        if (!in_array(Auth::user()->role, $roles)) {
            return response()->json([
                'message' => 'you do not have permission to access this resource'
            ], 403);
        }
        // 3️⃣ Allow request to continue
        return $next($request);
    }
}

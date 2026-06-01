<?php

namespace App\Http\Middleware;

use App\Models\ConnectionRequest;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyUser
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = ConnectionRequest::where('connection_id', $request->header('Connection-Id'))
                ->where('auth_code', $request->header('Auth-Code'))
                ->first();  // Execute the query and get first record
        
        if (!$user) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'user not authenticated'
            ]);
        }
        
        $request->merge(['user_id' => $user->user_id]);
        // dd($request);
        return $next($request);
    }
}

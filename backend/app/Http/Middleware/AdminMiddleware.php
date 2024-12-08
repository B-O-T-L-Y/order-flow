<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return response()->json([
                'error' => [
                    'message' => 'You must be authenticated to access this resource.',
                    'code' => 'AUTHENTICATION_REQUIRED'
                ]
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!$request->user()->is_admin) {
            return response()->json([
                'error' => [
                    'message' => 'You are not authorized to perform this action.',
                    'code' => 'UNAUTHORIZED_ACTION'
                ]
            ], Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}

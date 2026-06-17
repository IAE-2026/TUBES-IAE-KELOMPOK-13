<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $key = $request->header('X-IAE-KEY');

        if ($key !== '102022400220') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized (Invalid API Key)'
            ], 401);
        }

        return $next($request);
    }
}
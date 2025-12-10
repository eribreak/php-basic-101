<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequest
{
    // Log thông tin request
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('Request logged', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
        ]);

        $response = $next($request);

        return $response;
    }
}

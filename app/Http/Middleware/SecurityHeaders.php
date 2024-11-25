<?php

namespace App\Http\Middleware;

class SecurityHeaders
{
    public function handle($request, $next)
    {
        $response = $next($request);
        
        $cspDirectives = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:*",
            "script-src-elem 'self' 'unsafe-inline' http://localhost:*",
            "style-src 'self' 'unsafe-inline' http://localhost:*",
            "style-src-elem 'self' 'unsafe-inline' http://localhost:*",
            "img-src 'self' data: blob:",
            "font-src 'self' data:",
            "connect-src 'self' ws://localhost:* http://localhost:*"
        ];

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Content-Security-Policy', implode('; ', $cspDirectives));
        $response->headers->set('Permissions-Policy', 'geolocation=()');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        
        return $response;
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent clickjacking — only allow framing from same origin
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Stop browsers from MIME-sniffing content type
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Only send origin on same-origin requests; send nothing on cross-origin
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Disable browser features that aren't needed
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');

        // Legacy XSS filter (still respected by some older browsers)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Remove server fingerprinting header if present
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }
}

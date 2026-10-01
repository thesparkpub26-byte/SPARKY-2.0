<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Browser-side protections added to every response. */
class SecurityHeaders
{
    /**
     * Adds the browser security headers (content type, framing, referrer, content security policy) to every
     * response.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');           // never guess a file's type (blocks disguised scripts)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');               // other sites can't frame this one (clickjacking)
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');

        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // API answers are data, not pages: nothing to script or frame, and never cached by shared proxies,
        // except the few public reader lists that opt in with `cache.headers:public;...` in routes/api.php
        // (the same for every visitor, so browsers and a CDN may keep them for a short while)
        if ($request->is('api/*')) {
            $response->headers->set('Content-Security-Policy', "default-src 'none'; frame-ancestors 'none'");
            if (!$response->headers->hasCacheControlDirective('public')) {
                $response->headers->set('Cache-Control', 'no-store, private');
            }
        } elseif ($policy = $this->pagePolicy()) {
            $response->headers->set($policy['header'], $policy['value']);
        }

        return $response;
    }

    /**
     * The Content-Security-Policy for the site's pages: scripts, frames and connections may only come from
     * the site itself plus the few outside services it really uses. Even if someone got HTML onto a page,
     * the browser would refuse to run it.
     *
     * CSP_MODE=enforce | report | off. Default: enforce on a live site, report-only while developing (the
     * browser console lists anything the policy would block, without breaking the page).
     */
    private function pagePolicy(): ?array
    {
        $mode = strtolower((string) config('security.csp_mode'));
        if ($mode === 'off') {
            return null;
        }

        $scripts = ["'self'", 'https://cdnjs.cloudflare.com'];             // PDF viewer for published issues
        $connect = ["'self'", 'https://cdnjs.cloudflare.com'];
        $styles  = ["'self'", "'unsafe-inline'", 'https://fonts.googleapis.com'];
        $fonts   = ["'self'", 'https://fonts.gstatic.com', 'data:'];

        // When the built CSS/JS are served from a CDN (ASSET_URL), the browser must be allowed to load them from there
        if ($cdn = $this->assetOrigin()) {
            $scripts[] = $cdn;
            $styles[]  = $cdn;
            $fonts[]   = $cdn;
        }

        // The Vite dev server (hot reload) only exists while developing
        if (is_file(public_path('hot'))) {
            $scripts[] = 'http://localhost:5173';
            $scripts[] = 'http://127.0.0.1:5173';
            $connect = array_merge($connect, ['http://localhost:5173', 'ws://localhost:5173', 'http://127.0.0.1:5173', 'ws://127.0.0.1:5173']);
        }

        $directives = [
            "default-src 'self'",
            'script-src ' . implode(' ', $scripts),
            'style-src ' . implode(' ', $styles),
            'font-src ' . implode(' ', $fonts),
            "img-src 'self' data: blob: https:",                              // article photos can come from any https address
            "media-src 'self' blob:",
            'connect-src ' . implode(' ', $connect),
            'worker-src ' . "'self' blob: https://cdnjs.cloudflare.com",
            'frame-src https://www.youtube.com https://www.youtube-nocookie.com',
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
        ];

        return [
            'header' => $mode === 'enforce' ? 'Content-Security-Policy' : 'Content-Security-Policy-Report-Only',
            'value'  => implode('; ', $directives),
        ];
    }

    /** The scheme and host of ASSET_URL (e.g. https://cdn.example.com), or null when assets come from this site. */
    private function assetOrigin(): ?string
    {
        $url = parse_url((string) config('app.asset_url'));

        return isset($url['scheme'], $url['host'])
            ? $url['scheme'] . '://' . $url['host'] . (isset($url['port']) ? ':' . $url['port'] : '')
            : null;
    }
}

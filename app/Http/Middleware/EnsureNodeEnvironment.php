<?php

namespace App\Http\Middleware;

use App\Support\CoreNodeVerifier;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNodeEnvironment
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow health checks if configured
        if ($request->is('up')) {
            return $next($request);
        }

        if (!CoreNodeVerifier::check()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Service Unavailable (Node Configuration Error).'
                ], 503);
            }

            return response(
                '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>503 Service Unavailable</title><style>*{box-sizing:border-box}body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;background:#f8fafc;color:#1e293b;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;padding:20px}.card{background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 10px 25px -5px rgba(0,0,0,0.05);max-width:520px;width:100%;padding:40px 32px;text-align:center}.icon{display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;border-radius:50%;background:#fee2e2;color:#dc2626;font-size:24px;font-weight:700;margin-bottom:20px}h1{font-size:22px;font-weight:700;color:#0f172a;margin:0 0 12px}p{font-size:15px;color:#64748b;line-height:1.6;margin:0 0 20px}.code{font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,monospace;font-size:13px;background:#f1f5f9;color:#475569;padding:6px 12px;border-radius:6px;display:inline-block}</style></head><body><div class="card"><div class="icon">!</div><h1>503 | Service Unavailable</h1><p>The application runtime node environment is currently unavailable. Please verify your system node configuration or contact the system administrator.</p><div class="code">ERR_NODE_CONFIG_REF_503</div></div></body></html>',
                503,
                ['Content-Type' => 'text/html; charset=UTF-8']
            );
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\GenericUser;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSmisSso
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['message' => 'SMIS token missing'], 401);
        }

        $secret = config('smis-sso.app_key');
        if (!$secret) {
            return response()->json(['message' => 'SMIS app key not configured'], 500);
        }

        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return response()->json(['message' => 'Invalid SMIS token'], 401);
        }

        [$headerB64, $payloadB64, $signature] = $parts;
        $unsigned = $headerB64 . '.' . $payloadB64;

        // Recompute signature using HS256
        $expected = rtrim(strtr(base64_encode(hash_hmac('sha256', $unsigned, $secret, true)), '+/', '-_'), '=');
        if (!hash_equals($expected, $signature)) {
            return response()->json(['message' => 'Invalid SMIS token'], 401);
        }

        $payloadJson = base64_decode(strtr($payloadB64, '-_', '+/'));
        $info = json_decode($payloadJson, true) ?? [];

        // Check expiry with clock skew
        $clockSkew = (int) config('smis-sso.clock_skew', 120);
        $now = time();
        if (isset($info['exp']) && $info['exp'] + $clockSkew < $now) {
            return response()->json(['message' => 'SMIS token expired'], 401);
        }

        // Make token info available on the request and as the authenticated user
        $request->attributes->set('smisInfo', $info);
        auth()->setUser(new GenericUser([
            'id' => $info['sub'] ?? $info['username'] ?? null,
            'name' => $info['username'] ?? null,
            'email' => $info['email'] ?? null,
            'info' => $info,
        ]));

        return $next($request);
    }
}

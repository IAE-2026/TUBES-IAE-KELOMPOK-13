<?php

namespace App\Http\Middleware;

use App\Models\Role;
use App\Models\User;
use Closure;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FederatedSsoMiddleware
{
    protected function mapSsoRoleToLocalRole(string $ssoRole): string
    {
        $normalizedRole = strtolower(trim($ssoRole));

        return match ($normalizedRole) {
            'admin', 'administrator', 'superadmin' => 'admin_farmasi',
            'dokter', 'doctor', 'dosen', 'staff', 'apoteker' => 'apoteker',
            default => 'pasien',
        };
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $jwt = $request->bearerToken();

        if (!$jwt) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unauthorized - Missing Bearer Token',
                'errors'  => null
            ], 401);
        }

        try {
            // Fetch and cache JWKS from SSO server (cached for 1 hour)
            $jwks = Cache::remember('iae_sso_jwks', 3600, function () {
                $url = env('SSO_JWKS_URL', 'https://iae-sso.virtualfri.id/api/v1/auth/jwks');
                $response = Http::get($url);
                if ($response->successful()) {
                    return $response->json();
                }
                throw new \Exception('Failed to fetch JWKS from SSO server: ' . $response->status());
            });

            // Decode JWT using the JWKS keys
            // Firebase JWT JWK::parseKeySet parses the JWKS JSON array
            $decoded = JWT::decode($jwt, JWK::parseKeySet($jwks));

            $email = $decoded->email ?? ($decoded->sub ?? null);
            if (!$email) {
                throw new \Exception('Email claim missing in token');
            }

            $name = $decoded->name ?? 'User SSO';

            $ssoRole = $decoded->role
                ?? $decoded->roles
                ?? $decoded->scope
                ?? $decoded->user_role
                ?? 'warga';

            if (is_array($ssoRole)) {
                $ssoRole = $ssoRole[0] ?? 'warga';
            }

            $localRoleName = $this->mapSsoRoleToLocalRole((string) $ssoRole);
            Log::info('ROLE CHECK', [
        'email' => $email,
        'sso_role' => $ssoRole,
        'local_role' => $localRoleName,
        'required_roles' => $roles,
]);

            $role = Role::where('name', $localRoleName)->first();
            if (!$role) {
                $role = Role::firstOrCreate(['name' => 'pasien']);
            }

            // Sync user to local database
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => bcrypt('SSO_USER_PASSWORD_PLACEHOLDER'),
                    'role_id' => $role->id
                ]
            );

            // Optional: If middleware parameters specify required roles, check them
            if (!empty($roles) && !in_array($localRoleName, array_map('strtolower', $roles), true)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Forbidden - You do not have access to this resource',
                    'errors'  => null
                ], 403);
            }

            // Authenticate user in request
            $request->setUserResolver(fn() => $user);
            auth()->login($user);

            return $next($request);

        } catch (\Exception $e) {
            Log::error('SSO Authentication failed: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Unauthorized - Invalid or expired token',
                'errors'  => $e->getMessage()
            ], 401);
        }
    }
}

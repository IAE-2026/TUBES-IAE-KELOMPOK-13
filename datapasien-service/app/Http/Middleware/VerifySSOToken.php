<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use App\Services\SSOService;
use App\Models\SsoUser;
use Firebase\JWT\JWT;
use Firebase\JWT\JWK;
use Carbon\Carbon;
 
class VerifySSOToken
{
    public function __construct(private SSOService $sso) {}
 
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
 
        if (!$token) {
            return response()->json(['message' => 'Token tidak ditemukan'], 401);
        }
 
        try {
            $jwks = $this->sso->getJWKS();
            $keys = JWK::parseKeySet($jwks);
            $decoded = JWT::decode($token, $keys);
 
            $this->mapLocalRole($request, $decoded, $token);
 
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token tidak valid: ' . $e->getMessage()], 401);
        }
 
        return $next($request);
    }
 
    private function mapLocalRole(Request $request, object $decoded, string $rawToken): void
    {
        $sub   = $decoded->sub ?? '';
        $email = $decoded->email ?? $sub;
        $name  = $decoded->name ?? null;
 
        
        $role = str_contains($email, '@ktp.iae.id') ? 'warga' : 'admin';
 
        $ssoUser = SsoUser::updateOrCreate(
            ['sub' => $sub],
            [
                'email'         => $email,
                'name'          => $name,
                'local_role'    => $role,
                'last_token'    => $rawToken, 
                'last_login_at' => Carbon::now(),
            ]
        );
 
        $request->merge([
            'sso_user'   => $ssoUser,
            'local_role' => $ssoUser->local_role,
        ]);
    }
}
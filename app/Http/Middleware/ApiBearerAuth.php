<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\TokenService;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\Auth;

class ApiBearerAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        // 1. Kiểm tra Bearer Token
        if ($token) {
            $payload = TokenService::validateToken($token);

            if (!$payload) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token xác thực không hợp lệ hoặc đã hết hạn (Vui lòng đăng nhập lại).',
                ], 401);
            }

            $user = NguoiDung::find($payload['sub']);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản liên kết với token này không còn tồn tại.',
                ], 401);
            }

            Auth::setUser($user);
            $request->setUserResolver(fn () => $user);

            return $next($request);
        }

        // 2. Không có Bearer Token -> 401 Unauthorized
        return response()->json([
            'success' => false,
            'message' => 'Chưa xác thực danh tính (Vui lòng cung cấp Header: Authorization: Bearer <token>).',
        ], 401);
    }
}

<?php

namespace App\Services;

use App\Models\NguoiDung;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class TokenService
{
    /**
     * Thời gian sống của token: 30 ngày (tính bằng giây)
     */
    const TOKEN_LIFETIME = 2592000;

    /**
     * Tạo Bearer Token mã hóa bảo mật từ thông tin User
     */
    public static function createToken(NguoiDung $user): string
    {
        $payload = [
            'sub'   => $user->id,
            'phone' => $user->so_dien_thoai,
            'email' => $user->email,
            'role'  => $user->id_vai_tro,
            'iat'   => time(),
            'exp'   => time() + self::TOKEN_LIFETIME,
        ];

        return Crypt::encryptString(json_encode($payload));
    }

    /**
     * Xác thực và giải mã Bearer Token
     */
    public static function validateToken(string $token): ?array
    {
        try {
            $decrypted = Crypt::decryptString($token);
            $payload = json_decode($decrypted, true);

            if (!is_array($payload) || !isset($payload['sub'], $payload['exp'])) {
                return null;
            }

            // Kiểm tra token đã hết hạn chưa
            if ($payload['exp'] < time()) {
                return null;
            }

            return $payload;
        } catch (DecryptException $e) {
            return null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}

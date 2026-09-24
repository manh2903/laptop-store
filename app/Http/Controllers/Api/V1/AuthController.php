<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AuthController extends BaseApiController
{
    /**
     * API Đăng ký tài khoản
     * POST /api/v1/auth/register
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ho_ten'            => 'required|string|max:100',
            'email'             => 'required|email|unique:nguoi_dung,email',
            'so_dien_thoai'     => 'required|regex:/^[0-9]{10,11}$/|unique:nguoi_dung,so_dien_thoai',
            'mat_khau'          => 'required|min:6',
            'nhap_lai_mat_khau' => 'required|same:mat_khau',
        ], [
            'email.unique'         => 'Email này đã được sử dụng.',
            'so_dien_thoai.unique' => 'Số điện thoại này đã được đăng ký.',
            'nhap_lai_mat_khau.same'=> 'Mật khẩu xác nhận không khớp.',
            'mat_khau.min'         => 'Mật khẩu phải từ 6 ký tự trở lên.',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Dữ liệu không hợp lệ', $validator->errors(), 422);
        }

        $otp = rand(1000, 9999);

        $user = NguoiDung::create([
            'ho_ten'          => $request->ho_ten,
            'email'           => $request->email,
            'so_dien_thoai'   => $request->so_dien_thoai,
            'mat_khau'        => Hash::make($request->mat_khau),
            'id_vai_tro'      => 2, // Khách hàng
            'token_kich_hoat' => (string) $otp,
            'trang_thai'      => 0, // Chờ kích hoạt OTP
        ]);

        $token = \App\Services\TokenService::createToken($user);

        return $this->sendResponse([
            'access_token'  => $token,
            'token_type'    => 'Bearer',
            'expires_in'    => \App\Services\TokenService::TOKEN_LIFETIME,
            'user'          => [
                'user_id'       => $user->id,
                'ho_ten'        => $user->ho_ten,
                'email'         => $user->email,
                'so_dien_thoai' => $user->so_dien_thoai,
                'test_otp'      => $otp,
            ]
        ], 'Đăng ký tài khoản thành công! Vui lòng xác thực mã OTP.', 201);
    }

    /**
     * API Đăng nhập
     * POST /api/v1/auth/login
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'so_dien_thoai' => 'required',
            'mat_khau'      => 'required',
        ], [
            'so_dien_thoai.required' => 'Vui lòng cung cấp số điện thoại.',
            'mat_khau.required'      => 'Vui lòng cung cấp mật khẩu.',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Vui lòng nhập đầy đủ thông tin', $validator->errors(), 422);
        }

        $user = NguoiDung::where('so_dien_thoai', $request->so_dien_thoai)->first();

        if (!$user) {
            return $this->sendError('Số điện thoại này chưa được đăng ký trong hệ thống.', [], 404);
        }

        if (!Hash::check($request->mat_khau, $user->mat_khau)) {
            return $this->sendError('Mật khẩu không chính xác.', [], 401);
        }

        Auth::login($user);
        $token = \App\Services\TokenService::createToken($user);

        return $this->sendResponse([
            'access_token'  => $token,
            'token_type'    => 'Bearer',
            'expires_in'    => \App\Services\TokenService::TOKEN_LIFETIME,
            'user'          => [
                'id'            => $user->id,
                'ho_ten'        => $user->ho_ten,
                'email'         => $user->email,
                'so_dien_thoai' => $user->so_dien_thoai,
                'id_vai_tro'    => $user->id_vai_tro,
                'trang_thai'    => $user->trang_thai,
            ]
        ], 'Đăng nhập thành công!');
    }

    /**
     * API Kiểm tra tính khả dụng của số điện thoại
     * POST /api/v1/auth/check-phone
     */
    public function checkPhone(Request $request): JsonResponse
    {
        $phone = $request->phone ?? $request->so_dien_thoai;

        if (empty($phone)) {
            return $this->sendError('Số điện thoại không được để trống.', [], 422);
        }

        // Kiểm tra định dạng số điện thoại
        if (!preg_match('/^[0-9]{10,11}$/', $phone)) {
            return $this->sendError('Số điện thoại không hợp lệ (chỉ gồm 10-11 chữ số).', [], 422);
        }

        $exists = NguoiDung::where('so_dien_thoai', $phone)->exists();

        return $this->sendResponse([
            'phone'  => $phone,
            'exists' => $exists,
        ], $exists ? 'Số điện thoại đã tồn tại trên hệ thống.' : 'Số điện thoại có thể sử dụng.');
    }

    /**
     * API Xác thực mã OTP
     * POST /api/v1/auth/verify-otp
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $otp = $request->otp;
        if (empty($otp)) {
            return $this->sendError('Mã OTP không được để trống.', [], 422);
        }

        $user = null;
        if ($request->filled('phone')) {
            $user = NguoiDung::where('so_dien_thoai', $request->phone)->first();
        } elseif ($request->filled('email')) {
            $user = NguoiDung::where('email', $request->email)->first();
        } elseif ($request->filled('user_id')) {
            $user = NguoiDung::find($request->user_id);
        }

        if (!$user) {
            // Tìm theo OTP
            $user = NguoiDung::where('token_kich_hoat', $otp)->first();
        }

        if (!$user || $user->token_kich_hoat !== (string)$otp) {
            return $this->sendError('Mã OTP không chính xác hoặc đã hết hạn.', [], 400);
        }

        $user->trang_thai = 1;
        $user->token_kich_hoat = null;
        $user->save();

        return $this->sendResponse([
            'id'     => $user->id,
            'ho_ten' => $user->ho_ten,
            'email'  => $user->email,
        ], 'Kích hoạt tài khoản thành công!');
    }

    /**
     * API Gửi lại mã OTP
     * POST /api/v1/auth/resend-otp
     */
    public function resendOtp(Request $request): JsonResponse
    {
        $phone = $request->phone ?? $request->so_dien_thoai;
        $email = $request->email;

        $user = null;
        if ($phone) {
            $user = NguoiDung::where('so_dien_thoai', $phone)->first();
        } elseif ($email) {
            $user = NguoiDung::where('email', $email)->first();
        }

        if (!$user) {
            return $this->sendError('Không tìm thấy tài khoản cần gửi lại OTP.', [], 404);
        }

        $newOtp = rand(1000, 9999);
        $user->token_kich_hoat = (string) $newOtp;
        $user->save();

        return $this->sendResponse([
            'phone'    => $user->so_dien_thoai,
            'test_otp' => $newOtp,
        ], 'Đã tạo và gửi lại mã OTP mới!');
    }

    /**
     * API Quên mật khẩu
     * POST /api/v1/auth/forgot-password
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Email không hợp lệ.', $validator->errors(), 422);
        }

        $user = NguoiDung::where('email', $request->email)->first();
        if (!$user) {
            return $this->sendError('Email này chưa từng được đăng ký trong hệ thống.', [], 404);
        }

        $resetOtp = rand(1000, 9999);
        $user->token_kich_hoat = (string) $resetOtp;
        $user->save();

        return $this->sendResponse([
            'email'    => $user->email,
            'test_otp' => $resetOtp,
        ], 'Mã xác thực khôi phục mật khẩu đã được tạo thành công.');
    }

    /**
     * API Lấy thông tin tài khoản hiện tại
     * GET /api/v1/auth/me
     */
    public function me(): JsonResponse
    {
        if (!Auth::check()) {
            return $this->sendError('Chưa xác thực danh tính (Vui lòng đăng nhập).', [], 401);
        }

        $user = Auth::user();
        return $this->sendResponse([
            'id'            => $user->id,
            'ho_ten'        => $user->ho_ten,
            'email'         => $user->email,
            'so_dien_thoai' => $user->so_dien_thoai,
            'dia_chi'       => $user->dia_chi,
            'gioi_tinh'     => $user->gioi_tinh,
        ], 'Lấy thông tin tài khoản thành công.');
    }

    /**
     * API Đăng xuất
     * POST /api/v1/auth/logout
     */
    public function logout(): JsonResponse
    {
        Auth::logout();
        return $this->sendResponse(null, 'Đăng xuất thành công.');
    }
}

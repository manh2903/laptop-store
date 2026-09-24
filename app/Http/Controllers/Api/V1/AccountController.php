<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AccountController extends BaseApiController
{
    /**
     * Lấy thông tin cá nhân
     * GET /api/v1/account/profile
     */
    public function profile(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return $this->sendError('Chưa xác thực danh tính.', [], 401);
        }

        return $this->sendResponse([
            'id'            => $user->id,
            'ho_ten'        => $user->ho_ten,
            'email'         => $user->email,
            'so_dien_thoai' => $user->so_dien_thoai,
            'dia_chi'       => $user->dia_chi,
            'anh_dai_dien'  => $user->anh_dai_dien ? asset($user->anh_dai_dien) : null,
            'vai_tro'       => $user->id_vai_tro,
        ], 'Lấy thông tin tài khoản thành công.');
    }

    /**
     * Cập nhật thông tin cá nhân (Tên, địa chỉ, SĐT)
     * PUT /api/v1/account/profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return $this->sendError('Chưa xác thực danh tính.', [], 401);
        }

        $validator = Validator::make($request->all(), [
            'ho_ten'        => 'sometimes|string|max:255',
            'dia_chi'       => 'sometimes|nullable|string|max:500',
            'so_dien_thoai' => 'sometimes|string|regex:/^[0-9]{10,11}$/',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Dữ liệu cập nhật không hợp lệ.', $validator->errors(), 422);
        }

        /** @var NguoiDung $user */
        if ($request->filled('ho_ten')) {
            $user->ho_ten = $request->ho_ten;
        }
        if ($request->has('dia_chi')) {
            $user->dia_chi = $request->dia_chi;
        }
        if ($request->filled('so_dien_thoai')) {
            $user->so_dien_thoai = $request->so_dien_thoai;
        }

        $user->save();

        return $this->sendResponse([
            'id'            => $user->id,
            'ho_ten'        => $user->ho_ten,
            'email'         => $user->email,
            'so_dien_thoai' => $user->so_dien_thoai,
            'dia_chi'       => $user->dia_chi,
        ], 'Cập nhật thông tin cá nhân thành công!');
    }
}

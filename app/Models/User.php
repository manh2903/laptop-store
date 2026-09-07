<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // 1. Khai báo tên bảng (Vì bảng của bạn là 'nguoi_dung')
    protected $table = 'nguoi_dung';

    // --- THÊM ĐOẠN NÀY ĐỂ SỬA LỖI ---
    // Khai báo tên cột thời gian tùy chỉnh của bạn
    const CREATED_AT = 'ngay_tao';
    const UPDATED_AT = 'ngay_cap_nhat';

    // 2. Khai báo khóa chính (nếu khóa chính là 'id' thì không cần dòng này, nhưng khai báo cho chắc)
    protected $primaryKey = 'id';

    // 3. Các cột được phép chỉnh sửa
    protected $fillable = [
        'ho_ten',        // Khớp với cột trong DB
        'email',
        'mat_khau',      // Khớp với cột trong DB
        'so_dien_thoai',
        'dia_chi',
        'id_vai_tro',    // 1: Admin, 2: Khách
        'trang_thai',    // 1: Active, 0: Block
        'anh_dai_dien'
    ];

    // 4. Ẩn cột mật khẩu khi xuất dữ liệu
    protected $hidden = [
        'mat_khau',
        'remember_token',
    ];

    // 5. CẤU HÌNH QUAN TRỌNG NHẤT: Báo cho Laravel biết cột mật khẩu tên là 'mat_khau'
    public function getAuthPassword()
    {
        return $this->mat_khau;
    }
}
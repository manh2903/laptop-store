<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class NguoiDung extends Authenticatable 
{
    use HasFactory, Notifiable;

    protected $table = 'nguoi_dung'; // Tên bảng trong DB của bạn
    public $timestamps = false;      

    protected $fillable = [
        'ho_ten', 
        'email', 
        'mat_khau', 
        'so_dien_thoai', 
        'ngay_sinh', 
        'id_vai_tro', 
        'gioi_tinh', 
        'dia_chi',
        'token_kich_hoat', 
        'trang_thai'       
    ];

    /**
     * Cấu hình để Laravel biết cột mật khẩu trong DB tên là 'mat_khau'
     */
    public function getAuthPassword() {
        return $this->mat_khau;
    }

    /**
     * THÊM QUAN HỆ: Một người dùng có một giỏ hàng
     * Hàm này giúp fix lỗi RelationNotFoundException trong Admin
     */
    public function gioHang()
    {
        return $this->hasOne(GioHang::class, 'id_nguoi_dung');
    }

    // Thêm hàm này vào Model User
public function getAvatarChuCaiAttribute() {
    // Lấy chữ cái đầu tiên của tên, viết hoa
    $ten = $this->ho_ten ?? 'K';
    return strtoupper(substr($ten, 0, 1));
}
}
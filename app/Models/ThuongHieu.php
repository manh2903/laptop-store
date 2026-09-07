<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThuongHieu extends Model
{
    protected $table = 'thuong_hieu'; //
    
    // Bật timestamp nhưng chỉ định tên cột tiếng Việt theo CSDL của bạn
    public $timestamps = true;
    const CREATED_AT = 'ngay_tao';
    const UPDATED_AT = 'ngay_cap_nhat';

    protected $fillable = [
        'ten_thuong_hieu', 
        'slug',
        'hinh_anh',
        'thu_tu_sap_xep',
        'noi_bat', // Thêm cột này nếu bạn muốn quản lý hàng nổi bật
        'trang_thai',
    ];
}
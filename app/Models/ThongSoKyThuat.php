<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThongSoKyThuat extends Model
{
    use HasFactory;
    
    // Khai báo tên bảng
    protected $table = 'thong_so_ky_thuat';
    
    // Cho phép gán dữ liệu hàng loạt
    protected $guarded = [];

    // Tự động chuyển JSON trong DB thành Mảng trong PHP
    protected $casts = [
        'thong_so_tuy_chinh' => 'array', 
    ];
}
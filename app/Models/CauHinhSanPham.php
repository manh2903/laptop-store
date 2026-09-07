<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CauHinhSanPham extends Model
{
    protected $table = 'cau_hinh_san_pham';
    protected $fillable = ['id_san_pham', 'loai_cau_hinh', 'ten_cau_hinh', 'gia_them'];
}
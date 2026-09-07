<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DanhMuc extends Model
{
    protected $table = 'danh_muc';
    const CREATED_AT = 'ngay_tao';
    const UPDATED_AT = 'ngay_cap_nhat';

    protected $fillable = [
        'ten_danh_muc', 
        'slug', 
        'parent_id', 
        'thu_tu', 
        'trang_thai', 
        'hinh_anh',
        'loai',
        'icon'
    ];

    // 1. Dùng cho ADMIN (để kiểm tra xóa, quản lý) - GIỮ NGUYÊN
    public function children()
    {
        return $this->hasMany(DanhMuc::class, 'parent_id')->orderBy('thu_tu', 'asc');
    }

    // 2. Dùng cho KHÁCH HÀNG (Menu, Trang chủ) - THÊM MỚI ĐOẠN NÀY
    // Chỉ lấy những con ĐANG HIỆN (trang_thai = 1)
    public function childrenActive()
    {
        return $this->hasMany(DanhMuc::class, 'parent_id')
                    ->where('trang_thai', 1) // Chỉ lấy cái đang bật
                    ->orderBy('thu_tu', 'asc');
    }

    // Mối quan hệ: Một danh mục con thuộc về một cha
    public function parent()
    {
        return $this->belongsTo(DanhMuc::class, 'parent_id');
    }
}
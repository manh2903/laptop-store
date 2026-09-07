<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DanhGia extends Model
{
    protected $table = 'danh_gia';
    protected $fillable = ['id_nguoi_dung', 'id_san_pham', 'so_sao', 'noi_dung', 'hinh_anh', 'trang_thai'];

    // 1. Liên kết User
    public function user() {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }

    // 2. Format thời gian Tiếng Việt (Vừa xong, 1 phút trước...)
    public function getThoiGianAttribute() {
        Carbon::setLocale('vi'); // Thiết lập tiếng Việt
        return $this->created_at->diffForHumans();
    }

    // 3. Label text cho từng sao
    public function getLabelSaoAttribute() {
        $labels = [
            1 => 'Rất tệ',
            2 => 'Tệ',
            3 => 'Bình thường',
            4 => 'Hài lòng',
            5 => 'Cực kỳ hài lòng'
        ];
        return $labels[$this->so_sao] ?? 'Bình thường';
    }
}
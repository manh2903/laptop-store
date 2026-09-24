<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietDonHang extends Model
{
    protected $table = 'chi_tiet_don_hang';
    protected $guarded = [];

    public function sanPham() {
        return $this->belongsTo(SanPham::class, 'id_san_pham');
    }
}

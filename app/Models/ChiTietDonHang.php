<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietDonHang extends Model
{
    public function sanPham() {
    return $this->belongsTo(SanPham::class, 'id_san_pham');
}
}

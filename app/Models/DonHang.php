<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonHang extends Model
{
    public function chiTiets() {
    return $this->hasMany(ChiTietDonHang::class, 'id_don_hang');
}
}

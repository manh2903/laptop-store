<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonHang extends Model
{
    protected $table = 'don_hang';
    protected $guarded = [];

    public function chiTiets() {
        return $this->hasMany(ChiTietDonHang::class, 'id_don_hang');
    }
}

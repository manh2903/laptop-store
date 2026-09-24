<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'ten_thuong_hieu' => $this->ten_thuong_hieu,
            'slug'            => $this->slug,
            'hinh_anh'        => $this->hinh_anh ? (str_starts_with($this->hinh_anh, 'http') ? $this->hinh_anh : asset($this->hinh_anh)) : null,
            'noi_bat'         => (bool) $this->noi_bat,
            'thu_tu_sap_xep'  => $this->thu_tu_sap_xep,
        ];
    }
}

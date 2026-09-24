<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $discountPercent = 0;
        if ($this->gia_khuyen_mai > 0 && $this->gia_ban > $this->gia_khuyen_mai) {
            $discountPercent = round((($this->gia_ban - $this->gia_khuyen_mai) / $this->gia_ban) * 100);
        }

        $featuredImage = $this->anh_dai_dien;
        if ($featuredImage && !str_starts_with($featuredImage, 'http')) {
            $featuredImage = asset($featuredImage);
        }

        return [
            'id'                 => $this->id,
            'ten_san_pham'       => $this->ten_san_pham,
            'slug'               => $this->slug,
            'ma_sku'             => $this->ma_sku,
            'gia_ban'            => (float) $this->gia_ban,
            'gia_khuyen_mai'     => (float) $this->gia_khuyen_mai,
            'gia_hien_thi'       => (float) ($this->gia_khuyen_mai > 0 ? $this->gia_khuyen_mai : $this->gia_ban),
            'phan_tram_giam'     => $discountPercent,
            'anh_dai_dien'       => $featuredImage,
            'video_url'          => $this->video_url,
            'tinh_nang_noi_bat'  => $this->tinh_nang_noi_bat,
            'so_luong_ton'       => (int) $this->so_luong_ton,
            'is_flash_sale'      => (bool) $this->is_flash_sale,
            'tra_gop_0_phan_tram'=> (bool) $this->tra_gop_0_phan_tram,
            'luot_xem'           => (int) ($this->luot_xem ?? $this->so_luot_xem ?? 0),
            'so_luot_danh_gia'   => (int) ($this->so_luot_danh_gia ?? 0),
            'gia_thu_cu'         => (float) ($this->gia_thu_cu ?? 0),
            'tro_gia'            => (float) ($this->tro_gia ?? 0),
            'mo_ta'              => $this->mo_ta,
            'thong_tin_them'     => $this->thong_tin_them,
            'uu_dai_student'     => $this->uu_dai_student,
            'uu_dai_khac'        => $this->uu_dai_khac,
            'danh_muc'           => new CategoryResource($this->whenLoaded('danhMuc')),
            'thuong_hieu'        => new BrandResource($this->whenLoaded('thuongHieu')),
            'thong_so'           => $this->whenLoaded('thongSo'),
            'hinh_anh'           => $this->whenLoaded('hinhAnh', function () {
                return $this->hinhAnh->map(function ($img) {
                    return [
                        'id'            => $img->id,
                        'duong_dan_anh' => $img->duong_dan_anh ? (str_starts_with($img->duong_dan_anh, 'http') ? $img->duong_dan_anh : asset($img->duong_dan_anh)) : null,
                        'anh_nho'       => $img->anh_nho ? (str_starts_with($img->anh_nho, 'http') ? $img->anh_nho : asset($img->anh_nho)) : null,
                        'anh_lon'       => $img->anh_lon ? (str_starts_with($img->anh_lon, 'http') ? $img->anh_lon : asset($img->anh_lon)) : null,
                        'loai'          => $img->loai,
                    ];
                });
            }),
            'bien_the'           => $this->whenLoaded('bienThe'),
            'cau_hinh'           => $this->whenLoaded('cauHinh'),
            'ngay_tao'           => $this->ngay_tao,
            'ngay_cap_nhat'      => $this->ngay_cap_nhat,
        ];
    }
}

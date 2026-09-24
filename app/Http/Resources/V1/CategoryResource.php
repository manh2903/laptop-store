<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'ten_danh_muc' => $this->ten_danh_muc,
            'slug'         => $this->slug,
            'parent_id'    => $this->parent_id,
            'loai'         => $this->loai,
            'icon'         => $this->icon,
            'hinh_anh'     => $this->hinh_anh ? (str_starts_with($this->hinh_anh, 'http') ? $this->hinh_anh : asset($this->hinh_anh)) : null,
            'thu_tu'       => $this->thu_tu,
            'children'     => CategoryResource::collection(
                $this->whenLoaded('childrenActive', function () {
                    return $this->childrenActive;
                }, function () {
                    return $this->whenLoaded('children');
                })
            ),
        ];
    }
}

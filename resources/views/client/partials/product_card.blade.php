{{-- File: resources/views/client/partials/product_card.blade.php --}}
@php
    $giaBanThuc = $product->gia_khuyen_mai > 0 ? $product->gia_khuyen_mai : $product->gia_ban;
    $giaGoc     = $product->gia_ban;
    $phanTramGiam = 0;
    if($product->gia_khuyen_mai > 0 && $product->gia_khuyen_mai < $product->gia_ban) {
        $phanTramGiam = round((($product->gia_ban - $product->gia_khuyen_mai) / $product->gia_ban) * 100);
    }
@endphp

{{-- QUAN TRỌNG: Thêm style min-width để slider không bị co thẻ --}}
<div class="product-card full-list-card" style="min-width: 236px; max-width: 236px; margin-right: 15px;">
    {{-- Badge Sale --}}
    @if($phanTramGiam > 0)
        <span class="badge-sale">Giảm {{ $phanTramGiam }}%</span>
    @endif
    
    {{-- Badge Trả góp --}}
    @if($product->tra_gop_0_phan_tram)
        <span class="badge-installment">Trả góp 0%</span>
    @endif

    <div class="card-image">
         <img src="{{ Str::startsWith($product->anh_dai_dien, 'http') ? $product->anh_dai_dien : asset($product->anh_dai_dien) }}" 
              alt="{{ $product->ten_san_pham }}">
    </div>
    
    <div class="product-name">
        <a href="{{ route('client.product.detail', $product->slug) }}">{{ Str::limit($product->ten_san_pham, 50) }}</a>
    </div>
    
    <div class="price-section">
        <span class="current-price">{{ number_format($giaBanThuc, 0, ',', '.') }}đ</span>
        @if($phanTramGiam > 0)
            <span class="old-price">{{ number_format($giaGoc, 0, ',', '.') }}đ</span>
        @endif
    </div>
    
    {{-- INFO TEXT: Dữ liệu động --}}
    <div class="info-text">
        @if($product->giam_smember > 0)
            <p class="block-smem-price">
                Smember giảm đến&nbsp;<span>{{ number_format($product->giam_smember, 0, ',', '.') }}đ</span>
            </p>
        @elseif($product->thong_tin_them)
            <p class="product-info">{{ $product->thong_tin_them }}</p>
        @endif

        @if($product->uu_dai_student)
            <p class="student-discount">{{ Str::limit($product->uu_dai_student, 40) }}</p>
        @endif
        
        @if($product->uu_dai_khac)
            <p class="coupon-price">{{ Str::limit($product->uu_dai_khac, 60) }}</p>
        @else
            {{-- Giữ khung nếu không có dữ liệu --}}
            <p class="coupon-price" style="visibility: hidden">...</p>
        @endif
    </div>
    
    <div class="card-footer">
        @if($product->danh_gia > 0)
            <div class="rating"><i class="fa-solid fa-star"></i> {{ $product->danh_gia }}</div>
        @else
             <div class="rating"></div>
        @endif
        <div class="like-btn"><i class="ri-heart-line"></i> Yêu thích</div>
    </div>
</div>
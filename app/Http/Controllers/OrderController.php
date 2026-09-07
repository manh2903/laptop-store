<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonHang;
use App\Models\ChiTietDonHang;
use App\Models\GioHang;
use App\Models\SanPham;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // 1. Xử lý khi bấm nút "MUA NGAY"
    public function buyNow(Request $request)
    {
        // Xóa session cũ để tránh bị lưu đè
        session()->forget('buy_now_item');

        // Lưu dữ liệu sản phẩm muốn mua ngay vào Session
        session(['buy_now_item' => [
            'id_san_pham' => $request->id_san_pham,
            'so_luong'    => $request->so_luong ?? 1,
        ]]);

        // Chuyển hướng sang trang thanh toán
        return redirect()->route('order.checkout');
    }

    // 2. Hiển thị trang Thanh toán (Checkout)
    public function checkout()
    {
        $userId = Auth::id();
        $backUrl = route('home'); // Mặc định quay về trang chủ

        // TRƯỜNG HỢP A: Mua ngay (Lấy từ Session)
        if (session()->has('buy_now_item')) {
            $buyNow = session('buy_now_item');
            $sanPham = SanPham::find($buyNow['id_san_pham']);
            
            if (!$sanPham) {
                return redirect()->route('home')->with('error', 'Sản phẩm không tồn tại');
            }

            // Tạo danh sách giả lập để dùng chung view
            $items = collect([(object)[
                'id_san_pham' => $sanPham->id,
                'so_luong'    => $buyNow['so_luong'],
                'sanPham'     => $sanPham
            ]]);
            
            // Link quay lại: Về đúng trang chi tiết sản phẩm đó
            $backUrl = route('client.product.detail', $sanPham->slug);
        } 
        // TRƯỜNG HỢP B: Mua từ Giỏ hàng (Lấy từ DB)
        else {
            $gioHang = GioHang::where('id_nguoi_dung', $userId)->first();
            if (!$gioHang || $gioHang->chiTietGioHang->isEmpty()) {
                return redirect()->route('cart.index')->with('warning', 'Giỏ hàng trống!');
            }
            $items = $gioHang->chiTietGioHang;
            
            // Link quay lại: Về trang giỏ hàng
            $backUrl = route('cart.index');
        }

        // Tính tổng tiền
        $totalPrice = $items->sum(fn($item) => ($item->sanPham->gia_khuyen_mai ?: $item->sanPham->gia_ban) * $item->so_luong);

        // Truyền đầy đủ biến sang View
        return view('client.checkout', compact('items', 'totalPrice', 'backUrl'));
    }

    // 3. Xử lý Lưu đơn hàng (Store)
    public function store(Request $request)
    {
        $userId = Auth::id();
        
        DB::beginTransaction();
        try {
            $maDonHang = 'LTF' . strtoupper(Str::random(8));

            // A. Lưu thông tin đơn hàng chung
            $donHang = DonHang::create([
                'id_nguoi_dung' => $userId,
                'ma_don_hang' => $maDonHang,
                'ten_nguoi_nhan' => $request->ho_ten,
                'sdt_nguoi_nhan' => $request->so_dien_thoai,
                'email_nguoi_nhan' => Auth::user()->email,
                'dia_chi_giao_hang' => $request->dia_chi,
                'tong_tien' => $request->tong_tien_hidden,
                'phuong_thuc_thanh_toan' => $request->payment_method,
                'trang_thai' => 'cho_xac_nhan',
                'ghi_chu' => $request->ghi_chu,
            ]);

            // B. Chuẩn bị danh sách sản phẩm để lưu chi tiết
            $itemsToOrder = [];
            
            if (session()->has('buy_now_item')) {
                // Lấy từ session mua ngay
                $buyNow = session('buy_now_item');
                $sp = SanPham::find($buyNow['id_san_pham']);
                $itemsToOrder[] = (object)[
                    'id_san_pham' => $sp->id,
                    'so_luong' => $buyNow['so_luong'],
                    'gia' => $sp->gia_khuyen_mai ?: $sp->gia_ban
                ];
            } else {
                // Lấy từ giỏ hàng trong DB
                $gioHang = GioHang::where('id_nguoi_dung', $userId)->first();
                if ($gioHang) {
                    foreach($gioHang->chiTietGioHang as $item) {
                        $itemsToOrder[] = (object)[
                            'id_san_pham' => $item->id_san_pham,
                            'so_luong' => $item->so_luong,
                            'gia' => $item->sanPham->gia_khuyen_mai ?: $item->sanPham->gia_ban
                        ];
                    }
                }
            }

            // C. Lưu từng món vào bảng chi_tiet_don_hang
            foreach ($itemsToOrder as $item) {
                ChiTietDonHang::create([
                    'id_don_hang' => $donHang->id,
                    'id_san_pham' => $item->id_san_pham,
                    'so_luong'    => $item->so_luong,
                    'don_gia'     => $item->gia,
                    'thanh_tien'  => $item->gia * $item->so_luong,
                ]);
            }

            DB::commit();

            // D. Dọn dẹp sau khi đặt thành công
            if (session()->has('buy_now_item')) {
                session()->forget('buy_now_item');
            } else {
                // Xóa giỏ hàng của user hiện tại
                $gioHang = GioHang::where('id_nguoi_dung', $userId)->first();
                if($gioHang) {
                    $gioHang->chiTietGioHang()->delete();
                }
            }

            // E. Chuyển hướng thanh toán nếu chọn VNPAY
            if ($request->payment_method == 'vnpay') {
                return $this->vnpay_payment($donHang);
            }

            // F. Hoàn tất (COD)
            return view('client.checkout_success', compact('donHang'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // --- Các hàm hỗ trợ khác (VNPAY, Lịch sử) giữ nguyên ---
    public function vnpay_payment($donHang) {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('order.vnpay_return');
        $vnp_TmnCode = "MÃ_TEST"; // Thay mã của bạn
        $vnp_HashSecret = "MÃ_BÍ_MẬT"; // Thay mã của bạn

        $vnp_TxnRef = $donHang->ma_don_hang;
        $vnp_OrderInfo = "Thanh toan don hang " . $donHang->ma_don_hang;
        $vnp_OrderType = "billpayment";
        $vnp_Amount = $donHang->tong_tien * 100;
        $vnp_Locale = "vn";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) { $hashdata .= '&' . urlencode($key) . "=" . urlencode($value); } 
            else { $hashdata .= urlencode($key) . "=" . urlencode($value); $i = 1; }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        return redirect()->away($vnp_Url);
    }

    public function vnpay_return(Request $request) {
        if ($request->vnp_ResponseCode == '00') {
            $donHang = DonHang::where('ma_don_hang', $request->vnp_TxnRef)->first();
            if($donHang) {
                $donHang->update(['trang_thai' => 'da_thanh_toan']);
                if (!session()->has('buy_now_item')) {
                     $gh = GioHang::where('id_nguoi_dung', $donHang->id_nguoi_dung)->first();
                     if($gh) $gh->chiTietGioHang()->delete();
                }
                session()->forget('buy_now_item');
                return view('client.checkout_success', compact('donHang'));
            }
        }
        return redirect()->route('cart.index')->with('error', 'Thanh toán thất bại!');
    }

    public function index() {
        $orders = DonHang::where('id_nguoi_dung', Auth::id())->orderBy('id', 'desc')->get();
        return view('client.orders.index', compact('orders'));
    }

    public function show($ma_don_hang) {
        $order = DonHang::where('ma_don_hang', $ma_don_hang)->with('chiTiets.sanPham')->firstOrFail();
        return view('client.orders.show', compact('order'));
    }
}
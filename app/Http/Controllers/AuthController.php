<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str; 
use Illuminate\Http\Request;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Carbon\Carbon;

class AuthController extends Controller
{
    // --- HÀM PHỤ: GỬI EMAIL ---
    private function sendEmailOTP($email, $otp) {
        Mail::send('emails.otp', ['otp' => $otp], function($message) use ($email) {
            $message->to($email);
            $message->subject('Mã xác thực đăng ký tài khoản TFmember');
        });
    }

    // ====================================================
    // 1. CHỨC NĂNG ĐĂNG KÝ
    // ====================================================
    public function showRegisterForm() {
        return view('auth.register'); 
    }

    public function register(Request $request) {
        $request->validate([
            'ho_ten' => 'required',
            'email' => 'required|email|unique:nguoi_dung,email',
            'mat_khau' => 'required|min:6',
            'nhap_lai_mat_khau' => 'required|same:mat_khau',
            'so_dien_thoai' => 'required|unique:nguoi_dung,so_dien_thoai'
            ], [
            'email.unique' => 'Email này đã được đăng ký, vui lòng chọn email khác.',
        ]);

        $ngaySinhChuan = null;
        if ($request->ngay_sinh) {
            try {
                $ngaySinhCarbon = Carbon::createFromFormat('d/m/Y', $request->ngay_sinh);
                if ($ngaySinhCarbon->age < 6) {
                    return back()->withErrors([
                        'ngay_sinh' => 'Xin lỗi, bạn phải trên 6 tuổi mới được đăng ký tài khoản.'
                    ])->withInput(); 
                }
                $ngaySinhChuan = $ngaySinhCarbon->format('Y-m-d');
            } catch (\Exception $e) { 
                return back()->withErrors([
                    'ngay_sinh' => 'Ngày sinh không hợp lệ (Vui lòng nhập đúng định dạng ngày/tháng/năm).'
                ])->withInput();
            }
        }

        $otp = rand(1000, 9999);

        $user = NguoiDung::create([
            'ho_ten' => $request->ho_ten,
            'email' => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'ngay_sinh' => $ngaySinhChuan,
            'mat_khau' => Hash::make($request->mat_khau),
            'id_vai_tro' => 2,
            'gioi_tinh' => $request->gioi_tinh,
            'dia_chi' => $request->dia_chi,
            'token_kich_hoat' => $otp,
            'trang_thai' => 0 
        ]);

        session([
            'temp_user_id' => $user->id,
            'otp_expire' => Carbon::now()->addSeconds(60), 
            'phone' => $request->so_dien_thoai,
            'email' => $request->email
        ]);

        try {
            $this->sendEmailOTP($request->email, $otp);
        } catch (\Exception $e) {
            Log::error("Lỗi gửi mail: " . $e->getMessage());
        }

        Log::info("OTP Đăng ký: $otp");

        return redirect()->route('otp.view')->with([
            'success' => 'Đăng ký thành công! Mã OTP đã gửi về Email.',
            'test_otp' => $otp 
        ]);
    }

    // ====================================================
    // 2. CHỨC NĂNG XÁC THỰC OTP (ĐĂNG KÝ)
    // ====================================================
    public function showOtpForm() {
        if (!session('temp_user_id')) return redirect()->route('dangky');
        return view('auth.otp');
    }

    public function verifyOtp(Request $request) {
        $userId = session('temp_user_id');
        $expire = session('otp_expire');

        if (!$userId) return response()->json(['status' => 'error', 'message' => 'Phiên đăng nhập hết hạn!']);

        if (Carbon::now()->gt($expire)) {
            return response()->json(['status' => 'error', 'message' => 'Mã OTP đã hết hạn (60s)!']);
        }

        $user = NguoiDung::find($userId);

        if ($user && $user->token_kich_hoat == $request->otp) {
            $user->token_kich_hoat = null;
            $user->trang_thai = 1; 
            $user->save();

            session()->forget(['temp_user_id', 'otp_expire', 'phone', 'email']);
            
            session()->flash('success', 'Kích hoạt thành công! Mời bạn đăng nhập.');

            return response()->json([
                'status' => 'success', 
                'redirect' => route('login') 
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Mã OTP không chính xác!']);
    }

    public function resendOtp(Request $request) {
        $userId = session('temp_user_id');
        if (!$userId) return response()->json(['status' => 'error', 'message' => 'Lỗi phiên!']);

        $ip = $request->ip();
        $key = 'resend_otp_' . $ip . '_' . $userId;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'status' => 'error', 
                'message' => "Bạn gửi quá nhanh! Vui lòng đợi $seconds giây."
            ]);
        }

        $otp = rand(1000, 9999);
        $user = NguoiDung::find($userId);
        $user->token_kich_hoat = $otp;
        $user->save();

        session(['otp_expire' => Carbon::now()->addSeconds(60)]);

        try {
            $this->sendEmailOTP($user->email, $otp);
        } catch (\Exception $e) {
            Log::error("Lỗi gửi mail resend: " . $e->getMessage());
        }

        RateLimiter::hit($key, 60);

        return response()->json([
            'status' => 'success', 
            'message' => 'Đã gửi lại mã mới vào Email!', 
            'test_otp' => $otp
        ]);
    }

    // ====================================================
    // 3. CHỨC NĂNG ĐĂNG NHẬP
    // ====================================================
    public function showLoginForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
        // 1. Validate dữ liệu đầu vào
        $request->validate([
            'so_dien_thoai' => 'required',
            'mat_khau' => 'required'
        ], [
            'so_dien_thoai.required' => 'Vui lòng nhập số điện thoại',
            'mat_khau.required' => 'Vui lòng nhập mật khẩu'
        ]);

        // 2. Kiểm tra số điện thoại có tồn tại không
        $user = NguoiDung::where('so_dien_thoai', $request->so_dien_thoai)->first();

        if (!$user) {
            return back()->withErrors([
                'so_dien_thoai' => 'Số điện thoại này chưa được đăng ký.'
            ])->withInput();
        }

        // 3. Thử đăng nhập
        $credentials = [
            'so_dien_thoai' => $request->so_dien_thoai,
            'password' => $request->mat_khau 
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // ====================================================
            // LOGIC ĐIỀU HƯỚNG MỚI (ĐÃ SỬA)
            // ====================================================

            // [SỬA QUAN TRỌNG]: Đổi 'redirect_url' thành 'redirect' để khớp với JS
            $redirectUrl = $request->input('redirect'); 

            if ($redirectUrl) {
                // Nếu link có chứa chữ 'reviews-section' -> Xử lý để mở tab đánh giá
                if (str_contains($redirectUrl, 'reviews-section')) {
                     // Tách bỏ dấu # cũ đi (ví dụ: .../san-pham-a#reviews-section)
                     $cleanUrl = explode('#', $redirectUrl)[0];
                     
                     // Thêm tham số ?open_review=1 để JS bên kia nhận biết
                     // Kiểm tra xem URL gốc đã có dấu ? chưa để nối chuỗi cho đúng
                     $connector = str_contains($cleanUrl, '?') ? '&' : '?';
                     
                     return redirect()->to($cleanUrl . $connector . 'open_review=1#reviews-section')
                            ->with('success', 'Đăng nhập thành công! Mời bạn viết đánh giá.');
                }
                
                // Nếu là link khác (ví dụ từ trang checkout) -> Giữ nguyên
                return redirect()->to($redirectUrl)->with('success', 'Đăng nhập thành công!');
            }

            // Các trường hợp ưu tiên thấp hơn (giữ nguyên code cũ của bạn)
            if (session()->has('url.intended')) {
                return redirect()->intended(); 
            }

           
            
            return redirect()->route('home')->with('success', 'Chào mừng bạn quay trở lại!');
        }

        // 4. Nếu mật khẩu sai
        return back()->withErrors([
            'mat_khau' => 'Mật khẩu không chính xác.'
        ])->withInput($request->only('so_dien_thoai')); 
    }

    // ====================================================
    // 4. CHỨC NĂNG QUÊN MẬT KHẨU
    // ====================================================
    
    // --- HIỆN FORM NHẬP SĐT ---
    public function showForgotForm() {
        return view('auth.quenmatkhau');
    }

    // --- GỬI OTP VÀO EMAIL ---
    public function sendResetOtp(Request $request) {
        // 1. KIỂM TRA ĐỊNH DẠNG SĐT TRƯỚC
        $request->validate([
            'so_dien_thoai' => ['required', 'regex:/^(0)[0-9]{9}$/']
        ], [
            'so_dien_thoai.required' => 'Vui lòng nhập số điện thoại.',
            'so_dien_thoai.regex' => 'Số điện thoại không hợp lệ (Phải có 10 số và bắt đầu bằng số 0).'
        ]);

        $user = NguoiDung::where('so_dien_thoai', $request->so_dien_thoai)->first();
        if (!$user) {
            return back()->withErrors(['so_dien_thoai' => 'Số điện thoại này chưa được đăng ký.']);
        }

        $otp = rand(1000, 9999);
        $user->token_quen_mat_khau = $otp;
        $user->save();

        session([
            'reset_user_id' => $user->id,
            'phone' => $user->so_dien_thoai,
            'reset_otp_expire' => Carbon::now()->addSeconds(60),
        ]);

        try {
            $this->sendEmailOTP($user->email, $otp);
        } catch (\Exception $e) {
             Log::error("Gửi mail lỗi: " . $e->getMessage());
        }

        return redirect()->route('otp.reset.view')->with('success', 'Mã xác thực đã được gửi về Email của bạn.');
    }

    // --- HIỆN FORM NHẬP OTP ---
    public function showResetOtpForm() {
        if (!session('reset_user_id')) {
            return redirect()->route('password.forgot');
        }
        return view('auth.otp-reset');
    }

    // --- XÁC THỰC OTP VÀ CHUYỂN SANG ĐỔI MẬT KHẨU ---
    public function verifyResetOtp(Request $request) {
        $userId = session('reset_user_id');
        $expire = session('reset_otp_expire'); 

        if ($expire && Carbon::now()->gt($expire)) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Mã đã hết hạn (quá 60s), vui lòng lấy mã mới!'
            ]);
        }

        $user = NguoiDung::find($userId);

        if (!$user || $user->token_quen_mat_khau != $request->otp) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Mã OTP không chính xác hoặc đã hết hạn!'
            ]);
        }

        session(['otp_verified' => true]);

        return response()->json([
            'status' => 'success', 
            'redirect' => route('password.change.view')
        ]);
    }

    // --- HIỆN FORM ĐỔI MẬT KHẨU MỚI ---
    public function showChangePasswordForm() {
        if (!session('reset_user_id') || !session('otp_verified')) {
            return redirect()->route('password.forgot')->withErrors(['msg' => 'Vui lòng thực hiện lại từ đầu.']);
        }
        return view('auth.doimatkhau');
    }

    // --- XỬ LÝ CẬP NHẬT MẬT KHẨU VÀO DB ---
    public function updatePassword(Request $request) {
       // 1. Validate
        $request->validate([
            'mat_khau_moi' => [
                'required',
                'string',
                'min:7',            // Hơn 6 ký tự
                'regex:/[a-zA-Z]/', // Phải có chữ
                'regex:/[0-9]/',    // Phải có số
            ],
            'nhap_lai_mat_khau' => 'required|same:mat_khau_moi'
        ], [
            'mat_khau_moi.required' => 'Vui lòng nhập mật khẩu mới.',
            'mat_khau_moi.min' => 'Mật khẩu phải có hơn 6 ký tự (tối thiểu 7 ký tự).',
            'mat_khau_moi.regex' => 'Mật khẩu phải bao gồm cả chữ cái và số.',
            'nhap_lai_mat_khau.same' => 'Mật khẩu nhập lại không khớp.',
            'nhap_lai_mat_khau.required' => 'Vui lòng xác nhận lại mật khẩu.'
        ]);

        $userId = session('reset_user_id');
        
        if (!$userId) return redirect()->route('login')->withErrors(['msg' => 'Phiên làm việc hết hạn.']);

        $user = NguoiDung::find($userId);
        if (!$user) return redirect()->route('login');

        // 2. Cập nhật
        $user->mat_khau = Hash::make($request->mat_khau_moi);
        $user->token_quen_mat_khau = null;
        $user->save();

        session()->forget(['reset_user_id', 'phone', 'otp_verified', 'reset_otp_expire']);

        return redirect()->route('login')->with('success', 'Đổi mật khẩu thành công! Mời bạn đăng nhập với mật khẩu mới.');
    }

    // --- API CHECK SĐT ---
    public function checkPhoneAvailability(Request $request) {
        $exists = NguoiDung::where('so_dien_thoai', $request->phone)->exists();
        return response()->json(['exists' => $exists]);
    }

    // --- BỔ SUNG: GỬI LẠI OTP CHO QUÊN MẬT KHẨU ---
    public function resendResetOtp(Request $request) {
        $userId = session('reset_user_id');
        
        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'Phiên giao dịch hết hạn.']);
        }

        $ip = $request->ip();
        $key = 'resend_reset_otp_' . $ip . '_' . $userId;
        if (RateLimiter::tooManyAttempts($key, 1)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json(['status' => 'error', 'message' => "Vui lòng đợi $seconds giây."]);
        }

        $otp = rand(1000, 9999);
        $user = NguoiDung::find($userId);
        $user->token_quen_mat_khau = $otp;
        $user->save();

        session(['reset_otp_expire' => Carbon::now()->addSeconds(60)]);

        try {
            $this->sendEmailOTP($user->email, $otp);
        } catch (\Exception $e) {
            Log::error("Lỗi gửi mail: " . $e->getMessage());
        }

        RateLimiter::hit($key, 60);

        return response()->json([
            'status' => 'success', 
            'message' => 'Đã gửi lại mã mới!',
            'test_otp' => $otp 
        ]);
    }

    // --- CHỨC NĂNG ĐĂNG XUẤT ---
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home'); // Quay về trang chủ
    }

    // --- HIỂN THỊ TRANG HỒ SƠ ---
    public function showProfile() {
        return view('account.profile');
    }

    // --- GỬI EMAIL NÂNG CẤP ---
    public function sendUpgradeEmail(Request $request) {
        try {
            $data = $request->all();
            
            if (Auth::check()) {
                $userEmail = Auth::user()->email;

                Mail::send('emails.upgrade_notification', ['data' => $data], function($message) use ($userEmail) {
                    $message->to($userEmail)->subject('Xác nhận yêu cầu nâng cấp cấu hình - LaptopTF');
                });

                return response()->json(['success' => true, 'message' => 'Gửi mail thành công']);
            }
            
            return response()->json(['success' => false, 'message' => 'Chưa đăng nhập'], 401);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
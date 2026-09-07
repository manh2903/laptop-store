<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    // 1. Hiển thị Form Login
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    // 2. Xử lý Đăng nhập
    public function login(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Chuẩn bị thông tin đăng nhập
        // Lưu ý: Key 'password' là bắt buộc của Laravel để nó nhận diện mật khẩu nhập vào
        // Model User sẽ tự ánh xạ nó sang cột 'mat_khau' nhờ hàm getAuthPassword() ta vừa viết
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'id_vai_tro' => 1, // Bắt buộc phải là Admin
            'trang_thai' => 1  // Bắt buộc tài khoản đang hoạt động
        ];

        // Thử đăng nhập
        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công!');
        }

        // Nếu thất bại
        return back()->withErrors([
            'email' => 'Email/Mật khẩu sai hoặc tài khoản không có quyền Admin.',
        ]);
    }

    // 3. Xử lý Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    // 4. Form Đăng ký (Để tạo nick test)
    public function showRegisterForm()
    {
        return view('admin.auth.register');
    }

    // 5. Xử lý Đăng ký
   // 5. Xử lý Đăng ký
    public function register(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'fullname' => 'required|string|max:255',
            // Quan trọng: unique:nguoi_dung (kiểm tra trùng trong bảng nguoi_dung)
            'email' => 'required|email|unique:nguoi_dung,email', 
            'password' => 'required|confirmed|min:6', // confirmed: yêu cầu phải khớp với ô nhập lại mật khẩu
            'phone' => 'required|string|max:15',
        ], [
            // Thông báo lỗi tiếng Việt (tùy chọn)
            'email.unique' => 'Email này đã được sử dụng.',
            'password.confirmed' => 'Mật khẩu nhập lại không khớp.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.'
        ]);

        // Tạo User mới (Mapping đúng cột trong DB của bạn)
        User::create([
            'ho_ten' => $request->fullname,        // Map fullname -> ho_ten
            'email' => $request->email,
            'mat_khau' => Hash::make($request->password), // Map password -> mat_khau
            'so_dien_thoai' => $request->phone,    // Map phone -> so_dien_thoai
            'id_vai_tro' => 1,                     // 1: Cấp quyền Admin luôn
            'trang_thai' => 1,                     // 1: Kích hoạt luôn
        ]);

        return redirect()->route('admin.login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }
}

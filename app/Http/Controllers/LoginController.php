<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{

   public function showLoginForm()
    {
        return view('auth.loginform');
    }

    public function login(Request $request)
    {
        $rules = array(
            'email'    => 'required|email|exists:users', // make sure the email is an actual email
            'password' => 'required' // password can only be alphanumeric and has to be greater than 3 characters
        );

        $messages = [
            'email.required' => 'Bạn phải nhập địa chỉ email.',
            'email.email' => 'Email sai định dạng.',
            'email.exists' => 'Email không tồn tại trên hệ thống.',
            'password.required' => 'Bạn phải nhập mật khẩu.'
        ];
        $request->validate($rules,$messages);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            $request->session()->regenerate();
            if("Kích hoạt" == auth()->user()->status){
                if (auth()->user()->type == 'Admin') {
                    Session::flash('success_message', 'Đăng nhập thành công!');
                    return redirect()->route('admin.policies');
                }else{
                    Session::flash('success_message', 'Đăng nhập thành công!');
                    return redirect()->intended('/');
                }
            }else{
                Auth::logout();
                Session::flash('error_message', 'Tài khoản đã bị khóa!');
                return redirect()->route('login');
            }
        }

        return back()->withErrors([
            'email' => 'Email hoặc password không đúng!',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        Session::flash('success_message', 'Đăng xuất thành công!');
        return redirect('/');
    }
}

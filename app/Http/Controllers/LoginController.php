<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\UserForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

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

    public function showForgotPasswordForm()
    {
        return view('auth.forgot_password');
    }

    public function submitForgotPasswordForm(Request $request)
    {
        $rules = [
            'email' => 'required|email|exists:users',
        ];
        $messages = [
            'email.required' => 'Bạn phải nhập địa chỉ email.',
            'email.email' => 'Email sai định dạng.',
            'email.exists' => 'Email không tồn tại trên hệ thống.',
        ];
        $request->validate($rules,$messages);

        $token = Str::random(64);
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
          ]);

        Notification::route('mail' , $request->email)->notify(new UserForgotPassword($request->email, $token));

        return back()->with('flash_message_success', 'Chúng tôi vừa gửi đường link cấp lại mật khẩu tới email của bạn!');
    }

    public function showResetPasswordForm($token)
    {
        return view('auth.recover_password', ['token' => $token]);
    }

    public function submitResetPasswordForm(Request $request)
    {
        $rules = [
            'email' => 'required|email|exists:users',
            'password' => 'required|confirmed|min:6',
        ];
        $messages = [
            'email.required' => 'Bạn phải nhập địa chỉ email.',
            'email.email' => 'Email sai định dạng.',
            'email.exists' => 'Email không tồn tại',
            'password.required' => 'Bạn phải nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải dài ít nhất 6 ký tự.',
            'password.confirmed' => 'Mật khẩu không khớp.',
        ];
        $request->validate($rules,$messages);

        $updatePassword = DB::table('password_reset_tokens')
                            ->where([
                            'email' => $request->email,
                            'token' => $request->token
                            ])
                            ->first();

        if(!$updatePassword){
            return back()->withInput()->with('flash_message_error', 'Token không hợp lệ!');
        }

        User::where('email', $request->email)
                    ->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where(['email'=> $request->email])->delete();

        return redirect('/login')->with('flash_message_success', 'Mật khẩu được khôi phục thành công. Bạn hãy đăng nhập với mật khẩu mới!');
    }
}

<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class PasswordChange extends Component
{
    public $password = '';
    public $password_confirmation = '';

    public function changePassword()
    {
        //Validate
        $rules = [
            'password'              => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
        ];
        $messages = [
            'password.required' => 'Bạn phải nhập mật khẩu.',
            'password.confirmed' => 'Mật khẩu không khớp.',
            'password.min' => 'Bạn phải nhập mật khẩu dài ít nhất 6 ký tự.',
            'password_confirmation.required' => 'Bạn phải xác nhận mật khẩu.',
        ];

        $this->validate($rules, $messages);
        $user = User::findOrFail(Auth::user()->id);
        $user->update(['password' => $this->password]);
        $this->reset('password', 'password_confirmation');
        Session::flash('success_message', 'Cập nhật thành công!');
        return $this->redirect('/profile');
    }

    public function render()
    {
        return view('livewire.password-change', ['user' => Auth::user()])->layout('layouts.base');
    }
}

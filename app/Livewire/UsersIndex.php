<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class UsersIndex extends Component
{
    use WithPagination;

    public $search;
    public $name;
    public $email;
    public $role_id;
    public $password;
    public $password_confirmation;

    private function resetInput()
    {
        $this->search = '';
        $this->name = '';
        $this->email = '';
        $this->role_id = '';
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function mount()
    {
        $this->resetInput();
    }

    public function addNew()
    {
        $this->dispatch('show-form');
    }

    public function addUser()
    {
        $rules = [
            'name'                  => 'required',
            'email'                 => 'required|email|unique:users|max:255',
            'role_id'               => 'required',
            'password'              => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
        ];
        $messages = [
            'name.required' => 'Bạn phải nhập tên.',
            'name.max' => 'Tên dài quá 255 ký tự.',
            'email.required' => 'Bạn phải nhập email.',
            'email.email' => 'Email của bạn không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại. Bạn hãy chọn email khác.',
            'email.max' => 'Email dài quá 255 ký tự.',
            'role_id.required' => 'Bạn phải chọn vai trò.',
            'password.required' => 'Bạn phải nhập mật khẩu.',
            'password.confirmed' => 'Mật khẩu không khớp.',
            'password.min' => 'Bạn phải nhập mật khẩu dài ít nhất 6 ký tự.',
            'password_confirmation.required' => 'Bạn phải xác nhận mật khẩu.',
        ];
        $this->validate($rules,$messages);

        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = Hash::make($this->password);
        $user->role_id = $this->role_id;
        $user->save();

        $this->dispatch('hide-form');

        Session::flash('success_message', 'Người dùng mới được tạo thành công!');
    }

    public function render()
    {
        $users = User::query()
                    ->whereLike('name', $this->search)
                    ->whereLike('email', $this->search)
                    ->orWhereHas('role', function($q)
                    {
                        $q->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->paginate(10);
        $roles = Role::all();
        return view('livewire.users-index', ['users' => $users, 'roles' => $roles])->layout('layouts.base');
    }
}

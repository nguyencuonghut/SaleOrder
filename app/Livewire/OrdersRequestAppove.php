<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderRequestApprove;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class OrdersRequestAppove extends Component
{
    public $orderId;
    public $level1_manager_id;
    public $level2_manager_id;

    public function mount($id)
    {
        $this->orderId = $id;
    }

    public function requestApprove()
    {
        $order = Order::findOrFail($this->orderId);

        //Validate
        switch(Auth::user()->role->name){
            case 'Nhân viên':
            case 'Admin':
            case 'Sản Xuất':
                $rules = [
                    'level2_manager_id'  => 'required',
                ];
                $messages = [
                    'level2_manager_id.required' => 'Bạn phải chọn trưởng vùng/giám sát.',
                ];
                $toMail = User::findOrFail($this->level2_manager_id)->email;
                $order->level2_manager_id = $this->level2_manager_id;
                break;
            case 'TV/GS':
                $rules = [
                    'level1_manager_id'  => 'required',
                ];
                $messages = [
                    'level1_manager_id.required' => 'Bạn phải chọn giám đốc.',
                ];
                $toMail = User::findOrFail($this->level1_manager_id)->email;
                $order->level1_manager_id = $this->level1_manager_id;
                break;
        }
        $this->validate($rules, $messages);

        //Update the order
        $order->save();

        //Notify email to manager
        Notification::route('mail' , $toMail)->notify(new OrderRequestApprove($order->id));

        $this->reset(['orderId', 'level1_manager_id', 'level2_manager_id']);
        Session::flash('success_message', 'Gửi yêu cầu duyệt thành công!');
        return $this->redirect('/orders');
    }

    public function render()
    {
        $level1_managers = User::where('role_id', 2)->where('status', 'Kích hoạt')->get();
        $level2_managers = User::where('role_id', 3)->where('status', 'Kích hoạt')->get();
        $order = Order::findOrFail($this->orderId);
        switch($order->status){
            case 'Chưa duyệt':
                break;
            case 'TV/GS đã duyệt':
                break;
            case 'Giám đốc đã duyệt':
                break;
        }
        return view('livewire.orders-request-appove',
                    [
                        'level1_managers' => $level1_managers,
                        'level2_managers' => $level2_managers
                    ])->layout('layouts.base');
    }
}

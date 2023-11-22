<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderApproved;
use App\Notifications\OrderFinalApproved;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class OrdersApprove extends Component
{
    public $orderId;
    public $level1_manager_approved_result;
    public $level2_manager_approved_result;

    public function mount($id)
    {
        $this->orderId = $id;
    }

    public function approveOrder()
    {
        if(!Auth::user()->can('create-order')){
            Session::flash('error_message', 'Bạn không có quyền duyệt đơn hàng!');
            return $this->redirect('/order');
        }

        $order = Order::findOrFail($this->orderId);
        switch(Auth::user()->role->name){
            case 'TV/GS':
                $rules = [
                    'level2_manager_approved_result'  => 'required',
                ];
                $messages = [
                    'level2_manager_approved_result.required' => 'Bạn phải chọn kết quả 2.',
                ];
                $order->level2_manager_approved_result = $this->level2_manager_approved_result;
                $toMail = $order->creator->email;
                $level = 'level_2';
                if('Đồng ý' == $this->level2_manager_approved_result){
                    $order->status = 'TV/GS đã duyệt';
                }
                break;
            case 'Giám đốc':
                $rules = [
                    'level1_manager_approved_result'  => 'required',
                ];
                $messages = [
                    'level1_manager_approved_result.required' => 'Bạn phải chọn kết quả 1.',
                ];
                $order->level1_manager_approved_result = $this->level1_manager_approved_result;
                $toMail = $order->level2_manager->email;
                $level = 'level_1';
                if('Đồng ý' == $this->level1_manager_approved_result){
                    $order->status = 'Giám đốc đã duyệt';
                }
                break;
        }
        $this->validate($rules, $messages);

        $order->save();

        //Notify email to users
        Notification::route('mail' , $toMail)->notify(new OrderApproved($order->id, $level));

        //Notify email to Admin/Sản Xuất when order is finally approved by GĐV
        if('Giám đốc đã duyệt' == $order->status){
            //1- Admin
            //5- Sản Xuất
            $users = User::where('status', 'Kích hoạt')->whereIn('role_id', [1, 5])->get();
            foreach($users as $user){
                Notification::route('mail' , $user->email)->notify(new OrderFinalApproved($order->id));
            }
        }
        $this->reset(['orderId']);
        Session::flash('success_message', 'Duyệt thành công!');
        return $this->redirect('/orders');
    }

    public function render()
    {
        $order = Order::findOrFail($this->orderId);
        return view('livewire.orders-approve',
                    [
                        'order' => $order
                    ]
                    )
                    ->layout('layouts.base');
    }
}

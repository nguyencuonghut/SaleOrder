<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class OrdersEdit extends Component
{
    public $editedOrderId;
    public $schedule_id;
    public $level1_manager_id;
    public $level2_manager_id;
    public $delivery_date;

    public function mount($id)
    {
        $order = Order::findOrFail($id);
        //Check condition before rendering page
        if(Auth::user()->id != $order->creator_id){
            Session::flash('error_message', 'Bạn không có quyền sửa đơn đặt hàng này!');
            return $this->redirect('/orders');
        }

        $this->editedOrderId = $order->id;
        $this->schedule_id = $order->schedule_id;
        $this->level1_manager_id = $order->level1_manager_id;
        $this->level2_manager_id = $order->level2_manager_id;
        $this->delivery_date = $order->delivery_date;
    }

    public function saveOrder()
    {
        //Validate
        $rules = [
            'schedule_id'  => 'required',
        ];
        $messages = [
            'schedule_id.required' => 'Bạn phải chọn kỳ đặt hàng.',
        ];
        $this->validate($rules, $messages);

        $order = Order::findOrFail($this->editedOrderId);
        switch(Auth::user()->role->name){
            case 'Nhân viên':
            case 'Admin':
            case 'Sản Xuất':
                $order->status = 'Chưa duyệt';
                break;
            case 'TV/GS':
                $order->status = 'TV/GS đã duyệt';
                $order->level2_manager_id = Auth::user()->id;
                $order->level2_manager_approved_result = 'Đồng ý';
                break;
            case 'Giám đốc':
                $order->status = 'Giám đốc đã duyệt';
                $order->level1_manager_id = Auth::user()->id;
                $order->level1_manager_approved_result = 'Đồng ý';
                break;
        }
        $order->schedule_id = $this->schedule_id;
        if($this->delivery_date){
            $order->delivery_date = Carbon::createFromFormat('d/m/Y', $this->delivery_date);
        }else{
            $order->delivery_date = null;
        }
        $order->save();

        $this->reset(['schedule_id', 'level1_manager_id', 'level2_manager_id', 'delivery_date']);
        Session::flash('success_message', 'Sửa thành công!');
        return $this->redirect('/orders', navigate: true);
    }

    public function render()
    {
        $schedules = Schedule::whereDate('end_time', '>=', Carbon::now())->get();
        $level1_managers = User::where('role_id', 2)->get();
        $level2_managers = User::where('role_id', 3)->get();
        $order = Order::findOrFail($this->editedOrderId);
        return view('livewire.orders-edit',
                    [
                        'schedules' => $schedules,
                        'level1_managers' => $level1_managers,
                        'level2_managers' => $level2_managers,
                        'order' => $order
                    ])
                    ->layout('layouts.base');
    }
}

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
        $this->editedOrderId = $order->id;
        $this->schedule_id = $order->schedule_id;
        $this->level1_manager_id = $order->level1_manager_id;
        $this->level2_manager_id = $order->level2_manager_id;
        $this->delivery_date = $order->delivery_date;
    }

    public function saveOrder()
    {
        //Validate
        switch(Auth::user()->role->name){
            case 'Nhân viên':
            case 'Admin':
            case 'Sản Xuất':
                $rules = [
                    'schedule_id'  => 'required',
                    'level1_manager_id'  => 'required',
                    'level2_manager_id'  => 'required',
                ];
                $messages = [
                    'schedule_id.required' => 'Bạn phải chọn kỳ đặt hàng.',
                    'level1_manager_id.required' => 'Bạn phải chọn giám đốc.',
                    'level2_manager_id.required' => 'Bạn phải chọn trưởng vùng/giám sát.',
                ];
                break;
            case 'TV/GS':
                $rules = [
                    'schedule_id'  => 'required',
                    'level1_manager_id'  => 'required',
                ];
                $messages = [
                    'schedule_id.required' => 'Bạn phải chọn kỳ đặt hàng.',
                    'level1_manager_id.required' => 'Bạn phải chọn giám đốc.',
                ];
                break;
            case 'Giám đốc':
                $rules = [
                    'schedule_id'  => 'required',
                ];
                $messages = [
                    'schedule_id.required' => 'Bạn phải chọn kỳ đặt hàng.',
                ];
                break;
        }
        //Create new order
        $this->validate($rules, $messages);

        $order = Order::findOrFail($this->editedOrderId);
        $order->schedule_id = $this->schedule_id;
        $order->creator_id = Auth::user()->id;
        if(null != $this->level1_manager_id){
            $order->level1_manager_id = $this->level1_manager_id;
        }else{
            $order->level1_manager_id = Auth::user()->id;
        }
        if(null != $this->level2_manager_id){
            $order->level2_manager_id = $this->level2_manager_id;
        }else{
            if('TV/GS' == Auth::user()->role->name){
                $order->level2_manager_id = Auth::user()->id;
            }
        }
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

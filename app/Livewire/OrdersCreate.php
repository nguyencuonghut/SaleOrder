<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrdersProducts;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class OrdersCreate extends Component
{
    public $schedule_id;
    public $level1_manager_id;
    public $level2_manager_id;
    public $delivery_date;

    public function addOrder()
    {
        if(!Auth::user()->can('create-order')){
            Session::flash('error_message', 'Bạn không có quyền tạo đơn hàng!');
            return $this->redirect('/cart', navigate: true);
        }
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
                $status = 'Chưa duyệt';
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
                $status = 'TV/GS đã duyệt';
                break;
            case 'Giám đốc':
                $rules = [
                    'schedule_id'  => 'required',
                ];
                $messages = [
                    'schedule_id.required' => 'Bạn phải chọn kỳ đặt hàng.',
                ];
                $status = 'Giám đốc đã duyệt';
                break;
        }

        //Create new order
        $this->validate($rules, $messages);
        $order = new Order();
        $order->schedule_id = $this->schedule_id;
        $order->creator_id = Auth::user()->id;
        if(null != $this->level1_manager_id){
            $order->level1_manager_id = $this->level1_manager_id;
        }else{
            $order->level1_manager_id = Auth::user()->id;
            $order->level1_manager_approved_result = 'Đồng ý';
        }
        if(null != $this->level2_manager_id){
            $order->level2_manager_id = $this->level2_manager_id;
        }else{
            if('TV/GS' == Auth::user()->role->name){
                $order->level2_manager_id = Auth::user()->id;
                $order->level2_manager_approved_result = 'Đồng ý';
            }
        }
        $order->status = $status;
        if($this->delivery_date){
            $order->delivery_date = Carbon::createFromFormat('d/m/Y', $this->delivery_date);
        }
        $order->save();

        //Create ordersproducts
        $items = Cart::getContent();
        foreach($items as $item){
            $ordersproducts = new OrdersProducts();
            $ordersproducts->order_id = $order->id;
            $ordersproducts->product_id = $item->id;
            $ordersproducts->quantity = $item->quantity;
            $ordersproducts->save();
        }

        //Clear cart
        Cart::clear();

        $this->reset(['schedule_id', 'level1_manager_id', 'level2_manager_id', 'delivery_date']);
        Session::flash('success_message', 'Tạo mới thành công!');
        return $this->redirect('/orders', navigate: true);
    }

    public function render()
    {
        $schedules = Schedule::whereDate('end_time', '>=', Carbon::now())->get();
        $level1_managers = User::where('role_id', 2)->get();
        $level2_managers = User::where('role_id', 3)->get();
        return view('livewire.orders-create',
                    [
                        'schedules' => $schedules,
                        'level1_managers' => $level1_managers,
                        'level2_managers' => $level2_managers,
                    ])
                    ->layout('layouts.base');
    }
}

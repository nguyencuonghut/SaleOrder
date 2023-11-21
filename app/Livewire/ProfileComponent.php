<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileComponent extends Component
{
    public function render()
    {
        //Get the orders by user's role
        switch(Auth::user()->role->name) {
            case 'Admin':
            case 'Nhân viên':
            case 'Sản Xuất':
                $my_level1_orders_cnt = 0;
                $my_level2_orders_cnt = 0;
                break;
            case 'Giám đốc':
                $my_level1_orders_cnt = Order::where('level1_manager_id', Auth::user()->id)->count();
                $my_level2_orders_cnt = 0;
                break;
            case 'TV/GS':
                $my_level1_orders_cnt = 0;
                $my_level2_orders_cnt = Order::where('level2_manager_id', Auth::user()->id)->count();
                break;
        }

        $my_orders_cnt = Order::where('creator_id', Auth::user()->id)->count();
        return view('livewire.profile-component',
                    [
                        'my_orders_cnt' => $my_orders_cnt,
                        'my_level1_approved_orders_cnt' => $my_level1_orders_cnt,
                        'my_level2_approved_orders_cnt' => $my_level2_orders_cnt,
                    ]
                    )->layout('layouts.base');
    }
}

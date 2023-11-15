<?php

namespace App\Livewire;

use App\Models\OrdersProducts;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DashboardComponent extends Component
{
    public $schedule_id;
    public $level1_manager_id;

    public function mount()
    {
        $schedule = Schedule::orderBy('id', 'desc')->first();
        $this->schedule_id = $schedule->id;
        $this->level1_manager_id = 'All';
    }

    public function render()
    {
        $schedules = Schedule::orderBy('id', 'desc')->get();
        $schedule_id = $this->schedule_id;
        $level1_managers = User::where('role_id', 2)->get();
        $level1_manager_id = $this->level1_manager_id;

        switch(Auth::user()->role->name){
            case 'Admin':
            case 'Sản Xuất':
                //Get all products of all orders
                if('All' == $level1_manager_id){
                    $products = OrdersProducts::where('is_deleted', false)
                    ->join('products','orders_products.product_id', '=', 'products.id')
                    ->join('orders as order', function ($join) use ($schedule_id) {
                        $join->on('orders_products.order_id', '=', 'order.id')
                            ->where('order.status','=','Giám đốc đã duyệt')
                            ->where('order.schedule_id', '=', $schedule_id);
                    })
                    ->select('products.*', 'orders_products.*', DB::raw('sum(orders_products.quantity) AS quantity'))
                    ->groupBy('products.id')
                    ->get();
                }else{
                    //Filter by level1_manager_id
                    $products = OrdersProducts::where('is_deleted', false)
                    ->join('products','orders_products.product_id', '=', 'products.id')
                    ->join('orders as order', function ($join) use ($schedule_id, $level1_manager_id) {
                        $join->on('orders_products.order_id', '=', 'order.id')
                            ->where('order.status','=','Giám đốc đã duyệt')
                            ->where('order.schedule_id', '=', $schedule_id)
                            ->where('order.level1_manager_id', '=', $level1_manager_id);
                    })
                    ->select('products.*', 'orders_products.*', DB::raw('sum(orders_products.quantity) AS quantity'))
                    ->groupBy('products.id')
                    ->get();
                }
                break;
            case 'Giám đốc':
                //Get only the products of the orders that approved as level1_manager_id
                $products = OrdersProducts::where('is_deleted', false)
                ->join('products','orders_products.product_id', '=', 'products.id')
                ->join('orders as order', function ($join) use ($schedule_id) {
                    $join->on('orders_products.order_id', '=', 'order.id')
                        ->where('order.status','=','Giám đốc đã duyệt')
                        ->where('order.schedule_id', '=', $schedule_id)
                        ->where('order.level1_manager_id', '=', Auth::user()->id);
                })
                ->select('products.*', 'orders_products.*', DB::raw('sum(orders_products.quantity) AS quantity'))
                ->groupBy('products.id')
                ->get();
                break;
            case 'TV/GS':
                //Get only the products of the orders that approved as level2_manager_id
                $products = OrdersProducts::where('is_deleted', false)
                ->join('products','orders_products.product_id', '=', 'products.id')
                ->join('orders as order', function ($join) use ($schedule_id) {
                    $join->on('orders_products.order_id', '=', 'order.id')
                        ->where('order.status','=','Giám đốc đã duyệt')
                        ->where('order.schedule_id', '=', $schedule_id)
                        ->where('order.level2_manager_id', '=', Auth::user()->id);
                })
                ->select('products.*', 'orders_products.*', DB::raw('sum(orders_products.quantity) AS quantity'))
                ->groupBy('products.id')
                ->get();
                break;
            case 'Nhân viên':
                //Get only the products of the orders that creator as creator_id
                $products = OrdersProducts::where('is_deleted', false)
                ->join('products','orders_products.product_id', '=', 'products.id')
                ->join('orders as order', function ($join) use ($schedule_id) {
                    $join->on('orders_products.order_id', '=', 'order.id')
                        ->where('order.status','=','Giám đốc đã duyệt')
                        ->where('order.schedule_id', '=', $schedule_id)
                        ->where('order.creator_id', '=', Auth::user()->id);
                })
                ->select('products.*', 'orders_products.*', DB::raw('sum(orders_products.quantity) AS quantity'))
                ->groupBy('products.id')
                ->get();
                break;
        }
        return view('livewire.dashboard-component',
                    ['products' => $products,
                     'schedules' => $schedules,
                     'level1_managers' => $level1_managers
                    ])
                    ->layout('layouts.base');
    }
}

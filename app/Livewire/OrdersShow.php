<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderEditLog;
use App\Models\OrdersProducts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class OrdersShow extends Component
{
    public $order_id;
    public $quantity;
    public $editedOrderProductId;
    public $deletedOrderProductId;
    public $tab;

    public function mount($id)
    {
        $this->order_id = $id;
        $this->quantity = 0;
        $this->editedOrderProductId = null;
        $this->deletedOrderProductId = null;
        $this->tab = 'detail';
    }

    public function confirmDestroy($id)
    {
        $this->deletedOrderProductId = $id;
    }

    public function destroy()
    {
        $orderproduct = OrdersProducts::findOrFail($this->deletedOrderProductId);

        //Create logs
        if(Auth::user()->id != $orderproduct->order->creator_id){
            $log = new OrderEditLog();
            $log->order_product_id = $orderproduct->id;
            $log->creator_id = Auth::user()->id;
            $log->new_value = null;
            $log->old_value = $orderproduct->quantity;
            $log->action = 'Xóa';
            $log->save();
        }

        //Not destroy, only change the status
        $orderproduct->update(['is_deleted'=> true]);
        Session::flash('success_message', "Đã xóa sản phẩm khỏi đơn hàng!");
    }

    public function edit($id)
    {
        $this->dispatch('show-form');
        $orderproduct = OrdersProducts::findOrFail($id);
        $this->quantity = $orderproduct->quantity;
        $this->editedOrderProductId = $id;
    }

    public function cancel()
    {
        $this->reset('editedOrderProductId', 'deletedOrderProductId', 'quantity');
        $this->resetErrorBag();
        $this->dispatch('hide-form');
    }

    public function update()
    {
        $rules = [
            'quantity' => 'required|numeric|min:5',
        ];
        $messages = [
            'quantity.required' => 'Bạn phải nhập trọng lượng (kg).',
            'quantity.numeric' => 'Trọng lượng phải là dạng số.',
            'quantity.min' => 'Trọng lượng ít nhất phải bằng 5 kg.',
        ];
        $this->validate($rules,$messages);

        $orderproduct = OrdersProducts::findOrFail($this->editedOrderProductId);
        $old_value = $orderproduct->quantity;
        $orderproduct->quantity = $this->quantity;
        $orderproduct->save();

        //Create logs
        if(Auth::user()->id != $orderproduct->order->creator_id){
            $log = new OrderEditLog();
            $log->order_product_id = $orderproduct->id;
            $log->creator_id = Auth::user()->id;
            $log->new_value = $orderproduct->quantity;
            $log->old_value = $old_value;
            $log->action = 'Sửa';
            $log->save();
        }

        $this->reset('editedOrderProductId', 'quantity');
        Session::flash('success_message', 'Cập nhật thành công');
        $this->dispatch('hide-form');
    }

    public function setTab($tabName)
    {
        $this->tab = $tabName;
    }

    public function render()
    {
        $order = Order::findOrFail($this->order_id);
        $ordersproducts = OrdersProducts::where('order_id', $this->order_id)->where('is_deleted', false)->get();
        $order_product_ids = OrdersProducts::where('order_id', $this->order_id)->pluck('id')->toArray();

        $logs = OrderEditLog::whereIn('order_product_id', $order_product_ids)->get();
        return view('livewire.orders-show',
                    ['order' => $order,
                     'ordersproducts' => $ordersproducts,
                     'logs' => $logs])
                    ->layout('layouts.base');
    }
}

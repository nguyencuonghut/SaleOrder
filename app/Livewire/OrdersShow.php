<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrdersProducts;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class OrdersShow extends Component
{
    public $order_id;
    public $quantity;
    public $editedOrderProductId;
    public $deletedOrderProductId;

    public function mount($id)
    {
        $this->order_id = $id;
        $this->quantity = 0;
        $this->editedOrderProductId = null;
        $this->deletedOrderProductId = null;
    }

    public function confirmDestroy($id)
    {
        $this->deletedOrderProductId = $id;
    }

    public function destroy()
    {
        $orderproduct = OrdersProducts::findOrFail($this->deletedOrderProductId);
        $orderproduct->destroy($this->deletedOrderProductId);
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
        $orderproduct->quantity = $this->quantity;
        $orderproduct->save();

        $this->reset('editedOrderProductId', 'quantity');
        Session::flash('success_message', 'Cập nhật thành công');
        $this->dispatch('hide-form');
    }

    public function render()
    {
        $order = Order::findOrFail($this->order_id);
        $ordersproducts = OrdersProducts::where('order_id', $this->order_id)->get();
        return view('livewire.orders-show', ['order' => $order, 'ordersproducts' => $ordersproducts])->layout('layouts.base');
    }
}

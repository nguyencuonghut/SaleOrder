<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class OrdersIndex extends Component
{
    public $search;
    public $sortField;
    public $sortAsc;
    public $editedOrderIndex;
    public $deletedOrderIndex;

    public function mount()
    {
        $this->search = '';
        $this->sortField = 'id';
        $this->sortAsc = false;
        $this->editedOrderIndex = null;
        $this->deletedOrderIndex = null;
    }


    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function cancel()
    {
        $this->reset('editedOrderIndex', 'deletedOrderIndex');
        $this->resetErrorBag();
    }

    public function confirmDestroy($id)
    {
        $this->deletedOrderIndex = $id;
    }

    public function destroy()
    {
        // Destroy order
        $order = Order::findOrFail($this->deletedOrderIndex);
        $order->destroy($this->deletedOrderIndex);

        $this->reset('deletedOrderIndex');
        Session::flash('success_message', 'Xóa thành công!');
    }

    public function render()
    {
        $orders = Order::where('id', 'like', '%'.$this->search.'%')
                            ->orWhereHas('schedule', function($q)
                            {
                                $q->where('title', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('creator', function($q)
                            {
                                $q->where('name', 'like', '%'.$this->search.'%');
                            })
                            ->orWhere('status', 'like', '%'.$this->search.'%')
                            ->orWhere('delivery_date', 'like', '%'.$this->search.'%')
                            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                            ->get();

        return view('livewire.orders-index', ['orders' => $orders])->layout('layouts.base');
    }
}

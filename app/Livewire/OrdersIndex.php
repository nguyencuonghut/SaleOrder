<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
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
        //Get the orders by user's role
        switch(Auth::user()->role->name) {
            case 'Admin':
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
                break;
            case 'Giám đốc':
                $orders = Order::where('level1_manager_id', Auth::user()->id)
                                ->where('status', 'TV/GS đã duyệt')
                                ->search($this->search)
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->get();
                break;
            case 'TV/GS':
                $orders = Order::where('level2_manager_id', Auth::user()->id)
                                ->search($this->search)
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->get();
                break;
            case 'Nhân viên':
                $orders = Order::where('creator_id', Auth::user()->id)
                                ->search($this->search)
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->get();
                break;
            case 'Sản Xuất':
                $orders = Order::where('status', 'Giám đốc đã duyệt')
                                ->search($this->search)
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->get();
                break;
        }

        return view('livewire.orders-index', ['orders' => $orders])->layout('layouts.base');
    }
}

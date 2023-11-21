<?php

namespace App\Livewire;

use Cart;
use App\Models\Group;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class HomeComponent extends Component
{
    use WithPagination;

    public $sortField;
    public $sortAsc;
    public $search;
    public $group_id;
    public $tab;
    public $selectedProductId;
    public $quantity;

    public function mount()
    {
        $this->sortField = 'id';
        $this->sortAsc = true;
        $this->search = '';
        $this->group_id = null;
        $this->tab = 'customer';
        $this->selectedProductId = null;
        $this->quantity = 0;
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

    public function setTab($tabName)
    {
        $this->tab = $tabName;
        $this->search = '';
        $this->group_id = null;
        $this->resetPage();
    }

    public function selectProduct($productId)
    {
        $this->selectedProductId = $productId;
        $this->dispatch('show-form');

    }

    public function addToCart()
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

        $product = Product::findOrFail($this->selectedProductId);
        Cart::add(
            ['id' => $product->id,
            'name' => $product->code,
            'quantity' => $this->quantity,
            'price' => 0,
            ])->associate('App\Models\Product');

        Session::flash('success_message', 'Thêm sản phẩm thành công!');
        $this->dispatch('refreshComponent')->to('count-cart');
        $this->quantity = 0;
        $this->selectedProductId = null;
        $this->dispatch('hide-form');
    }

    public function cancel()
    {
        $this->selectedProductId = null;
        $this->dispatch('hide-form');
        $this->resetErrorBag();
    }

    public function render()
    {
        if(null == $this->group_id
            || "-- Tất cả SP --" == $this->group_id){
            $customer_products = Product::where('category_id', 3)
                                ->where('status', 'Kích hoạt')
                                ->search(trim($this->search))
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->paginate(10);
        }else{
            $customer_products = Product::where('category_id', 3)
                                ->where('group_id', $this->group_id)
                                ->where('status', 'Kích hoạt')
                                ->search(trim($this->search))
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->paginate(10);
        }

        if(null == $this->group_id
            || "-- Tất cả SP --" == $this->group_id){
            $farm_products = Product::where('category_id', 1)
                                ->where('status', 'Kích hoạt')
                                ->search(trim($this->search))
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->paginate(10);
        }else{
            $farm_products = Product::where('category_id', 1)
                                ->where('status', 'Kích hoạt')
                                ->where('group_id', $this->group_id)
                                ->search(trim($this->search))
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->paginate(10);
        }

        if(null == $this->group_id
            || "-- Tất cả SP --" == $this->group_id){
            $silo_products = Product::where('category_id', 4)
                                ->where('status', 'Kích hoạt')
                                ->search(trim($this->search))
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->paginate(10);
        }else{
            $silo_products = Product::where('category_id', 4)
                                ->where('status', 'Kích hoạt')
                                ->where('group_id', $this->group_id)
                                ->search(trim($this->search))
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->paginate(10);
        }

        if(null == $this->group_id
            || "-- Tất cả SP --" == $this->group_id){
            $special_products = Product::where('category_id', 2)
                                ->where('status', 'Kích hoạt')
                                ->search(trim($this->search))
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->paginate(10);
        }else{
            $special_products = Product::where('category_id', 2)
                                ->where('status', 'Kích hoạt')
                                ->where('group_id', $this->group_id)
                                ->search(trim($this->search))
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->paginate(10);
        }
        //Calculate the group based on the tab (category_id)
        switch ($this->tab) {
            case 'customer':
                $group_ids = Product::where('category_id', 3)->where('status', 'Kích hoạt')->pluck('group_id')->toArray();
                $groups = Group::whereIn('id', $group_ids)->get();
                break;
            case 'farm':
                $group_ids = Product::where('category_id', 1)->where('status', 'Kích hoạt')->pluck('group_id')->toArray();
                $groups = Group::whereIn('id', $group_ids)->get();
                break;
            case 'special':
                $group_ids = Product::where('category_id', 2)->where('status', 'Kích hoạt')->pluck('group_id')->toArray();
                $groups = Group::whereIn('id', $group_ids)->get();
                break;
            case 'silo':
                $group_ids = Product::where('category_id', 4)->where('status', 'Kích hoạt')->pluck('group_id')->toArray();
                $groups = Group::whereIn('id', $group_ids)->get();
                break;
        }
        return view('livewire.home-component',
                    [
                        'customer_products' => $customer_products,
                        'farm_products' => $farm_products,
                        'special_products' => $special_products,
                        'silo_products' => $silo_products,
                        'groups' => $groups
                    ])
                    ->layout('layouts.base');
    }
}

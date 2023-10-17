<?php

namespace App\Livewire;

use App\Models\Group;
use App\Models\Product;
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

    public function mount()
    {
        $this->sortField = 'code';
        $this->sortAsc = false;
        $this->search = '';
        $this->group_id = null;
        $this->tab = 'customer';
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

    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);
        dd($product->code);
    }

    public function render()
    {
        if(null == $this->group_id
            || "-- Tất cả SP --" == $this->group_id){
            $customer_products = Product::where('category_id', 3)
                                ->search(trim($this->search))
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->paginate(10);
        }else{
            $customer_products = Product::where('category_id', 3)
                                ->where('group_id', $this->group_id)
                                ->search(trim($this->search))
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->paginate(10);
        }

        if(null == $this->group_id
            || "-- Tất cả SP --" == $this->group_id){
            $farm_products = Product::where('category_id', 1)
                                ->search(trim($this->search))
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->paginate(10);
        }else{
            $farm_products = Product::where('category_id', 1)
                                ->where('group_id', $this->group_id)
                                ->search(trim($this->search))
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->paginate(10);
        }

        if(null == $this->group_id
            || "-- Tất cả SP --" == $this->group_id){
            $silo_products = Product::where('category_id', 4)
                                ->search(trim($this->search))
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->paginate(10);
        }else{
            $silo_products = Product::where('category_id', 4)
                                ->where('group_id', $this->group_id)
                                ->search(trim($this->search))
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->paginate(10);
        }

        if(null == $this->group_id
            || "-- Tất cả SP --" == $this->group_id){
            $special_products = Product::where('category_id', 2)
                                ->search(trim($this->search))
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->paginate(10);
        }else{
            $special_products = Product::where('category_id', 2)
                                ->where('group_id', $this->group_id)
                                ->search(trim($this->search))
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->paginate(10);
        }
        //Calculate the group based on the tab (category_id)
        switch ($this->tab) {
            case 'customer':
                $group_ids = Product::where('category_id', 3)->pluck('group_id')->toArray();
                $groups = Group::whereIn('id', $group_ids)->get();
                break;
            case 'farm':
                $group_ids = Product::where('category_id', 1)->pluck('group_id')->toArray();
                $groups = Group::whereIn('id', $group_ids)->get();
                break;
            case 'special':
                $group_ids = Product::where('category_id', 2)->pluck('group_id')->toArray();
                $groups = Group::whereIn('id', $group_ids)->get();
                break;
            case 'silo':
                $group_ids = Product::where('category_id', 4)->pluck('group_id')->toArray();
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

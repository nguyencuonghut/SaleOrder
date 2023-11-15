<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Group;
use App\Models\Package;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ProductsCreate extends Component
{
    public $code;
    public $name;
    public $detail;
    public $description;
    public $package_id;
    public $group_id;
    public $category_id;

    public function mount()
    {
        $this->code = null;
        $this->name = null;
        $this->detail = null;
        $this->description = null;
        $this->package_id = null;
        $this->group_id = null;
        $this->category_id = null;
    }

    public function addProduct()
    {
        //Validate
        $rules = [
            'code'  => 'required',
        ];
        $messages = [
            'code.required' => 'Bạn phải nhập mã SP.',
        ];

        $this->validate($rules, $messages);
        $product = new Product();
        $product->code = $this->code;
        $product->name = $this->name;
        $product->detail = $this->detail;
        $product->description = $this->description;
        $product->package_id = $this->package_id;
        $product->group_id = $this->group_id;
        $product->category_id = $this->category_id;
        $product->save();

        $this->reset(['code', 'name', 'detail', 'description', 'package_id', 'group_id', 'category_id']);
        Session::flash('success_message', 'Tạo mới thành công!');
        return $this->redirect('/products');

    }

    public function render()
    {
        $packages = Package::all();
        $groups = Group::all();
        $categories = Category::all();
        return view('livewire.products-create',
                    [
                        'packages' => $packages,
                        'groups' => $groups,
                        'categories' => $categories
                    ])
                    ->layout('layouts.base');
    }
}

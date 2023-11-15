<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Group;
use App\Models\Package;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsIndex extends Component
{
    use WithPagination;

    public $search;
    public $sortField;
    public $sortAsc;
    public $editProductIndex;
    public $deletedProductIndex;
    public $code;
    public $detail;
    public $editPackageId;
    public $editGroupId;
    public $editCategoryId;
    public $editProductStatus;

    public function mount()
    {
        $this->search = '';
        $this->sortField = 'id';
        $this->sortAsc = false;
        $this->editProductIndex = null;
        $this->deletedProductIndex = null;
        $this->code = '';
        $this->detail = '';
        $this->editPackageId = null;
        $this->editGroupId = null;
        $this->editGroupId = null;
        $this->editProductStatus = null;
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

    public function edit($editProductId)
    {
        $product = Product::findOrFail($editProductId);
        $this->editProductIndex = $product->id;
        $this->code = $product->code;
        $this->editPackageId = $product->package_id;
        $this->editGroupId = $product->group_id;
        $this->editCategoryId = $product->category_id;
        $this->detail = $product->detail;
        $this->editProductStatus = $product->status;

    }

    public function save()
    {
        //Validate
        $rules = [
            'code'  => 'required'
        ];
        $messages = [
            'code.required' => 'Bạn phải nhập mã SP.',
        ];

        $this->validate($rules, $messages);
        $product = Product::findOrFail($this->editProductIndex);
        $product->code = $this->code;
        $product->detail = $this->detail;
        $product->package_id = $this->editPackageId;
        $product->group_id = $this->editGroupId;
        $product->category_id = $this->editCategoryId;
        $product->status = $this->editProductStatus;
        $product->save();

        $this->reset('editProductIndex', 'code', 'detail', 'editPackageId', 'editGroupId', 'editCategoryId', 'editProductStatus');
        Session::flash('success_message', 'Cập nhật thành công!');
    }


    public function cancel()
    {
        $this->reset('editProductIndex', 'code', 'detail', 'editPackageId', 'editGroupId', 'editCategoryId', 'deletedProductIndex', 'editProductStatus');
        $this->resetErrorBag();
    }

    public function confirmDestroy($id)
    {
        $this->deletedProductIndex = $id;
    }

    public function destroy()
    {
        // Destroy product
        $product = Product::findOrFail($this->deletedProductIndex);
        $product->destroy($this->deletedProductIndex);

        $this->reset('deletedProductIndex');
        Session::flash('success_message', 'Xóa thành công!');
    }

    public function render()
    {
        $products = Product::query()
            ->whereLike('code', $this->search)
            ->whereLike('detail', $this->search)
            ->whereLike('status', $this->search)
            ->orWhereHas('package', function($q)
            {
                $q->where('name', 'like', '%'.$this->search.'%');
            })
            ->orWhereHas('group', function($q)
            {
                $q->where('name', 'like', '%'.$this->search.'%');
            })
            ->orWhereHas('category', function($q)
            {
                $q->where('name', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate(10);
        $packages = Package::all();
        $groups = Group::all();
        $categories = Category::all();
        return view('livewire.products-index',
                    [
                        'products' => $products,
                        'packages' => $packages,
                        'groups' => $groups,
                        'categories' => $categories
                    ])
                    ->layout('layouts.base');
    }
}

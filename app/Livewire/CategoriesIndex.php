<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CategoriesIndex extends Component
{
    public $search;
    public $sortField;
    public $sortAsc;
    public $editCategoryIndex;
    public $deletedCategoryIndex;
    public $name;
    public $description;

    public function mount()
    {
        $this->search = '';
        $this->sortField = 'id';
        $this->sortAsc = false;
        $this->editCategoryIndex = null;
        $this->deletedCategoryIndex = null;
        $this->name = '';
        $this->description = '';
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

    public function edit($editCategoryId)
    {
        $category = Category::findOrFail($editCategoryId);
        $this->editCategoryIndex = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;

    }

    public function save()
    {
        //Validate
        $rules = [
            'name'  => [
                'required',
                Rule::unique('categories')->ignore($this->editCategoryIndex),
                ]
        ];
        $messages = [
            'name.required' => 'Bạn phải nhập tên.',
            'name.unique' => 'Tên đã tồn tại. Bạn vui lòng chọn tên khác.',
        ];

        $this->validate($rules, $messages);
        $category = Category::findOrFail($this->editCategoryIndex);
        $category->name = $this->name;
        $category->description = $this->description;
        $category->save();

        $this->reset('editCategoryIndex', 'name', 'description');
        Session::flash('success_message', 'Cập nhật thành công!');
    }


    public function cancel()
    {
        $this->reset('editCategoryIndex', 'name', 'description', 'deletedCategoryIndex');
        $this->resetErrorBag();
    }

    public function confirmDestroy($id)
    {
        $this->deletedCategoryIndex = $id;
    }

    public function destroy()
    {
        // Destroy category
        $category = Category::findOrFail($this->deletedCategoryIndex);
        $category->destroy($this->deletedCategoryIndex);

        $this->reset('deletedCategoryIndex');
        Session::flash('success_message', 'Xóa thành công!');
    }

    public function render()
    {
        $categories = Category::where('id', 'like', '%'.$this->search.'%')
                                ->orWhere('name', 'like', '%'.$this->search.'%')
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->get();
        return view('livewire.categories-index', ['categories' => $categories])->layout('layouts.base');
    }
}

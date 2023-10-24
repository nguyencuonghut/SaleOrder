<?php

namespace App\Livewire;

use Cart;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class CartDetail extends Component
{
    public $quantity;
    public $editedRowId;
    public $destroyedRowId;

    public function mount()
    {
        $this->quantity = 0;
        $this->editedRowId = null;
        $this->destroyedRowId = null;
    }

    public function destroy()
    {
        Cart::remove($this->destroyedRowId);
        $this->destroyedRowId = null;
        $this->dispatch('refreshComponent')->to('count-cart');
        Session::flash('success_message', "Đã xóa sản phẩm khỏi giỏ hàng!");
    }

    public function destroyAll()
    {
        Cart::clear();
        $this->dispatch('refreshComponent')->to('count-cart');
        Session::flash('success_message', "Đã xóa tất cả sản phẩm khỏi giỏ hàng!");
        return redirect()->route('home');
    }

    public function edit($rowId)
    {
        $this->dispatch('show-form');
        $cart = Cart::get($rowId);
        $this->quantity = $cart->quantity;
        $this->editedRowId = $cart->id;
    }

    public function confirmDestroy($rowId)
    {
        $this->dispatch('show-form');
        $cart = Cart::get($rowId);
        $this->quantity = $cart->quantity;
        $this->destroyedRowId = $cart->id;
    }

    public function cancel()
    {
        $this->quantity = 0;
        $this->editedRowId = null;
        $this->destroyedRowId = null;
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

        Cart::update($this->editedRowId, [
            'quantity' => [
                'relative' => false,
                'value' => $this->quantity
        ]]);

        $this->quantity = 0;
        $this->editedRowId = null;
        $this->destroyedRowId = null;

        Session::flash('success_message', 'Cập nhật thành công');
        $this->dispatch('refreshComponent')->to('count-cart');
        $this->dispatch('hide-form');
    }

    public function render()
    {
        return view('livewire.cart-detail')->layout('layouts.base');
    }
}

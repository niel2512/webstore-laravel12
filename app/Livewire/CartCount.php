<?php

namespace App\Livewire;

use App\Contract\CartServiceInterface;
use Livewire\Attributes\On;
use Livewire\Component;

class CartCount extends Component
{
    public int $count;

    public function mount(CartServiceInterface $cart)
    {
        $this->count = $cart->all()->total_quantity;
    }

    #[On('cart-update')]
    public function updateCount(CartServiceInterface $cart)
    {
        $this->count = $cart->all()->total_quantity;
    }

    public function render()
    {
        return view('livewire.cart-count');
    }
}

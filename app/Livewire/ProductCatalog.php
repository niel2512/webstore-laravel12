<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class ProductCatalog extends Component
{
    public function render()
    {
        $products = Product::paginate(9);
        return view('livewire.product-catalog', compact('products'));
    }
}

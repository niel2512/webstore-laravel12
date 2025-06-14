<?php
declare(strict_types=1); //mengaktifkan declare static types

namespace App\Livewire;

use App\Data\ProductData;
use App\Models\Product;
use Livewire\Component;

class ProductCatalog extends Component
{
    public function render()
    {
        $query = Product::paginate(9); //ORM hanya bertugas konek ke db
        // $product = Product::first();
        // dd($product);

        // Contoh DTO yang connect ke database
        // dd(new ProductData(
        //     'Product Name',
        //     'SKU',
        //     'slug',
        //     'desc',
        //     10,
        //     10.50,
        //     100
        // ));

        // DTO yang hanya mempassing data tanpa konek ke database
        $products = ProductData::collect($query);
        return view('livewire.product-catalog', compact('products'));
    }
}

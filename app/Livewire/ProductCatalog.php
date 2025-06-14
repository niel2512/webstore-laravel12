<?php
declare(strict_types=1); //mengaktifkan declare static types

namespace App\Livewire;

use App\Data\ProductCollectionData;
use App\Data\ProductData;
use App\Models\Product;
use App\Models\Tag;
use Livewire\Component;

class ProductCatalog extends Component
{
    public function render()
    {
        $collection_result = Tag::query()->withType('collection')->withCount('Products')->get();
        $query = Product::paginate(3); //ORM hanya bertugas konek ke db
        
        // DTO yang hanya mempassing data tanpa konek ke database
        $products = ProductData::collect($query);
        $collections = ProductCollectionData::collect($collection_result);
        return view('livewire.product-catalog', compact('products', 'collections'));
    }
}

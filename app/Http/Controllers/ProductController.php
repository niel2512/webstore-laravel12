<?php

namespace App\Http\Controllers;

use App\Data\ProductData;
use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        $product = ProductData::fromModel($product, true);
        return view('product.show', compact('product'));
    }
}

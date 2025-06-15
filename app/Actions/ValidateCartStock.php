<?php

namespace App\Actions;

use App\Contract\CartServiceInterface;
use App\Models\Product;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsAction;

class ValidateCartStock
{
    use AsAction;

    public function __construct(public CartServiceInterface $cart)
    {
        
    }

    public function handle()
    {
        $insufficient = [];

        foreach($this->cart->all()->items as $item) {
            // $product = Product::where('sku', $item->sku)->first(); //Pakai Cara Eloquent

            /** @var ProductData $product */  
            $product = $item->product();

            if (!$product || $product->stock < $item->quantity) {
                $insufficient[] = [
                    'sku' => $product->sku,
                    'name' => $product->name ?? "Unknown",
                    'requested' => $item->quantity, //berapa jumlah produk yang diminta
                    'available' => $product?->stock ?? 0 //berapa produk yang tersedia kalo gada value nya 0
                ];
            }
        }

        if ($insufficient) {
            throw ValidationException::withMessages([
                'cart' => 'Some Product is insufficient stock',
                'details' => $insufficient
            ]);
        }
    }
}

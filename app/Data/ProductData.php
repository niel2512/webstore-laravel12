<?php
declare(strict_types=1); //mengaktifkan declare static types agar strong types

namespace App\Data;

use App\Models\Product;
use Illuminate\Support\Number;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class ProductData extends Data
{
    #[Computed]
    public string $price_formatted;

    public function __construct(
        public string $name,
        public string $category_tags,
        public string $sku,
        public string $slug,
        public string|Optional|null $description,
        public int $stock,
        public float $price,
        public int $weight,
        public string $cover_url 
    ) {
        $this->price_formatted = Number::currency($price);
        // dd($price, $price_formatted);
    }

    public static function fromModel(Product $product) : self
    {
        return new self(
            $product->name,
            //collections diambil dari SpatieTagsInput -> type('collection') di ProductResource
            $product->tags()->where('type','collection')->pluck('name')->implode(', '), 
            $product->sku,
            $product->slug,
            $product->description,
            $product->stock,
            floatval($product->price),
            $product->weight,
            //helper yang diambil dari Product model
            $product->getFirstMediaUrl('cover') //cover diambil dari SpatieMediaLibraryFileUpload::make('cover') di ProductResource
        );
    }
}

<?php
declare(strict_types=1);

namespace App\Data;

use Illuminate\Support\Number;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class CartData extends Data
{
    #[Computed]
    public float $total;

    public int $total_weight;

    public int $total_quantity;

    public string $total_formatted;

    public function __construct(
        #[DataCollectionOf(CartItemData::class)]
        public DataCollection $items
    ) {
        $items = $items->toCollection();
        $this->total = $items->sum(fn(CartItemData $item) => $item->price * $item->quantity);
        $this->total_weight = $items->sum(fn(CartItemData $item) => $item->weight ?? 0);
        $this->total_quantity = $items->sum(fn(CartItemData $item) => $item->quantity);
        $this->total_formatted = Number::currency($this->total);
    }
}

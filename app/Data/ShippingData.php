<?php
declare(strict_types=1);

namespace App\Data;

use Illuminate\Support\Number;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class ShippingData extends Data
{
    #[Computed]
    public string $label;

    #[Computed]
    public string $cost_formatted;

    #[Computed]
    public string $hash;

    public function __construct(
        public string $driver,
        public string $courier,
        public string $service,
        public string $estimated_delivery,
        public float $cost,
        public int $weight,
        public RegionData $origin,
        public RegionData $destination,
        public string|null $logo_url
    ) {
        $this->cost_formatted = Number::currency($cost);
        $courier_label = ucfirst($courier);
        $this->label = "$courier_label ($estimated_delivery)";
        $this->hash = md5("$origin->code-$destination->code-$driver-$courier-$service-$estimated_delivery-$cost");
    }
}

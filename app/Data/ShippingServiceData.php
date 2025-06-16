<?php
declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;

class ShippingServiceData extends Data
{
    public function __construct(
        public string $driver,
        public string $code,
        public string $courier,
        public string $service
    ) {}
}

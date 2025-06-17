<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class CustomerData extends Data
{
    public function __construct(
        public string $full_name,
        public string $email,
        public string $phone
    ) {}
}

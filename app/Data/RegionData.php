<?php
declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class RegionData extends Data
{
    #[Computed]
    public string $label;

    public function __construct(
        public string $code,
        public string $province, //untuk provinsi
        public string $city, //untuk kota
        public string $district, //untuk kecamatan
        public string $sub_district,
        public string $postal_code,
        public string $country = 'indonesia'
    ) {
        $this->label = "$sub_district, $district, $city, $province, $postal_code";
    }
}

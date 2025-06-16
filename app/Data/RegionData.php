<?php
declare(strict_types=1);

namespace App\Data;

use App\Models\Region;
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

    public static function fromModel(Region $region) : self
    {
        return new self(
            code: $region->code,
            province: $region->parent->parent->parent->name,
            city: $region->parent->parent->name,
            district: $region->parent->name,
            sub_district: $region->name,
            postal_code: $region->postal_code
        );
    }
}

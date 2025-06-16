<?php
declare(strict_types=1);

namespace App\Contract;

use App\Data\CartData;
use App\Data\RegionData;
use App\Data\ShippingData;
use App\Data\ShippingServiceData;
use Spatie\LaravelData\DataCollection;

interface ShippingDriverInterface {
  /** @return DataCollection<ShippingServiceData> */
  public function getServices() : DataCollection;

  public function getRate(
    RegionData $origin,
    RegionData $destination,
    CartData $cart,
    ShippingServiceData $shipping_service
  ) : ?ShippingData;
}
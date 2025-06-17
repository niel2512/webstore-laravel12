<?php
declare(strict_types=1);

namespace App\Services;

use App\Contract\ShippingDriverInterface;
use App\Data\CartData;
use App\Data\RegionData;
use App\Data\ShippingData;
use App\Data\ShippingServiceData;
use App\Drivers\Shipping\OfflineShippingDriver;
use Illuminate\Support\Facades\Cache;
use Spatie\LaravelData\DataCollection;

class ShippingMethodService
{
  protected array $drivers;

  public function __construct()
  {
    $this->drivers = [
      new OfflineShippingDriver()
    ];
  }

  public function getDriver(ShippingServiceData $service) : ShippingDriverInterface
  {
    return collect($this->drivers)
      ->first(fn(ShippingDriverInterface $shipping_driver) => $shipping_driver->driver === $service->driver);
  }

  /** @return DataCollection<ShippingServiceData> */ 
  public function getShippingServices() : DataCollection
  {
    return collect($this->drivers)
      ->flatMap(fn(ShippingDriverInterface $driver) => $driver->getServices()->toCollection())
      ->pipe(fn($items) => ShippingServiceData::collect($items, DataCollection::class));
  }

  /** @return DataCollection<ShippingData> */ 
  public function getShippingMethods(
    RegionData $origin,
    RegionData $destination,
    CartData $cart
  ) : DataCollection
  {
    return $this->getShippingServices()
      ->toCollection()
      ->map(function(ShippingServiceData $shipping_service) use ($origin, $destination, $cart) {
        $shipping_data = $this->getDriver($shipping_service)
          ->getRate($origin, $destination, $cart, $shipping_service);

        if ($shipping_data == null) {
          return;
        }
        Cache::put(
          key: "shipping_data:{$shipping_data->hash}",
          value: $shipping_data,
          ttl: now()->addMinutes(15) //time to live (ttl)
        ); 

        return $shipping_data;
      })
      ->reject(fn($item) => $item === null)
      ->pipe(fn($items) => ShippingData::collect($items, DataCollection::class));
  }

  public function getShippingMethod(string $hash) : ?ShippingData
  {
    return Cache::get("shipping_data:{$hash}");
  }
}
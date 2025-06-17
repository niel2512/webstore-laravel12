<?php
declare(strict_types=1);

namespace App\Services;

use App\Contract\PaymentDriverInterface;
use App\Data\PaymentData;
use App\Data\SalesOrderData;
use App\Data\SalesPaymentData;
use App\Drivers\Payment\OfflinePaymentDriver;
use Spatie\LaravelData\DataCollection;

class PaymentMethodQueryService
{
  protected array $drivers = [];

  public function __construct()
  {
    $this->drivers = [new OfflinePaymentDriver()];
  }

  public function getDriver(PaymentData|SalesPaymentData $payment_data) : PaymentDriverInterface
  {
    return collect($this->drivers)->first(fn(PaymentDriverInterface $driver) => $driver->driver === $payment_data->driver);
  }

  public function getPaymentMethods() : DataCollection
  {
    return collect($this->drivers)
      ->flatMap(fn(PaymentDriverInterface $driver) => $driver->getMethods()->toCollection())
      ->pipe(fn($items) => PaymentData::collect($items, DataCollection::class));
  }

  public function getPaymentMethodByHash(string $hash) : ?PaymentData
  {
    return $this->getPaymentMethods()
      ->toCollection()
      ->first(fn(PaymentData $data) => $data->hash === $hash);
  }

  public function shouldShowButton(SalesOrderData $sales_order): bool
  {
    return $this->getDriver($sales_order->payment)->shouldShowPayNowButton($sales_order);
  }

  public function getRedirectUrl(SalesOrderData $sales_order): ?string
  {
    return $this->getDriver($sales_order->payment)->getRedirectUrl($sales_order);
  }
}
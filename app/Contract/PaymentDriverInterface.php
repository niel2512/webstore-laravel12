<?php
declare(strict_types=1);

namespace App\Contract;

use App\Data\SalesOrderData;
use Spatie\LaravelData\DataCollection;

interface PaymentDriverInterface
{
  /** @return DataCollection<PaymentData> */ 
  public function getMethods() : DataCollection;

  public function process(SalesOrderData $sales_order);

  public function shouldShowPayNowButton(SalesOrderData $sales_order) : bool;

  public function getRedirectUrl(SalesOrderData $sales_order) : ?string;
}
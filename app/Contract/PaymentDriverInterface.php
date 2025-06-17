<?php
declare(strict_types=1);

namespace App\Contract;

use Spatie\LaravelData\DataCollection;

interface PaymentDriverInterface
{
  /** @return DataCollection<PaymentData> */ 
  public function getMethods() : DataCollection;

  public function process($sales_order);

  public function shouldShowPayNowButton($sales_order) : bool;

  public function getRedirectUrl($sales_order) : ?string;
}
<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\SalesOrderData;
use App\Events\ShippingReceiptNumberUpdateEvent;
use App\Models\SalesOrder;

class SalesOrderService
{
  public function updateShippingReceipt(SalesOrderData $sales_order, string $number): SalesOrderData
  {
    $query = SalesOrder::query()->where('trx_id', $sales_order->trx_id)->first();
    $query->update(['shipping_receipt_number' => $number]);

    $data = SalesOrderData::fromModel($query->refresh());

    event(new ShippingReceiptNumberUpdateEvent($data));

    return $data;
  }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\SalesOrderData;
use App\Data\SalesOrderItemData;
use App\Events\ShippingReceiptNumberUpdateEvent;
use App\Models\Product;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\DB;

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

  public function updateShippingPayload(SalesOrderData $sales_order, array $payload): SalesOrderData
  {
    SalesOrder::where('trx_id', $sales_order->trx_id)->update([
      'payment_payload' => array_merge($sales_order->payment->payload, $payload)
    ]);

    return SalesOrderData::from(
      SalesOrder::where('trx_id', $sales_order->trx_id)->first()
    );
  }

  public function returnStock(SalesOrderData $sales_order): void
  {
    $sales_order->items->toCollection()->each(function (SalesOrderItemData $item) {
      DB::transaction(function () use ($item) {
        Product::lockForUpdate()->update([
          'stock' => $item->quantity
        ]);
      });
    });
  }
}

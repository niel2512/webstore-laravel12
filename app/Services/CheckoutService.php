<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\CheckoutData;
use App\Data\SalesOrderData;
use App\Data\CartItemData;
use App\Events\SalesOrderCreatedEvent;
use App\Models\Product;
use App\Models\SalesOrder;
use App\States\SalesOrder\Pending;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService
{
  public function makeAnOrder(CheckoutData $checkout_data): SalesOrderData
  {
    // return new SalesOrderData();
    $sales_order = DB::transaction(function () use ($checkout_data) {
      $date = Carbon::now()->format('Ymd');
      $random = strtoupper(Str::random(5));

      $sales_order = SalesOrder::query()->create([
        'trx_id' => "TRX-{$date}-{$random}",
        'status' => Pending::class,
        'customer_full_name' => $checkout_data->customer->full_name,
        'customer_email' => $checkout_data->customer->email,
        'customer_phone' => $checkout_data->customer->phone,
        'address_line' => $checkout_data->address_line,

        'origin_code' => $checkout_data->origin->code,
        'origin_province' => $checkout_data->origin->province,
        'origin_city' => $checkout_data->origin->city,
        'origin_district' => $checkout_data->origin->district,
        'origin_sub_district' => $checkout_data->origin->sub_district,
        'origin_postal_code' => $checkout_data->origin->postal_code,

        'destination_code' => $checkout_data->destination->code,
        'destination_province' => $checkout_data->destination->province,
        'destination_city' => $checkout_data->destination->city,
        'destination_district' => $checkout_data->destination->district,
        'destination_sub_district' => $checkout_data->destination->sub_district,
        'destination_postal_code' => $checkout_data->destination->postal_code,

        'shipping_driver' => $checkout_data->shipping->driver,
        'shipping_receipt_number' => '',
        'shipping_courier' => $checkout_data->shipping->courier,
        'shipping_service' => $checkout_data->shipping->service,
        'shipping_estimated_delivery' => $checkout_data->shipping->estimated_delivery,
        'shipping_cost' => $checkout_data->shipping->cost,
        'shipping_weight' => $checkout_data->shipping->weight,

        'payment_driver' => $checkout_data->payment->driver,
        'payment_method' => $checkout_data->payment->method,
        'payment_label' => $checkout_data->payment->label,
        'payment_payload' => $checkout_data->payment->payload,
        // 'payment_paid_at' karena nullable jd tidak perlu dimasukkin

        'sub_total' => $checkout_data->sub_total,
        'shipping_total' => $checkout_data->shipping_cost,
        'total' => $checkout_data->grand_total,
        'due_date_at' => Carbon::now()->addHour(24)
      ]);

      $items = collect([]);

      /** @var CartItemData $item */
      foreach ($checkout_data->cart->items as $item) {
        $product = Product::where('sku', $item->sku)->lockForUpdate()->firstOrFail();

        if ($product->stock < $item->quantity) {
          throw new \Exception("Stock Not Available");
        }
        $product->stock -= $item->quantity;
        $product->save();

        $items->push([
          'name' => $item->product()->name,
          'short_desc' => $item->product()->short_desc ?? '-',
          'sku' => $item->sku,
          'slug' => $item->product()->slug,
          'description' => $item->product()->description ?? '',
          'cover_url' => $item->product()->cover_url,
          'quantity' => $item->quantity,
          'price' => $item->price,
          'total' => $item->price * $item->quantity,
          'weight' => $item->weight
        ]);
      }
      $sales_order->items()->createMany($items);
      return $sales_order;
    });

    $sales_order_data = SalesOrderData::fromModel($sales_order);

    event(new SalesOrderCreatedEvent($sales_order_data));

    return $sales_order_data;
  }
}

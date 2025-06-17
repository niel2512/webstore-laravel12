<?php

namespace App\Data;

use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class SalesOrderData extends Data
{
    #[Computed]
    public string $sub_total_formatted;

    #[Computed]
    public string $shipping_total_formatted;

    #[Computed]
    public string $total_formatted;

    #[Computed]
    public string $created_at_formatted;

    #[Computed]
    public string $due_date_at_formatted;

    public function __construct(
        public string $trx_id,
        public string $status,
        public CustomerData $customer,
        public string $address_line,
        public RegionData $origin,
        public RegionData $destination,

        #[DataCollectionOf(SalesOrderItemData::class)]
        public DataCollection $items,

        public SalesShippingData $shipping,
        public SalesPaymentData $payment,

        public float $sub_total,
        public float $shipping_cost,
        public float $total,

        public Carbon $due_date_at,
        public Carbon $created_at
    ) {
        $this->sub_total_formatted = Number::currency($sub_total);
        $this->shipping_total_formatted = Number::currency($shipping_cost);
        $this->total_formatted = Number::currency($total);

        $this->created_at_formatted = $created_at->translatedFormat('d F Y, H:i');
        $this->due_date_at_formatted = $due_date_at->translatedFormat('d F Y, H:i');
    }

    public static function fromModel(SalesOrder $sales_order): self
    {
        return new self(
            trx_id: $sales_order->trx_id,
            status: $sales_order->status,
            customer: new CustomerData(
                full_name: $sales_order->customer_full_name,
                email: $sales_order->customer_email,
                phone: $sales_order->customer_phone
            ),
            address_line: $sales_order->address_line,
            origin: new RegionData(
                code: $sales_order->origin_code,
                province: $sales_order->origin_province,
                city: $sales_order->origin_city,
                district: $sales_order->origin_district,
                sub_district: $sales_order->origin_sub_district,
                postal_code: $sales_order->origin_postal_code
            ),
            destination: new RegionData(
                code: $sales_order->destination_code,
                province: $sales_order->destination_province,
                city: $sales_order->destination_city,
                district: $sales_order->destination_district,
                sub_district: $sales_order->destination_sub_district,
                postal_code: $sales_order->destination_postal_code
            ),
            items: SalesOrderItemData::collect($sales_order->items->toArray(), DataCollection::class),
            shipping: new SalesShippingData(
                driver: $sales_order->shipping_driver,
                receipt_number: $sales_order->shipping_receipt_number,
                courier: $sales_order->shipping_courier,
                service: $sales_order->shipping_service,
                estimated_delivery: $sales_order->shipping_estimated_delivery,
                cost: $sales_order->shipping_cost,
                weight: $sales_order->shipping_weight
            ),
            payment: new SalesPaymentData(
                driver: $sales_order->payment_driver,
                method: $sales_order->payment_method,
                label: $sales_order->payment_label,
                payload: $sales_order->payment_payload,
                paid_at: $sales_order->payment_paid_at
            ),
            sub_total: $sales_order->sub_total,
            shipping_cost: $sales_order->shipping_cost,
            total: $sales_order->total,
            due_date_at: Carbon::parse($sales_order->due_date_at),
            created_at: $sales_order->created_at
        );
    }
}

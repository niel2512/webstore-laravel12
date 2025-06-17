<?php

namespace App\Data;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class SalesOrderData extends Data
{
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
    ) {}
}

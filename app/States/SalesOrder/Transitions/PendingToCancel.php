<?php

declare(strict_types=1);

namespace App\States\SalesOrder\Transitions;

use App\Data\SalesOrderData;
use App\Events\SalesOrderCancelledEvent;
use App\Models\SalesOrder;
use App\Services\SalesOrderService;
use App\States\SalesOrder\Cancel;
use Spatie\ModelStates\Transition;

class PendingToCancel extends Transition
{
  public function __construct(private SalesOrder $sales_order) {}

  public function handle()
  {
    $this->sales_order->update([
      'status' => Cancel::class
    ]);

    $data = SalesOrderData::fromModel($this->sales_order);

    app(SalesOrderService::class)->returnStock($data);

    event(new SalesOrderCancelledEvent($data));

    return $this->sales_order;
  }
}

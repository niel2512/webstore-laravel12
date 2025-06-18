<?php

declare(strict_types=1);

namespace App\States\SalesOrder\Transitions;

use App\Data\SalesOrderData;
use App\Events\SalesOrderProgressedEvent;
use App\Models\SalesOrder;
use App\States\SalesOrder\Progress;
use Illuminate\Support\Carbon;
use Spatie\ModelStates\Transition;

class PendingToProgress extends Transition
{
  public function __construct(private SalesOrder $sales_order) {}

  public function handle()
  {
    $this->sales_order->update([
      'status' => Progress::class,
      'payment_paid_at' => Carbon::now()
    ]);
    event(new SalesOrderProgressedEvent(SalesOrderData::fromModel($this->sales_order)));
    return $this->sales_order;
  }
}

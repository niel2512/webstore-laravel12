<?php

declare(strict_types=1);

namespace App\States\SalesOrder\Transitions;

use App\Models\SalesOrder;
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
    return $this->sales_order;
  }
}

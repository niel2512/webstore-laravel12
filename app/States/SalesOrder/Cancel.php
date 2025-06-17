<?php
declare(strict_types=1);

namespace App\States\SalesOrder;

class Cancel extends SalesOrderState
{
  public function label(): string
  {
    return "Batal";
  }
}
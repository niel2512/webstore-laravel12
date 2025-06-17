<?php
declare(strict_types=1);

namespace App\States\SalesOrder;

class Completed extends SalesOrderState
{
  public function label(): string
  {
    return "Sukses";
  }
}
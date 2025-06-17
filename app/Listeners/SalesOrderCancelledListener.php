<?php

namespace App\Listeners;

use App\Data\SalesOrderData;
use App\Events\SalesOrderCancelledEvent;
use App\Mail\SalesOrderCancelledMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SalesOrderCancelledListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SalesOrderCancelledEvent $event): void
    {
        Mail::queue(
            new SalesOrderCancelledMail($event->sales_order)
        );
    }
}

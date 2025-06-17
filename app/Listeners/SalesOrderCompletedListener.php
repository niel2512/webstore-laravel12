<?php

namespace App\Listeners;

use App\Events\SalesOrderCompletedEvent;
use App\Mail\SalesOrderCompletedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SalesOrderCompletedListener
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
    public function handle(SalesOrderCompletedEvent $event): void
    {
        Mail::queue(
            new SalesOrderCompletedMail($event->sales_order)
        );
    }
}

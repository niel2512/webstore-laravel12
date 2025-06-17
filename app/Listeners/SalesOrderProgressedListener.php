<?php

namespace App\Listeners;

use App\Events\SalesOrderProgressedEvent;
use App\Mail\SalesOrderProgressedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SalesOrderProgressedListener
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
    public function handle(SalesOrderProgressedEvent $event): void
    {
        Mail::queue(
            new SalesOrderProgressedMail($event->sales_order)
        );
    }
}

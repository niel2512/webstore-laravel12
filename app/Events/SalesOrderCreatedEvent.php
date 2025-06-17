<?php

namespace App\Events;

use App\Data\SalesOrderData;
use App\Data\SalesOrderItemData;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SalesOrderCreatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public SalesOrderData $sales_order)
    {
        //
    }

    public function broadcastWith(): array
    {
        /** @var SalesOrderItemData $product */
        $product = $this->sales_order->items->toCollection()->random(1)->first();
        // disesuakan dengan toast pada bagian ini `${e.customer_name} Baru saja membeli ${e.product_qty} buah ${e.product}`
        return [
            'customer_name' => $this->sales_order->customer->full_name,
            'product' => $product->name,
            'product_qty' => $product->quantity
        ];
    }

    public function broadcastAs(): string
    {
        return 'orders'; //ini tuh untuk disesuaikan di toast.blade bagian ini window.Echo.channel('orders')
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('orders'), //ini disesuaikan namanya agar sama dengan toast pada bagian ini .listen('.orders'
        ];
    }
}

<?php

namespace App\Jobs;

use App\Services\SalesOrderService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

class MootaPaymentJob extends ProcessWebhookJob
{
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data = $this->webhookCall;

        collect(data_get($data, 'payload'))->each(function ($item) {
            if (!data_get($item, 'payment_detail.order_id')) {
                return;
            }
            $real_total = data_get($item, 'payment_detail.total') - data_get($item, 'payment_detail.unique_code');
            app(SalesOrderService::class)->approvePaymentUsingTrxID(
                data_get($item, 'payment_detail.order_id'),
                $real_total
            );
        });
    }
}

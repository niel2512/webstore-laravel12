<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            // Semua data ini untuk mengisi SalesOrderData
            $table->id();
            $table->string('trx_id')->unique();
            $table->string('status');

            // data yang untuk mengisi CustomerData
            $table->string('customer_full_name');
            $table->string('customer_email');
            $table->string('customer_phone');

            $table->string('address_line');

            // data untuk mengisi RegionData
            $table->string('origin_code');
            $table->string('origin_province');
            $table->string('origin_city');
            $table->string('origin_district');
            $table->string('origin_sub_district');
            $table->string('origin_postal_code');
            // $table->string('origin_country'); //sudah di scale up agar Indonesia aktifkan kalau mau ke luar Negeri juga
            
            // data untuk mengisi RegionData
            $table->string('destination_code');
            $table->string('destination_province');
            $table->string('destination_city');
            $table->string('destination_district');
            $table->string('destination_sub_district');
            $table->string('destination_postal_code');

            // data untuk mengisi SalesShippingData
            $table->string('shipping_driver');
            $table->string('shipping_receipt_number')->nullable();
            $table->string('shipping_courier');
            $table->string('shipping_service');
            $table->string('shipping_estimated_delivery');
            $table->double('shipping_cost', 11, 2);
            $table->integer('shipping_weight');

            // data untuk mengisi SalesShippingData
            $table->string('payment_driver');
            $table->string('payment_method');
            $table->string('payment_label');
            $table->text('payment_payload');
            $table->timestamp('payment_paid_at')->nullable();

            $table->double('sub_total', 11, 2);
            $table->double('shipping_total', 11, 2);
            $table->double('total', 11, 2);

            $table->timestamp('due_date_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};

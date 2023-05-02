<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->comment('ref: orders')->nullable();
            $table->string('shipment_order_id')->nullable();
            $table->string('shipment_order_number')->nullable();
            $table->string('shipment_id')->nullable();
            $table->string('channel_id')->nullable();
            $table->boolean('status')->comment('0=Inactive 1=Active')->default(1);
            $table->string('payment_mode')->nullable();  
            $table->string('status_code')->nullable();
            $table->string('complete')->nullable();
            $table->string('awb_code')->nullable();
            $table->string('courier_id')->comment('ref: couriers')->nullable();
            $table->string('location_name')->nullable();
            $table->string('cod')->nullable();
            $table->string('tracking_id')->comment('ref: trackings')->nullable();
            $table->string('shiporder_id')->comment('ref: ship_orders')->nullable();
            $table->string('shipment_staus_id')->comment('ref: shipments_status')->nullable();
            $table->string('shipping_method_id')->comment('ref: shipping_method')->nullable();
            $table->text('data')->nullable();
            $table->text('awb_data')->nullable();
            $table->text('pickup_data')->nullable();
            $table->text('cancel_data')->nullable();
            $table->text('pickup_request_data')->nullable();
            $table->text('track_data')->nullable();
            $table->string('generate_manifest_url')->nullable();
            $table->string('print_manifest_url')->nullable();
            $table->string('label_url')->nullable();
            $table->string('invoice_url')->nullable();
            $table->string('track_url')->nullable();
            $table->boolean('is_delivered')->comment('0=not delivered,1=delivered')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipments');
    }
}

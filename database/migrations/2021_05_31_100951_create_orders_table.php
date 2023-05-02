<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('financial_status_id')->comment('ref:order_financial_statuses')->nullable();
            $table->unsignedBigInteger('currency_id')->comment('ref:currencies')->nullable();
            $table->unsignedBigInteger('shipping_method_id')->comment('ref:shipping_methods')->nullable();
            $table->string('user_id')->index()->comment('ref:users')->nullable();
            $table->unsignedBigInteger('billing_address_id')->comment('ref:order locations')->nullable();
            $table->unsignedBigInteger('shipping_address_id')->comment('ref:order locations')->nullable();
            $table->unsignedBigInteger('payment_method_id')->comment('ref:payment_methods')->nullable();
            $table->datetime('paid_at')->nullable();
            $table->string('fulfillment_status');
            $table->datetime('fulfilled_at')->nullable();
            $table->boolean('accepts_marketing')->comment('0=No, 1=Yes')->default(0);
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('taxes', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->string('discount_code')->nullable();
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->string('risk_level')->comment('Low, High')->nullable();
            $table->string('source')->comment('Web, App')->nullable();
            $table->string('tax_1_name')->nullable();
            $table->decimal('tax_1_value', 15, 2)->nullable();
            $table->string('tax_2_name')->nullable();
            $table->decimal('tax_2_value', 15, 2)->nullable();
            $table->string('tax_3_name')->nullable();
            $table->decimal('tax_3_value', 15, 2)->nullable();
            $table->string('tax_4_name')->nullable();
            $table->decimal('tax_4_value', 15, 2)->nullable();
            $table->string('tax_5_name')->nullable();
            $table->decimal('tax_5_value', 15, 2)->nullable();
            $table->string('receipt_number')->nullable();
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
        Schema::dropIfExists('orders');
    }
}

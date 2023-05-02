<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_products', function (Blueprint $table) {
            $table->id();
            $table->string('shipping_id')->comment('ref: shippings')->nullable();
            $table->string('order_id')->comment('ref: orders')->nullable();
            $table->string('product_id')->comment('ref: products')->nullable();
            $table->string('product_variant_options_id')->comment('ref: product_variant_options')->nullable();
            $table->string('title')->nullable();
            $table->string('quantity')->nullable();
            $table->string('selling_price')->nullable();
            $table->string('sku')->nullable();
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
        Schema::dropIfExists('shipping_products');
    }
}

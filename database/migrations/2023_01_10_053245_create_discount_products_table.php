<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('discount_id')->comment('ref:discounts')->nullable();
            $table->string('product_id')->comment('ref:products')->nullable();
            $table->unsignedBigInteger('product_variant_options_id')->comment('ref:product_variant_options')->nullable();
            $table->decimal('initial_value',15,2)->nullable();
            $table->boolean('status')->comment('0=Disabled, 1=Enabled')->default(0);
            $table->unsignedBigInteger('currency_id')->comment('ref:currencies')->nullable();
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
        Schema::dropIfExists('discount_products');
    }
}

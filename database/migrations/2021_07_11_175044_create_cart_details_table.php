<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('cart_id')->comment('ref:cart');
            $table->char('product_id', 36)->comment('ref:products');
            $table->char('store_id', 36)->comment('ref:user_store')->nullable();
            $table->unsignedBigInteger('variant_option_id')->nullable()->comment('ref:product_variant_option');
            $table->integer('quantity')->default(0);
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
        Schema::dropIfExists('cart_details');
    }
}

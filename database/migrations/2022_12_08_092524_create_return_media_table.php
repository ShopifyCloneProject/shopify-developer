<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_media', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id')->nullable();
            $table->string('order_id')->comment('ref:orders');
            $table->string('product_id')->comment('ref:products');
            $table->string('product_variant_options_id')->comment('ref:product_variant_options');
            $table->string('src')->nullable();
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
        Schema::dropIfExists('return_media');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ship_orders', function (Blueprint $table) {
            $table->id();
            $table->string('filter')->nullable();
            $table->string('description')->nullable();
            $table->string('shipping_method_id')->comment('ref: shipping_method')->nullable();
            $table->boolean('status')->comment('0=Inactive 1=Active')->default(1);
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
        Schema::dropIfExists('ship_orders');
    }
}

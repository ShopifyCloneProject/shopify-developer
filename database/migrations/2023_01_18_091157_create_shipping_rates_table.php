<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->boolean('rate_status')->comment('0=Carrier, 1=Own')->default(1);
            $table->string('name')->nullable();
            $table->decimal('price',15,2)->nullable();
            $table->boolean('conditions')->comment('0=No, 1=Yes')->default(1);
            $table->boolean('weight_or_price')->comment('0=Weight, 1=Price')->default(0);
            $table->decimal('min',15,2)->nullable();
            $table->decimal('max',15,2)->nullable();
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
        Schema::dropIfExists('shipping_rates');
    }
}

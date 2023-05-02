<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('shipping_method_id')->comment('ref: shipping_methods');
            $table->string('pickup_id')->nullable();
            $table->string('pickup_code')->nullable();
            $table->boolean('status')->comment('0=InActive 1=Active')->default(1);
            $table->text('data')->nullable();
            $table->string('address_id')->comment('ref: addresses');
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
        Schema::dropIfExists('warehouses');
    }
}

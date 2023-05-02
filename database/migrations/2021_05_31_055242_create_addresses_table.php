<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id')->index()->comment('ref: users');
            $table->string('address', 2000)->nullable();
            $table->string('address_2', 2000)->nullable();
            $table->string('phone_code',10)->nullable();
            $table->string('mobile', 50)->nullable();
            $table->boolean('status')->comment('0=Shipping 1=Billing')->default(1);
            $table->string('postal_code', 10)->nullable();
            $table->unsignedBigInteger('country_id')->comment('ref: countries')->nullable();
            $table->unsignedBigInteger('state_id')->comment('ref: states')->nullable();
            $table->string('city_name')->nullable();
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
        Schema::dropIfExists('addresses');
    }
}

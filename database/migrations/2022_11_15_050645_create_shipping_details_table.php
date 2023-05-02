<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_details', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email', 100)->nullable()->unique();
            $table->string('password')->nullable();
            $table->string('access_token')->nullable();
            $table->string('secret_key', 1000)->nullable();
            $table->boolean('test_mode')->default(0)->comment('0=False, 1=True');
            $table->text('state_data')->nullable();
            $table->text('city_data')->nullable();
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
        Schema::dropIfExists('shipping_details');
    }
}

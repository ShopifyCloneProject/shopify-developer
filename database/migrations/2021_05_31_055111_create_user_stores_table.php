<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_stores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('address_id')->comment('ref: addresses');
            $table->unsignedBigInteger('timezone_id')->nullable()->comment('ref: time_zones');
            $table->unsignedBigInteger('user_store_industry_id')->nullable()->comment('ref: user_store_industries');
            $table->string('store_contact_email',100);
            $table->string('sender_email', 100);
            $table->string('company')->nullable();
            $table->unsignedInteger('unit_system');
            $table->unsignedInteger('unit_weight');
            $table->string('prefix', 50)->nullable();
            $table->string('suffix', 50)->nullable();
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
        Schema::dropIfExists('user_stores');
    }
}

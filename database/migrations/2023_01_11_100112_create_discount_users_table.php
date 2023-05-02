<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('discount_id')->comment('ref:discounts')->nullable();
            $table->string('user_id')->comment('ref:users')->nullable();
            $table->boolean('status')->comment('0=Disabled, 1=Enabled')->default(0);
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
        Schema::dropIfExists('discount_users');
    }
}

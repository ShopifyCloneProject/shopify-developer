<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('user_id',36);
            $table->unsignedBigInteger('payment_method_id');
            $table->text('description')->nullable();
            $table->string('app_key', 1000)->nullable();
            $table->string('app_secret', 1000)->nullable();
            $table->string('industry_type')->nullable();
            $table->string('website', 1000)->nullable();
            $table->boolean('is_testmode')->default(0)->comment('0=No, 1=Yes');
            $table->boolean('status')->default(1)->comment('0=DeActivate, 1=Activate');
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
        Schema::dropIfExists('payment_details');
    }
}

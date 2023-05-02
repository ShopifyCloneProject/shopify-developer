<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->nullable()->comment('ref: orders');
            $table->string('payment_id')->nullable()->comment('main payment id');
            $table->string('refund_id')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->double('amount',15,2)->nullable();
            $table->string('status')->nullable();
            $table->text('note')->nullable();
            $table->text('data')->comment('refund data');
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
        Schema::dropIfExists('refunds');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodCustomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_method_customs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('user_id', 36)->comment('ref:user');
            $table->string('type')->nullable();
            $table->string('name')->nullable();
            $table->text('additional_details')->nullable();
            $table->text('additional_instruction')->nullable();
            $table->boolean('status')->default(1)->comment('0=deactive,1=active');
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
        Schema::dropIfExists('payment_method_customs');
    }
}

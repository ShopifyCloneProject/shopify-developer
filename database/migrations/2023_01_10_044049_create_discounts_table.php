<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->datetime('starting_date')->nullable();
            $table->datetime('expiry_date')->nullable();
            $table->boolean('expiry_type')->comment('0=No, 1=Yes')->default(0);
            $table->unsignedBigInteger('currency_id')->comment('ref:currencies')->nullable();
            $table->boolean('percentage_or_amount')->comment('0=Amount, 1=Percentage')->default(0);
            $table->decimal('amount',15,2)->nullable();
            $table->decimal('initial_value',15,2)->nullable();
            $table->boolean('product_or_collection')->comment('0=Collection, 1=Product')->default(0);
            $table->boolean('product_status')->comment('0=Particular, 1=All')->default(0);
            $table->boolean('status')->comment('0=Disabled, 1=Enabled')->default(0);
            $table->string('user_availability')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('discounts');
    }
}

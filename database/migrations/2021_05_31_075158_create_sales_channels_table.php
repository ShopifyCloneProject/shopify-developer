<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_channels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_id')->index()->comment('ref: products');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('status')->comment('1=Active 0=Inactive')->default(1);
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
        Schema::dropIfExists('sales_channels');
    }
}

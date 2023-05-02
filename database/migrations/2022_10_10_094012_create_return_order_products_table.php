<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_order_products', function (Blueprint $table) {
            $table->id();
            $table->string('return_order_id')->comment('ref: return_orders')->nullable();
            $table->string('product_id')->comment('ref: products')->nullable();
            $table->string('product_variant_options_id')->comment('ref: product_variant_options')->nullable();
            $table->integer('quantity')->default(0);
            $table->string('description')->nullable();
            $table->tinyInteger('admin_approve')->default('0')->comment('0=NotApprove 1=Approve');
            $table->string('cancel_user_id')->comment('ref: users')->nullable();
            $table->integer('cancel_request')->default(1);
            $table->string('cancel_request_description')->nullable();
            $table->datetime('cancel_request_date')->nullable();
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
        Schema::dropIfExists('return_order_products');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_products', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->index()->comment('ref: orders')->nullable();
            $table->string('product_id')->index()->comment('ref: products');
            $table->char('user_id', 36)->nullable();
            $table->unsignedBigInteger('product_variant_options_id')->nullable()->comment('ref: product_variant_options');
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->decimal('cost_per_item', 15, 2)->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('total', 15, 2)->nullable();
            $table->string('src')->nullable();
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->unsignedBigInteger('weight_type_id')->nullable()->comment('ref: weightmanages');
            $table->float('weight', 15, 2)->default(0);
            $table->string('hs_code')->nullable();
            $table->boolean('is_product_charge')->comment('0=no,1=yes')->default(0);
            $table->boolean('is_track')->comment('0=no,1=yes')->default(0);
            $table->boolean('is_special_product')->comment('0=no,1=yes')->default(0);
            $table->decimal('special_price', 15, 2)->nullable();
            $table->longText('description')->nullable();
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
        Schema::dropIfExists('refund_products');
    }
}

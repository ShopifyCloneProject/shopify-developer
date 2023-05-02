<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('product_type_id')->nullable()->index()->comment('ref: product_types');
            $table->string('vendor_id')->nullable()->index()->comment('ref: vendors');
            $table->unsignedBigInteger('country_id')->comment('ref: countries');
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('status')->comment('0=draft,1=active')->default(0);
            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('compare_at_price', 15, 2)->nullable();
            $table->decimal('cost_per_item', 15, 2)->nullable();
            $table->boolean('is_product_charge')->comment('0=no,1=yes')->default(0);
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->boolean('is_track')->comment('0=no,1=yes')->default(0);
            $table->boolean('is_continue_selling')->comment('0=no,1=yes')->default(0);
            $table->boolean('is_physical_product')->comment('0=no,1=yes')->default(0);
            $table->unsignedBigInteger('weight_type_id')->nullable()->comment('ref: weightmanages');
            $table->float('weight', 15, 2)->default(0);
            $table->string('hs_code')->nullable();
            $table->integer('min_order_limit')->default(0);
            $table->integer('max_order_limit')->default(0);
            $table->boolean('is_cod_enabled')->comment('0=no,1=yes')->default(0);
            $table->boolean('is_size_chart_enabled')->comment('0=no,1=yes')->default(0);
            $table->boolean('is_special_product')->comment('0=no,1=yes')->default(0);
            $table->decimal('special_price', 15, 2)->nullable();
            $table->date('expiry_date')->nullable();
            $table->boolean('special_product_status')->default(0);
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 2000)->nullable();
            $table->boolean('is_gift_card')->comment('0=no,1=yes')->default(0);
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
        Schema::dropIfExists('products');
    }
}

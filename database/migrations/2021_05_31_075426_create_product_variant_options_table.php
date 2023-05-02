<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variant_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_id')->index()->comment('ref: products');
            $table->unsignedBigInteger('variant_option_1_id')->nullable()->comment('ref: variant_options');
            $table->unsignedBigInteger('variant_option_2_id')->nullable()->comment('ref: variant_options');
            $table->unsignedBigInteger('variant_option_3_id')->nullable()->comment('ref: variant_options');
            $table->string('src')->nullable();
            $table->string('src_alt_text')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('compare_at_price', 15, 2)->nullable();
            $table->decimal('cost_per_item', 15, 2)->nullable();
            $table->boolean('is_product_charge')->comment('0=No, 1=Yes')->default(0);
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->boolean('is_track')->comment('0=No, 1=Yes')->default(0);
            $table->boolean('is_continue_selling')->comment('0=No, 1=Yes')->default(0);
            $table->boolean('is_physical_product')->comment('0=No, 1=Yes')->default(0);
            $table->unsignedBigInteger('country_id')->nullable()->comment('ref: countries');
            $table->unsignedBigInteger('weight_type_id')->nullable()->comment('ref: weightmanages');
            $table->float('weight', 15, 2)->default(0);
            $table->string('hs_code')->nullable();
            $table->boolean('is_special_product')->comment('0=No, 1=Yes')->default(0);
            $table->decimal('special_price', 15, 2)->nullable();
            $table->date('expiry_date')->nullable();
            $table->boolean('special_product_status')->comment('0=No, 1=Yes')->default(0);
            $table->boolean('is_shipping')->comment('0=No, 1=Yes')->default(0);
            $table->boolean('is_taxable')->comment('0=No, 1=Yes')->default(0);
            $table->integer('reorder')->nullable();
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
        Schema::dropIfExists('product_variant_options');
    }
}

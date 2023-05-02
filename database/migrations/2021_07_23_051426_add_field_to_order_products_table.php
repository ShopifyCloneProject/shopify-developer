<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->string('title')->nullable()->after('product_variant_options_id');
            $table->string('slug')->nullable()->after('title');
            $table->decimal('price', 15, 2)->nullable()->after('slug');
            $table->string('src')->nullable()->after('price');
            $table->integer('quantity')->default(0)->after('src');
            $table->string('sku')->nullable()->after('quantity');
            $table->string('barcode')->nullable()->after('sku');
            $table->unsignedBigInteger('weight_type_id')->nullable()->comment('ref: weightmanages')->after('barcode');
            $table->float('weight', 15, 2)->default(0)->after('weight_type_id');
            $table->string('hs_code')->nullable()->after('weight');
            $table->boolean('is_product_charge')->comment('0=no,1=yes')->default(0)->after('hs_code');
            $table->boolean('is_track')->comment('0=no,1=yes')->default(0)->after('is_product_charge');
            $table->boolean('is_special_product')->comment('0=no,1=yes')->default(0)->after('is_track');
            $table->decimal('special_price', 15, 2)->nullable()->after('is_special_product');
            $table->string('email', 100)->nullable()->after('user_id');
            $table->string('mobile', 50)->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->dropColumn(['title', 'slug', 'price', 'src', 'quantity', 'sku', 'barcode', 'weight_type_id', 'weight', 'hs_code', 'is_product_charge', 'is_track', 'is_special_product', 'special_price', 'email', 'mobile']);
        });
    }
}

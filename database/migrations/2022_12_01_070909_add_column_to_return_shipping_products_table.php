<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToReturnShippingProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('return_shipping_products', function (Blueprint $table) {
            $table->string('order_id')->comment('ref: orders')->nullable()->after('return_shipping_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('return_shipping_products', function (Blueprint $table) {
            $table->dropColumn('order_id');
        });
    }
}

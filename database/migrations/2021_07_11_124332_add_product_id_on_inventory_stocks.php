<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductIdOnInventoryStocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_stocks', function (Blueprint $table) {
             $table->string('product_variant_option_id')->nullable()->change();
             $table->string('product_id')->nullable()->after('id');
             $table->unsignedBigInteger('incoming')->default(0)->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_stocks', function (Blueprint $table) {
             $table->dropColumn(['product_id','incoming']);
        });
    }
}

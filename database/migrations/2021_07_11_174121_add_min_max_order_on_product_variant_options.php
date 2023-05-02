<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMinMaxOrderOnProductVariantOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_variant_options', function (Blueprint $table) {
            $table->integer('min_order_limit')->default(0)->after('src_alt_text');
            $table->integer('max_order_limit')->default(0)->after('min_order_limit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_variant_options', function (Blueprint $table) {
          $table->dropColumn(['min_order_limit','max_order_limit']);
        });
    }
}

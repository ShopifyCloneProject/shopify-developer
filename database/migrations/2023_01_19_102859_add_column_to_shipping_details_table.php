<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToShippingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_details', function (Blueprint $table) {
            $table->boolean('round_value')->comment('1=yes, 0=no')->default(0)->after('city_data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_details', function (Blueprint $table) {
            $table->dropColumn(['round_value']);
        });
    }
}

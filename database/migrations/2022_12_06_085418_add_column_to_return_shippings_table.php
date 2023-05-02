<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToReturnShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('return_shippings', function (Blueprint $table) {
            $table->text('rate_data')->nullable()->after('courier_id');
            $table->string('parent_shipping_id')->nullable()->after('rate_data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('return_shippings', function (Blueprint $table) {
            $table->dropColumn(['rate_data','parent_shipping_id']);
        });
    }
}

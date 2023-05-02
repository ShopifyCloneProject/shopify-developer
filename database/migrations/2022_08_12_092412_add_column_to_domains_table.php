<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->string('order_start_number')->after('default_currency')->nullable();
            $table->string('display_order_limit')->after('order_start_number')->nullable();
            $table->string('per_page')->after('display_order_limit')->nullable();
            $table->string('search_user_limit')->after('per_page')->nullable();
            $table->string('search_product_limit')->after('search_user_limit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn(['order_start_number','display_order_limit','per_page','search_user_limit','search_product_limit']);
        });
    }
}

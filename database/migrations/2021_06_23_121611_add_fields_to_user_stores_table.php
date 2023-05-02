<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUserStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_stores', function (Blueprint $table) {
            $table->unsignedBigInteger('address_id')->comment('ref: addresses')->nullable()->change();
            $table->char('user_id', 36)->after('id');
            $table->string('store_name')->after('user_store_industry_id');
            $table->string('address', 2000)->nullable()->after('company');
            $table->string('address_2', 2000)->nullable()->after('address');
            $table->string('mobile', 50)->nullable()->after('address_2');
            $table->string('city')->nullable()->after('mobile');
            $table->unsignedBigInteger('state_id')->comment('ref: states')->nullable()->after('city');
            $table->unsignedBigInteger('country_id')->comment('ref: countries')->nullable()->after('state_id');
            $table->string('postal_code', 10)->nullable()->after('country_id');
            $table->unsignedInteger('unit_system')->nullable()->change();
            $table->unsignedInteger('unit_weight')->nullable()->change();
            $table->unsignedBigInteger('currency_id')->after('suffix');
            $table->string('symbol', 5)->nullable()->after('currency_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_stores', function (Blueprint $table) {
             $table->dropColumn(['user_id', 'store_name', 'address', 'address_2', 'mobile', 'city', 'state_id', 'country_id', 'postal_code']);
        });
    }
}

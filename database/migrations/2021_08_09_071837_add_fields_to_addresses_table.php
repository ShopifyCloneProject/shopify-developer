<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('user_id', 36)->nullable()->change();
            $table->string('mac_id')->nullable()->after('user_id');
            $table->string('email', 100)->nullable()->after('phone_code');
            $table->boolean('is_save')->comment('0=no,1=yes')->default(0)->after('city_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('user_id')->change();
            $table->dropColumn(['mac_id', 'is_save', 'email']);
        });
    }
}

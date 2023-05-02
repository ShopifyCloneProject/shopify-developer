<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->string('payment_status')->default(0)->after('mac_id');
            $table->unsignedBigInteger('addresses_id')->nullable()->after('payment_status')->comment('ref: addresses');
            $table->unsignedBigInteger('shipping_address_id')->nullable()->after('addresses_id')->comment('ref: addresses'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn(['payment_status','addresses_id','shipping_address_id']);
        });
    }
}

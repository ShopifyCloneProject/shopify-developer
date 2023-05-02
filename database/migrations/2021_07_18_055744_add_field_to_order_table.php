<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // if (Schema::hasColumn('orders', 'financial_status_id'))
            // {
            //     $table->dropColumn('financial_status_id');
            // }
            $table->string('order_nr')->nullable()->after('id');
            $table->string('financial_status')->comment('authorized, pending, partially_paid, paid, partially_refunded, refunded, voided')->after('total');
            $table->string('status')->comment('open, closed, cancelled, any')->after('financial_status');
            $table->unsignedBigInteger('shipping_method_id')->nullable()->change('id');
            $table->string('fulfillment_status')->comment('fullfilled, unfullfilled')->change();
            $table->string('gateway')->nullable()->after('shipping_method_id');
            $table->string('note')->nullable()->after('receipt_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_nr', 'financial_status', 'status', 'gateway', 'note']);
        });
    }
}

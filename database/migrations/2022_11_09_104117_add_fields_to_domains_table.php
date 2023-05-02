<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToDomainsTable extends Migration
{
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up()
	{
		Schema::table('domains', function (Blueprint $table) {
			$table->string('shipment_order_number')->after('search_product_limit')->nullable();
			$table->string('return_shipment_order_number')->after('shipment_order_number')->nullable();
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
			$table->dropColumn(['shipment_order_number','return_shipment_order_number']);
		});
	}
}
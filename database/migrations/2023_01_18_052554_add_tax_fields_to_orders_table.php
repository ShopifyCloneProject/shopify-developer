<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaxFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('tax_round')->comment('1=yes, 0=no')->default(0)->after('source');
            $table->boolean('shipping_round')->comment('1=yes, 0=no')->default(0)->after('tax_round');
            $table->decimal('country_tax_percentage',15,2)->nullable()->after('admin_approve');
            $table->decimal('state_tax_percentage',15,2)->nullable()->after('country_tax_percentage');
            $table->text('state_text')->nullable()->after('state_tax_percentage');
            $table->bigInteger('state_tax_additional')->after('state_text');
            $table->unsignedBigInteger('shipping_rate_id')->nullable()->comment('ref:shipping_rates')->after('state_tax_additional');
            $table->boolean('rate_status')->comment('0=Carrier, 1=Own')->default(1)->after('shipping_rate_id');
            $table->boolean('conditions')->comment('0=No, 1=Yes')->default(1)->after('rate_status');
            $table->boolean('weight_or_price')->comment('0=Weight, 1=Price')->default(0)->after('conditions');
            $table->decimal('min',15,2)->nullable()->after('weight_or_price');
            $table->decimal('max',15,2)->nullable()->after('min');
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
            $table->dropColumn(['tax_round','shipping_round','country_tax_percentage','state_tax_percentage','state_text','state_tax_additional','shipping_rate_id','rate_status','conditions','weight_or_price','min','max']);
        });
    }
}

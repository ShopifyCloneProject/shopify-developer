<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_taxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id')->comment('ref: countries');
            $table->tinyInteger('default')->default('0')->comment('0=Default 1=Select');
            $table->decimal('tax_percentage',15,2)->nullable();
            $table->tinyInteger('include_tax')->default('0')->comment('0=Default 1=Select');
            $table->tinyInteger('exclude_tax')->default('0')->comment('0=Default 1=Select');
            $table->tinyInteger('charge_on_shipping')->default('0')->comment('0=Default 1=Select');
            $table->tinyInteger('charge_vat_digital_goods')->default('0')->comment('0=Default 1=Select');
            $table->boolean('round_value')->comment('1=yes, 0=no')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country_taxes');
    }
}

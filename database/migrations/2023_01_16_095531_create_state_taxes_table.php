<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStateTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('state_taxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_taxes_id')->comment('ref: country_taxes');
            $table->unsignedBigInteger('state_id')->comment('ref: states');
            $table->decimal('state_tax_percentage',15,2)->nullable();
            $table->text('text')->nullable();
            $table->bigInteger('tax_additional');
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
        Schema::dropIfExists('state_taxes');
    }
}

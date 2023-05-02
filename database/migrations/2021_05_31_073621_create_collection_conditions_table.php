<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_conditions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('condition_id')->index()->comment('ref: collections');
            $table->unsignedBigInteger('collection_title_id')->comment('ref: condition_options');
            $table->string('model_name')->nullable();
            $table->string('model_ref')->nullable();
            $table->boolean('status')->comment('0=Model Not Use, 1 = Model Use')->default(1);
            $table->string('value')->nullable();
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
        Schema::dropIfExists('collection_conditions');
    }
}

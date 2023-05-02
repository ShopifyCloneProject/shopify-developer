<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variant_media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_id')->index()->comment('ref:products');
            $table->unsignedBigInteger('product_variant_id')->index()->comment('ref:product_variant_options');;
            $table->string('src')->nullable();
            $table->string('src_alt_text')->nullable();
            $table->boolean('is_default')->comment('0=Yes, 1=No')->default(0);
            $table->integer('reorder')->default(0);
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
        Schema::dropIfExists('variant_media');
    }
}

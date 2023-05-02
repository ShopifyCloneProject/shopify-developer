<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenerateXmlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xmls', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->integer('product_type')->comment('0=>products, 1=>Collections')->default(0);
            $table->integer('option_type')->comment('0=>All, 1=>Manual')->default(0);
            $table->string('createtime')->nullable();
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
        Schema::dropIfExists('xmls');
    }
}

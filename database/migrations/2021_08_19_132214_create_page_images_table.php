<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagemedias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('src')->nullable();
            $table->string('src_alt_text')->nullable();
            $table->integer('order')->default(0);
            $table->unsignedBigInteger('section_id')->nullable();
            $table->boolean('align')->comment('0=Left, 1=Right')->default(0);
            $table->string('text1')->nullable();
            $table->string('text2')->nullable();
            $table->string('url')->nullable();
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
        Schema::dropIfExists('pagemedias');
    }
}

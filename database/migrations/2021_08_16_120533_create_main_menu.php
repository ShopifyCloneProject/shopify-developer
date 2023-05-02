<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mainmenu', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('menuname')->nullable();
            $table->string('setlink')->nullable();
            $table->string('url')->nullable();
            $table->string('category')->nullable();
            $table->string('category_product_relation')->nullable();
            $table->integer('order')->default(0);
            $table->integer('relation')->nullable();
            $table->string('level')->nullable();
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
        Schema::dropIfExists('mainmenu');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXmlFeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xmlfeed', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('choose1')->nullable();
            $table->unsignedBigInteger('choose2')->nullable();
            $table->boolean('default')->defult(0);
            $table->string('createtime')->default(1);
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
        Schema::dropIfExists('xmlfeed');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('ref: users');
            $table->string('bio')->nullable();
            $table->date('birth_date')->nullable();
            $table->unsignedBigInteger('country_id')->comment('ref: countries');
            $table->string('website')->nullable();
            $table->string('phone')->nullable();;
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('google')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();
            $table->string('quora')->nullable();
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
        Schema::dropIfExists('user_details');
    }
}

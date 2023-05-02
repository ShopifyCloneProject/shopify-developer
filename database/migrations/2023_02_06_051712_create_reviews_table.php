<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->comment('ref: users')->nullable();
            $table->string('product_id')->comment('ref: products')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('star_rating')->default(0);
            $table->tinyInteger('approved')->default(0)->comment('0 = NotApprove 1=Approve');
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
        Schema::dropIfExists('reviews');
    }
}

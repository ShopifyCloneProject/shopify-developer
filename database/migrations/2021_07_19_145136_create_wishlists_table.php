<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWishlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::statement('SET SESSION sql_require_primary_key=0');
        Schema::create('wishlists', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('user_id', 36)->index()->comment('ref:orders');
            $table->string('product_id', 36)->index()->comment('ref:products');
            $table->unsignedBigInteger('product_variant_options_id')->index()->comment('ref:product_variant_options')->nullable();
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
        Schema::dropIfExists('wishlists');
    }
}

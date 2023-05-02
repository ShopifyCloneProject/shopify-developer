<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToProductMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_media', function (Blueprint $table) {
            $table->boolean('source')->default(0)->after('reorder')->comment('0=web;1=csv');
            $table->string('cdn_url', 1000)->nullable()->after('source');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_media', function (Blueprint $table) {
            $table->dropColumn(['source', 'cdn_url']);
        });
    }
}

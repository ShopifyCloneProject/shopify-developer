<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConvertColumnOnProductMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_media', function (Blueprint $table) {
            $table->boolean('convert')->default(0)->after('source');
        });

        Schema::table('variant_media', function (Blueprint $table) {
            $table->boolean('convert')->default(0)->after('source');
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
            $table->dropColumn('convert');
        });

        Schema::table('variant_media', function (Blueprint $table) {
            $table->dropColumn('convert');
        });
    }
}

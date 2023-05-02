<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToThemepagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('themepages', function (Blueprint $table) {
            $table->string('icon')->after('logo')->nullable();
            $table->string('title')->after('icon')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('themepages', function (Blueprint $table) {
            $table->dropColumn(['icon','title']);
        });
    }
}

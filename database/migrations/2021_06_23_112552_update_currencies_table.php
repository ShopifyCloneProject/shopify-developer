<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('currencies', function (Blueprint $table) {
            if (Schema::hasColumn('currencies', 'title'))
            {
                $table->renameColumn('title', 'name');
                $table->string('title')->nullable()->change();

            }
            $table->string('currency', 50)->unique()->change();
            $table->string('symbol', 5)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('currencies', function (Blueprint $table) {
            if (Schema::hasColumn('currencies', 'name'))
            {
                $table->renameColumn('name', 'title');
            }
        });
    }
}

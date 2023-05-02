<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSortedTypeToCollections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->integer('sorted_type')->default(1)->after('conditions_type')->comment("'1' => 'Best selling', '2' => 'Product title A-Z', '3' => 'Product title Z-A', '4' => 'Highest price', '5' => 'Lowest price', '6' => 'Newest', '7' => 'Oldest', '8' => 'Manually'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn('sorted_type');
        });
    }
}

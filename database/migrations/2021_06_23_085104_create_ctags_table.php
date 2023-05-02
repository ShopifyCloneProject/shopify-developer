<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::statement('SET SESSION sql_require_primary_key=0');
        Schema::create('ctags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->boolean('status')->comment('1=Active 0=Inactive')->default(1);
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
        Schema::dropIfExists('ctags');
    }
}

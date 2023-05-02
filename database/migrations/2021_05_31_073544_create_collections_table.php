<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('collection_type')->comment('0=Manual 1=Automated')->default(0);
            $table->boolean('conditions_type')->comment('0=All 1=Any')->default(0);
            $table->tinyInteger('description_position')->comment('1=above,2=below')->default(0);
            $table->string('seo_keywords')->nullable();
            $table->longText('seo_description')->nullable();
            $table->boolean('status')->comment('1=Active 0=Inactive')->default(1);
            $table->string('src_alt_text')->nullable();
            $table->string('url')->nullable();
            $table->boolean('online_store')->comment('1=Yes 0=No')->default(0);
            $table->datetime('schedule_time')->nullable();
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
        Schema::dropIfExists('collections');
    }
}

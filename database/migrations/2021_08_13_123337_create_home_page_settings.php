<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomePageSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(env('APP_ENV') == 'Production')
        {
            DB::statement('SET SESSION sql_require_primary_key=0');
        }

        Schema::create('themepages', function (Blueprint $table) {
            $table->id();
            $table->integer('page')->comment('1=Home, 2=ProductDetail')->nullable();
            $table->string('sectionname')->nullable();
            $table->boolean('status')->comment('1=Display, 0=Not Display')->default(1);
            $table->integer('order')->default(0);
            $table->string('logo')->nullable();
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
        Schema::dropIfExists('themepages');
    }
}

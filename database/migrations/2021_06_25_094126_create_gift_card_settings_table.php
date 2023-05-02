<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftCardSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_card_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('user_id', 36)->comment('ref: user');
            $table->boolean('type')->default(1)->comment('1=Never expired, 2= expired time');
            $table->integer('days')->nullable();
            $table->integer('option')->nullable()->comment('1=Day, 2=Month, 3=Year');
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
        Schema::dropIfExists('gift_card_settings');
    }
}

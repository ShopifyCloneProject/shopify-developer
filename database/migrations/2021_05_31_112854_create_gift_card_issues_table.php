<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftCardIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_card_issues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id')->index()->comment('Ref: users');
            $table->string('gift_card_id')->index()->comment('Ref: products');
            $table->unsignedBigInteger('currency_id')->comment('Ref: currencies');
            $table->string('code');
            $table->string('status')->nullable();
            $table->datetime('date_issued')->nullable();
            $table->decimal('remaining_value', 15, 2)->nullable();
            $table->decimal('initial_value', 15, 2)->nullable();
            $table->string('expiration_type')->nullable();
            $table->date('expiration_date')->nullable();
            $table->longText('note')->nullable();
            $table->boolean('enabled')->comment('0=No, 1=Yes')->default(1);
            $table->datetime('disabled_at')->nullable();
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
        Schema::dropIfExists('gift_card_issues');
    }
}

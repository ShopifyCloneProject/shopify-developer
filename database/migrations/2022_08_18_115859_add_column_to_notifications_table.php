<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->boolean('email')->after('status')->default(1);
            $table->text('email_template')->after('email')->nullable();
            $table->boolean('sms')->after('email_template')->default(1);
            $table->text('sms_template')->after('sms')->nullable();
            $table->text('variable_description')->after('sms_template')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['email','email_template','sms','sms_template','variable_description']);
        });
    }
}

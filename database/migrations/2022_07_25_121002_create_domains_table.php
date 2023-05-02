<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('ref: users');
            $table->string('appname')->nullable();
            $table->string('appurl')->nullable();
            $table->string('authurl')->nullable();
            $table->string('call_app_url')->nullable();
            $table->string('front_app_url')->nullable();
            $table->string('db_connection')->nullable();
            $table->string('db_host')->nullable();
            $table->string('db_port')->nullable();
            $table->string('db_database')->nullable();
            $table->string('db_username')->nullable();
            $table->string('db_password')->nullable();
            $table->string('mail_mailer')->nullable();
            $table->string('mail_host')->nullable();
            $table->string('mail_port')->nullable();
            $table->string('mail_username')->nullable();
            $table->string('mail_password')->nullable();
            $table->string('mail_encryption')->nullable();
            $table->string('mail_from_address')->nullable();
            $table->string('mail_from_name')->nullable();
            $table->string('cashfree_return_url')->nullable();
            $table->string('cashfree_notify_url')->nullable();
            $table->string('razorpay_callback_url')->nullable();
            $table->string('instamojo_callback_url')->nullable();
            $table->string('paytm_callback_url')->nullable();
            $table->string('instamojo_payment_request')->nullable();
            $table->string('instamojo_webhook')->nullable();
            $table->string('default_currency')->nullable();
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
        Schema::dropIfExists('domains');
    }
}

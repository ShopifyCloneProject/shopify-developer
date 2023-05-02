<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // only for production
        if(env('APP_ENV') == 'Production')
        {
            DB::statement('SET SESSION sql_require_primary_key=0');
        }

        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('mobile', 50)->nullable();
            $table->string('email', 100)->nullable()->unique();
            $table->datetime('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->char('gender', 2)->comment('M=Male F=Female T=Transgender')->default('M');
            $table->string('google')->nullable();
            $table->string('facebook')->nullable();
            $table->boolean('is_verified')->comment('0=Unverified,1=Verified')->default(1);
            $table->string('company')->nullable();
            $table->boolean('email_notification_status')->comment('1=Yes 0=No')->default(1);
            $table->boolean('sms_notification_status')->comment('1=Yes 0=No')->default(1);
            $table->boolean('blocked')->comment('0=Blocked 1=Unblocked')->default('1');
            $table->boolean('accept_marketing')->comment('1=Yes 0=No')->default(1);
            $table->decimal('total_spent', 15, 2)->nullable();
            $table->integer('total_orders')->default(0);
            $table->string('tags')->nullable();
            $table->longText('note')->nullable();
            $table->string('tax_exempt')->comment('0=No 1=Yes')->default(0);
            $table->string('remember_token')->nullable();
            $table->unsignedBigInteger('role_id')->comment('ref: roles');
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
        Schema::dropIfExists('users');
    }
}

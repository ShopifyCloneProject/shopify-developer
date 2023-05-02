<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('length_type_id')->nullable()->comment('ref: dimensions')->after('weight');
            $table->decimal('length', 15, 2)->default(0)->after('length_type_id');
            $table->unsignedBigInteger('width_type_id')->nullable()->comment('ref: dimensions')->after('length');
            $table->decimal('width', 15, 2)->default(0)->after('width_type_id');
            $table->unsignedBigInteger('height_type_id')->nullable()->comment('ref: dimensions')->after('width');
            $table->decimal('height', 15, 2)->default(0)->after('height_type_id');
            $table->dateTime('start_schedule_date')->nullable()->after('special_price');
        });

        Schema::table('product_variant_options', function (Blueprint $table) {
            $table->unsignedBigInteger('length_type_id')->nullable()->comment('ref: dimensions')->after('weight');
            $table->decimal('length', 15, 2)->default(0)->after('length_type_id');
            $table->unsignedBigInteger('width_type_id')->nullable()->comment('ref: dimensions')->after('length');
            $table->decimal('width', 15, 2)->default(0)->after('width_type_id');
            $table->unsignedBigInteger('height_type_id')->nullable()->comment('ref: dimensions')->after('width');
            $table->decimal('height', 15, 2)->default(0)->after('height_type_id');
            $table->dateTime('start_schedule_date')->nullable()->after('special_price');
        });

        Schema::table('order_products', function (Blueprint $table) {
            $table->unsignedBigInteger('length_type_id')->nullable()->comment('ref: dimensions')->after('weight');
            $table->decimal('length', 15, 2)->default(0)->after('length_type_id');
            $table->unsignedBigInteger('width_type_id')->nullable()->comment('ref: dimensions')->after('length');
            $table->decimal('width', 15, 2)->default(0)->after('width_type_id');
            $table->unsignedBigInteger('height_type_id')->nullable()->comment('ref: dimensions')->after('width');
            $table->decimal('height', 15, 2)->default(0)->after('height_type_id');
            $table->dateTime('start_schedule_date')->nullable()->after('special_price');
        });

        Schema::table('refund_products', function (Blueprint $table) {
            $table->unsignedBigInteger('length_type_id')->nullable()->comment('ref: dimensions')->after('weight');
            $table->decimal('length', 15, 2)->default(0)->after('length_type_id');
            $table->unsignedBigInteger('width_type_id')->nullable()->comment('ref: dimensions')->after('length');
            $table->decimal('width', 15, 2)->default(0)->after('width_type_id');
            $table->unsignedBigInteger('height_type_id')->nullable()->comment('ref: dimensions')->after('width');
            $table->decimal('height', 15, 2)->default(0)->after('height_type_id');
        });   
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['length_type_id','length','width_type_id','width','height_type_id','height','start_schedule_date']);
        });

        Schema::table('product_variant_options', function (Blueprint $table) {
            $table->dropColumn(['length_type_id','length','width_type_id','width','height_type_id','height','start_schedule_date']);
        });

        Schema::table('order_products', function (Blueprint $table) {
            $table->dropColumn(['length_type_id','length','width_type_id','width','height_type_id','height','start_schedule_date']);
        });

        Schema::table('refund_products', function (Blueprint $table) {
            $table->dropColumn(['length_type_id','length','width_type_id','width','height_type_id','height']);
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_shippings', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_id')->comment('ref: shipments')->nullable();
            $table->string('order_id')->comment('ref: orders')->nullable();
            $table->string('return_shipment_order_number')->nullable();   
            $table->string('user_id')->comment('ref: users')->nullable();
            $table->tinyInteger('admin_approve')->default('0')->comment('0=NotApprove 1=Approve');   
            $table->string('pickup_id')->nullable();
            $table->text('title')->nullable();
            $table->string('quantity')->nullable();
            $table->decimal('selling_price', 15, 2)->nullable();
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('shipping_charges', 15, 2)->default(0);
            $table->decimal('transaction_charges', 15, 2)->default(0);
            $table->decimal('total_discount', 15, 2)->default(0);
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->unsignedBigInteger('weight_type_id')->nullable()->comment('ref: weightmanages');
            $table->decimal('weight', 15, 2)->default(0);
            $table->unsignedBigInteger('length_type_id')->nullable()->comment('ref: dimensions');
            $table->decimal('length', 15, 2)->default(0);
            $table->unsignedBigInteger('width_type_id')->nullable()->comment('ref: dimensions');
            $table->decimal('width', 15, 2)->default(0);
            $table->unsignedBigInteger('height_type_id')->nullable()->comment('ref: dimensions');
            $table->decimal('height', 15, 2)->default(0);
            $table->string('shipping_method_id')->comment('ref: shipping_methods')->nullable();   
            $table->string('courier_id')->comment('ref: couriers')->nullable();
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
        Schema::dropIfExists('return_shippings');
    }
}

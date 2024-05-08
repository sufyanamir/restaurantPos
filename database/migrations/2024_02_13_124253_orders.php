<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function(Blueprint $table){
            $table->id('order_main_id');
            $table->integer('added_user_id');
            $table->integer('order_id');
            $table->text('order_no');
            $table->string('order_type');
            $table->double('order_sub_total');
            $table->double('order_discount')->default(0);
            $table->double('order_grand_total');
            $table->double('order_final_total');
            $table->double('order_sale_tax')->default(0);
            $table->double('service_charges')->default(0);
            $table->double('order_change')->default(0);
            $table->integer('order_split')->default(1);
            $table->integer('order_split_amount')->default(1);
            $table->integer('is_uploaded')->default(0);
            $table->text('customer_name')->nullable();
            $table->text('phone')->nullable();
            $table->integer('assign_rider')->nullable();
            $table->text('customer_address')->nullable();
            $table->integer('table_id')->nullable();
            $table->text('table_location')->nullable();
            $table->text('table_no')->nullable();
            $table->integer('table_capacity')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('waiter_id')->nullable();
            $table->text('waiter_name')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};

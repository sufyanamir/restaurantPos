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
        Schema::create('inventory_plus', function (Blueprint $table){
            $table->id('inv_p_id');
            $table->integer('company_id');
            $table->integer('branch_id');
            $table->integer('supplier_id');
            $table->integer('added_user_id');
            $table->timestamp('inv_p_date')->useCurrent();
            $table->bigInteger('supplier_phone')->nullable();
            $table->text('supplier_note')->nullable();
            $table->longText('inv_order_details')->nullable();
            $table->double('inv_p_total')->default(0);
            $table->double('inv_p_paid')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_plus');
    }
};

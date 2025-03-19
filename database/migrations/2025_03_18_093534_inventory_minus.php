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
        Schema::create('inventory_minus', function (Blueprint $table){
            $table->id('inv_m_id');
            $table->integer('company_id');
            $table->integer('branch_id');
            $table->integer('dpt_name');
            $table->integer('added_user_id');
            $table->timestamp('inv_m_date')->useCurrent();
            $table->bigInteger('dpt_phone')->nullable();
            $table->text('dpt_note')->nullable();
            $table->longText('inv_order_details')->nullable();
            $table->double('inv_m_total')->default(0);
            $table->double('inv_m_paid')->default(0);
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
        Schema::dropIfExists('inventory_minus');
    }
};

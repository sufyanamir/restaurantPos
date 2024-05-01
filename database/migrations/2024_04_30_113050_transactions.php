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
        Schema::create('transactions', function(Blueprint $table){
            $table->id('transaction_id');
            $table->integer('company_id')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('added_user_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->double('debit_amount')->nullable();
            $table->double('credit_amount')->nullable();
            $table->text('transaction_added_from')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
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
        Schema::dropIfExists('transactions');
    }
};

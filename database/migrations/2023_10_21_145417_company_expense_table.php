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
        Schema::create('company_expense', function (Blueprint $table) {
            $table->id('expense_id');
            $table->integer('added_user_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->timestamp('expense_date')->nullable();
            $table->text('expense_name')->nullable();
            $table->float('expense_cost')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_expense');
    }
};

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
        Schema::create('company_branches', function(Blueprint $table){
            $table->id('branch_id');
            $table->integer('company_id');
            $table->text('branch_code')->unique();
            $table->text('branch_name');
            $table->text('branch_email');
            $table->text('branch_phone');
            $table->text('branch_address');
            $table->string('branch_manager');
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
        Schema::dropIfExists('company_branches');
    }
};

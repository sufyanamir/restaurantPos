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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id('inventory_id');
            $table->integer('company_id');
            $table->integer('branch_id');
            $table->integer('supplier_id');
            $table->integer('added_user_id');
            $table->string('inv_name');
            $table->double('inv_stockinhand')->default(0);
            $table->string('inv_unit')->nullable();
            $table->double('inv_box_price')->default(0);
            $table->double('inv_bag_qty')->default(0);
            $table->double('inv_unit_price')->default(0);
            $table->double('low_stock')->default(0);
            $table->text('inv_type')->nullable();
            $table->integer('inv_status')->default(1);
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
        Schema::dropIfExists('inventory');
    }
};

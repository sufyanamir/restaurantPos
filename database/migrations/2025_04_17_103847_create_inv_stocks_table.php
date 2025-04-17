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
        Schema::create('inv_stocks', function (Blueprint $table) {
            $table->id("inv_stocks_id");
            $table->integer("user_id");
            $table->integer("branch_id");
            $table->integer("inv_items_id");
            $table->integer("supplier_id");
            $table->integer("inv_stock_qty");
            $table->float("inv_unit_purchase_price");
            $table->date("inv_unit_expiry");
            $table->string("inv_stocks_type");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inv_stocks');
    }
};

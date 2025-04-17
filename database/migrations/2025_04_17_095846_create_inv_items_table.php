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
        Schema::create('inv_items', function (Blueprint $table) {
            $table->id("inv_items_id");
            $table->integer("company_id");
            $table->integer("branch_id");
            $table->integer("user_id");
            $table->integer("supplier_id");
            $table->integer("inv_unit_id");
            $table->string("inv_items_name");
            $table->integer("inv_item_cats_id");
            $table->integer("inv_items_bag_qty");
            $table->integer("inv_items_stock");
            $table->float("unit_purchase_price");
            $table->integer("inv_stock_alert");
            $table->string("inv_auto_   order")->nullable();
            $table->integer("inv_unit_status")->default(1);
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
        Schema::dropIfExists('inv_items');
    }
};

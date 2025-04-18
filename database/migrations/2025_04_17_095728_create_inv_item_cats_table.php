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
        Schema::create('inv_item_cats', function (Blueprint $table) {
            $table->id("inv_item_cats_id");
            $table->integer("company_id");
            $table->integer("user_id");
            $table->string("inv_item_cats_name");
            $table->integer("inv_item_cats_status")->default(1);
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
        Schema::dropIfExists('inv_item_cats');
    }
};

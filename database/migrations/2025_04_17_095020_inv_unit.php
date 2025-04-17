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
        Schema::table('inv_unit', function (Blueprint $table) {
            $table->id("inv_unit_id");
            $table->id("company_id");
            $table->id("unit_id");
            $table->id("inv_unit_name");
            $table->id("inv_unit_symbol");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};

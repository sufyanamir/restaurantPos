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
        Schema::table('company', function (Blueprint $table) {
            $table->float('sale_tax')->nullable()->default(0);
            $table->string('inventory')->nullable()->default('no');
            $table->string('currency')->nullable()->default('USD');
            $table->string('kitchen_slip')->nullable()->default('yes');
            $table->integer('service_charges')->nullable()->default(0);
            $table->string('ui_layout')->default('1');
            $table->string('print_bill_border')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company');
    }
};

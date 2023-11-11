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
        Schema::create('services', function(Blueprint $table){
           $table->id('service_id');
           $table->string('service_name')->unique();
           $table->string('service_subtitle');
           $table->integer('service_charges');
           $table->text('service_desc');
           $table->text('app_url')->nullable();
           $table->text('service_image')->nullable();
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
        Schema::dropIfExists('services');
    }
};

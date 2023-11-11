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
        Schema::create('image_gallery', function(Blueprint $table) {
            $table->id('image_id');
            $table->integer('added_user_id')->nullable();
            $table->integer('staff_id')->nullable();
            $table->integer('company_id');
            $table->integer('customer_id');
            $table->integer('order_id')->nullable();
            $table->text('stored_media');
            $table->text('app_url')->nullable();
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
        Schema::dropIfExists('image_gallery');
    }
};

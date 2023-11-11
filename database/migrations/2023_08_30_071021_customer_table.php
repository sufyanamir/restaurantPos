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
        Schema::create('customers', function(Blueprint $table){
            $table->id('customer_id');
            $table->integer('company_id')->nullable();
            $table->integer('added_user_id')->nullable();
            $table->string('customer_name');
            $table->string('customer_email')->unique();
            $table->bigInteger('customer_phone');
            $table->text('customer_address');
            $table->string('customer_social_links');
            $table->text('customer_image')->nullable();
            $table->integer('customer_status')->default(0);
            $table->text('app_url')->nullable();
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
        Schema::dropIfExists('customers');
    }
};

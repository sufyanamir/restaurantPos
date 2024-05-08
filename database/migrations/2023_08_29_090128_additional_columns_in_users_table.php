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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('company_id')->nullable();
            $table->text('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('category')->nullable();
            $table->text('user_image')->nullable();
            $table->text('social_links')->nullable();
            $table->string('user_role')->nullable();
            $table->string('user_status')->nullable();
            $table->text('user_priviledges')->nullable();
            $table->integer('user_branch')->nullable();
            $table->text('app_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        // Schema::table('users', function (Blueprint $table) {
        //     //
        // });
    }
};

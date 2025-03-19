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
        Schema::create('staff_attendance', function (Blueprint $table) {
            $table->id('attendance_id');
            $table->integer('company_id');
            $table->integer('branch_id');
            $table->integer('added_user_id');
            $table->timestamp('attendance_date')->useCurrent();
            $table->text('attendance_details')->nullable();
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
        Schema::dropIfExists('staff_attendance');
    }
};

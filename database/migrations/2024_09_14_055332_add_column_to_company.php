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
            $table->text('color_palette')->default('{
        "primary": {
            "main": "#de1616",
            "light": "#f00000"
        },
        "secondary": {
            "main": "#ff193b",
            "light": "#ffcad4",
            "gray": "#FAFAFA"
        }
    }');
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

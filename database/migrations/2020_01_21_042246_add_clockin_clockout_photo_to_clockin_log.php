<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClockinClockoutPhotoToClockinLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clock_in_logs', function (Blueprint $table) {
            $table->dropColumn("photo_id");
            $table->integer("clock_in_photo_id");
            $table->integer("clock_out_photo_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clock_in_logs', function (Blueprint $table) {
            //
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeClockOutTimestampAndPhotoNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clock_in_logs', function (Blueprint $table) {
            $table->dateTime("clockout_timestamp")->nullable()->change();
            $table->integer("clock_out_photo_id")->nullalble()->change();
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

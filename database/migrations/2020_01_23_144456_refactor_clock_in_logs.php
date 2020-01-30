<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactorClockInLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clock_in_logs', function (Blueprint $table) {
            $table->dropColumn("clockin_timestamp");
            $table->dropColumn("clockout_timestamp");
            $table->dropColumn("clock_in_photo_id");
            $table->dropColumn("clock_out_photo_id");

            $table->dateTime("timestamp");
            $table->integer("photo_id");
            $table->string("type");
            $table->integer("clock_in_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logs', function (Blueprint $table) {
            //
        });
    }
}

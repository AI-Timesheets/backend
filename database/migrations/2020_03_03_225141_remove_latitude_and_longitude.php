<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveLatitudeAndLongitude extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clock_in_logs', function (Blueprint $table) {
            $table->dropColumn("latitude");
            $table->dropColumn("longitude");
            $table->integer("geographic_location_id");
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

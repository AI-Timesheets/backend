<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGeographicLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geographic_locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->double("latitude");
            $table->double("longitude");
            $table->string("address");
            $table->string("city");
            $table->string("state");
            $table->string("country");
            $table->integer("area_code");
            $table->integer("area_code_suffix");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('geographic_locations');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    private function addDeletedAt($arr) {
        foreach ($arr as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function up()
    {
        $this->addDeletedAt([
            "users",
            "photos",
            "companies",
            "locations",
            "company_employees",
            "clock_in_logs",
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultRowToMultieggLicense extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('multiegg')->insert(
            array(
                'updated_at' => '2023-01-15',
                'confirm_key' => '000111',
                'license_key' => '000111',
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('multiegg', function (Blueprint $table) {
            //
        });
    }
}
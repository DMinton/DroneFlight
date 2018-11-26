<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlightsBatteryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights_battery', function (Blueprint $table) {
            $table->integer("flight_id");
            $table->string("battery_name", 200);
            $table->string("battery_sn", 200);
            $table->float('battery_percent', 4, 1);
            $table->float('battery_temperature', 4, 1);
            $table->bigInteger('timestamp');

            $table->index('flight_id');
            $table->index('timestamp');
            $table->index('battery_name');
            $table->index('battery_sn');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flights_battery');
    }
}

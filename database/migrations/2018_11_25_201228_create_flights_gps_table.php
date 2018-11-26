<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlightsGpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights_gps', function (Blueprint $table) {
            $table->integer("flight_id");
            $table->double('lat', 9, 6);
            $table->double('long', 9, 6);
            $table->double('alt', 9, 6);
            $table->bigInteger('timestamp');

            $table->index('flight_id');
            $table->index('timestamp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flights_gps');
    }
}

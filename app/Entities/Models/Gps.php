<?php namespace App\Entities\Models;

use Illuminate\Database\Eloquent\Model;

class Gps extends Model
{
    CONST TELEMETRY_NAME = 'gps';
    public $timestamps = false;
    protected $table = 'flights_gps';

    /**
     * @param $jsonObject
     * @return $this
     */
    public function setFromTelemetryData($jsonObject)
    {
        $this->flight_id = $jsonObject->flight_id;
        $this->lat = $jsonObject->lat;
        $this->long = $jsonObject->long;
        $this->alt = $jsonObject->alt;
        $this->timestamp = $jsonObject->timestamp;

        return $this;
    }
}
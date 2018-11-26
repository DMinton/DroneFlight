<?php namespace App\Entities\Models;

use Illuminate\Database\Eloquent\Model;

class Battery extends Model
{
    CONST TELEMETRY_NAME = 'battery';
    public $timestamps = false;
    protected $table = 'flights_battery';

    /**
     * @param $jsonObject
     * @return $this
     */
    public function setFromTelemetryData($jsonObject)
    {
        $this->flight_id = $jsonObject->flight_id;
        $this->battery_name = $jsonObject->battery_name;
        $this->battery_sn = $jsonObject->battery_sn;
        $this->battery_percent = $jsonObject->battery_percent;
        $this->battery_temperature = $jsonObject->battery_temperature;
        $this->timestamp = $jsonObject->timestamp;

        return $this;
    }
}
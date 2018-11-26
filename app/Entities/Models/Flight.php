<?php namespace App\Entities\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $table = 'flights';

    /**
     * @param $jsonObject
     * @return $this
     */
    public function setFromTelemetryData($jsonObject)
    {
        $this->uuid = $jsonObject->uuid;
        $this->aircraft_name = $jsonObject->aircraft_name;
        $this->aircraft_sn = $jsonObject->aircraft_sn;

        return $this;
    }
}
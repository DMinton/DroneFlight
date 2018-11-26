<?php namespace App\Entities\Telemetry;

use App;

class TelemetryFactory
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $telemetryObjects = array(
        'flights' => FlightTelemetry::class
    );

    /**
     * @return AbstractTelemetry
     */
    public function get()
    {
        if (isset($this->telemetryObjects[$this->type])) {
            return App::make($this->telemetryObjects[$this->type]);
        }
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
}
<?php namespace App\Entities\Telemetry\Helpers;

use App\Entities\Models\Battery;
use App\Entities\Models\Flight;
use App\Entities\Models\Gps;
use App\Entities\Models\ModelFactory;

class FlightTelemetryHelper
{
    /**
     * @var Flight
     */
    protected $flight;

    /**
     * @var array
     */
    protected $batteryDetails;

    protected $modelFactory;

    /**
     * FlightTelemetryHelper constructor.
     * @param ModelFactory $modelFactory
     */
    public function __construct(ModelFactory $modelFactory)
    {
        $this->modelFactory = $modelFactory;
    }

    /**
     * @param string $uuid
     * @param string $aircraft_name
     * @param string $aircraft_sn
     * @return bool
     */
    public function newFlight($uuid, $aircraft_name, $aircraft_sn)
    {
        $this->flight = $this->modelFactory->flight();
        $this->flight->uuid = $uuid;
        $this->flight->aircraft_name = $aircraft_name;
        $this->flight->aircraft_sn = $aircraft_sn;

        return $this->flight->save();
    }

    /**
     * @param array $batteryDetails
     * @return FlightTelemetryHelper
     */
    public function setBatteryDetails(array $batteryDetails)
    {
        $this->batteryDetails = $batteryDetails;
        return $this;
    }

    /**
     * @param string $type
     * @param \stdClass $frameJson
     */
    public function saveNewFlightFrame($type, $frameJson)
    {
        $frameJson->flight_id = $this->flight->id;

        switch ($type) {
            case Gps::TELEMETRY_NAME:
                $this->saveGpsFrame($frameJson);
                break;
            case Battery::TELEMETRY_NAME:
                $this->saveBatteryFrame($frameJson);
                break;
        }
    }

    /**
     * @param \stdClass $frameJson
     * @return bool
     */
    private function saveGpsFrame($frameJson)
    {
        $gps = $this->modelFactory->gps();
        $gps->setFromTelemetryData($frameJson);

        return $gps->save();
    }

    /**
     * @param \stdClass $frameJson
     * @return bool
     */
    private function saveBatteryFrame($frameJson)
    {
        $frameJson->battery_name = isset($this->batteryDetails[$frameJson->battery_sn]) ? $this->batteryDetails[$frameJson->battery_sn]['battery_name'] : '';

        $battery = $this->modelFactory->battery();
        $battery->setFromTelemetryData($frameJson);

        return $battery->save();
    }
}
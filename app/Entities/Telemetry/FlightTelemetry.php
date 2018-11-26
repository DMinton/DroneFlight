<?php namespace App\Entities\Telemetry;

use App\Entities\Models\ModelFactory;
use App\Entities\Telemetry\Helpers\FlightTelemetryHelper;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class FlightTelemetry extends AbstractTelemetry
{

    /**
     * @var FlightTelemetryHelper
     */
    protected $helper;

    /**
     * FlightTelemetry constructor.
     * @param FlightTelemetryHelper $flightTelemetryHelper
     */
    public function __construct(FlightTelemetryHelper $flightTelemetryHelper)
    {
        $this->helper = $flightTelemetryHelper;
    }

    /**
     * While I am actually saving to the DB here, this should just save the file to aws and queue the job.
     *
     * @return bool
     */
    public function save()
    {
        $this->processFile();

        return true;
    }

    /**
     * @return bool
     */
    public function processFile()
    {
        $flightJson = json_decode($this->file->get());

        $this->helper->newFlight($flightJson->uuid, $flightJson->aircraft_name, $flightJson->aircraft_sn);

        $batteryDetails = array();
        foreach ($flightJson->batteries as $batteryJson) {
            $batteryDetails[$batteryJson->battery_sn] = array(
                'battery_name' => $batteryJson->battery_name,
                'battery_sn' => $batteryJson->battery_sn
            );
        }

        $this->helper->setBatteryDetails($batteryDetails);

        foreach ($flightJson->frames as $frameJson) {
            $this->helper->saveNewFlightFrame($frameJson->type, $frameJson);
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isValidFile()
    {
        try {
            return !empty(json_decode($this->file->get()));
        } catch (FileNotFoundException $exception) {

        }

        return false;
    }
}
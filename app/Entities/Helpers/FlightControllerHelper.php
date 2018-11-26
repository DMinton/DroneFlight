<?php namespace App\Entities\Helpers;


use App\Entities\Models\Flight;
use App\Entities\Models\ModelFactory;
use Illuminate\Database\Eloquent\Collection;

class FlightControllerHelper
{
    /**
     * @var ModelFactory
     */
    protected $modelFactory;

    /**
     * @var GeoFormHelper
     */
    protected $geoFormHelper;

    /**
     * FlightControllerHelper constructor.
     * @param ModelFactory $modelFactory
     * @param GeoFormHelper $geoFormHelper
     */
    public function __construct(ModelFactory $modelFactory, GeoFormHelper $geoFormHelper)
    {
        $this->modelFactory = $modelFactory;
        $this->geoFormHelper = $geoFormHelper;
    }

    /**
     * @param $limit
     * @param $offset
     * @return \Illuminate\Support\Collection
     */
    public function list($limit, $offset)
    {
        $flights = $this->modelFactory->flight()
            ->select('id', 'aircraft_name', 'aircraft_sn')
            ->limit($limit)
            ->offset($offset)
            ->orderBy('id')
            ->get();

        $returnData = collect();
        foreach ($flights as $flight) {
            $batteriesCollection = $this->modelFactory->battery()
                ->select('battery_name', 'battery_sn')
                ->where('flight_id', $flight->id)
                ->groupBy('battery_sn')
                ->get();

            $timestampCollection = $this->modelFactory->gps()
                ->where('flight_id', $flight->id)
                ->orderBy('timestamp', 'asc')
                ->get();

            $returnData->push($this->getData($flight, $batteriesCollection, $timestampCollection, false));
        }

        return $returnData;
    }

    /**
     * @param Flight $flight
     * @param Collection $batteriesCollection
     * @param Collection $timestampCollection
     * @param bool $extended
     * @return array
     */
    private function getData($flight, $batteriesCollection, $timestampCollection, $extended)
    {
        $flightTime = 0;
        $homePoint = array();
        $flightPath = array();
        if ($timestampCollection->isNotEmpty()) {
            $flightTime = $this->geoFormHelper->setGps($timestampCollection)->getFlightTime();
            $homePoint = $this->geoFormHelper->toPoint();
            if ($extended) {
                $flightPath = $this->geoFormHelper->toMultiPoint();
            }
        }

        $batteries = array();
        $batteryTemperature = array();
        $batteryPercent = array();
        if ($batteriesCollection->isNotEmpty()) {
            foreach ($batteriesCollection as $battery) {
                $batteries[$battery->battery_sn] = array(
                    'battery_name' => $battery->battery_name,
                    'battery_sn' => $battery->battery_sn
                );

                if ($extended) {
                    $batteryTemperature[] = array(
                        'battery_temperature' => $battery->battery_temperature,
                        'timestamp' => $battery->timestamp
                    );

                    $batteryPercent[] = array(
                        'battery_percent' => $battery->battery_percent,
                        'timestamp' => $battery->timestamp
                    );
                }
            }
        }

        $returnData = array(
            'id' => $flight->id,
            'aircraft_name' => $flight->aircraft_name,
            'aircraft_sn' => $flight->aircraft_sn,
            'flight_duration' => $flightTime,
            'home_point' => $homePoint,
            'batteries' => array_values($batteries)
        );

        if ($extended) {
            $returnData = array_merge($returnData, array(
                'battery_temperature' => $batteryTemperature,
                'battery_percent' => $batteryPercent,
                'flight_path' => $flightPath
            ));
        }

        return $returnData;
    }

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function details($id)
    {
        $flight = $this->modelFactory->flight()
            ->where('id', $id)
            ->first();

        $returnData = collect();
        if (!empty($flight)) {
            $batteriesCollection = $this->modelFactory->battery()
                ->where('flight_id', $flight->id)
                ->orderBy('timestamp', 'asc')
                ->get();

            $timestampCollection = $this->modelFactory->gps()
                ->where('flight_id', $flight->id)
                ->orderBy('timestamp', 'asc')
                ->get();

            $returnData->push($this->getData($flight, $batteriesCollection, $timestampCollection, true));
        }

        return $returnData;
    }
}
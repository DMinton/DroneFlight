<?php namespace App\Entities\Helpers;

use Illuminate\Database\Eloquent\Collection;

class GeoFormHelper
{
    /**
     * @var Collection
     */
    protected $gps;

    /**
     * @return array
     */
    public function toMultiPoint()
    {
        $coordinates = $this->gps->map(function ($gps) {
            return array($gps->lat, $gps->long);
        })->toArray();

        return $this->form('MultiPoint', $coordinates);
    }

    /**
     * @param string $type
     * @param array $coordinates
     * @return array
     */
    private function form($type, array $coordinates)
    {
        return array(
            "type" => "Feature",
            "geometry" => [
                "type" => $type,
                "coordinates" => $coordinates
            ]
        );
    }

    /**
     * @return int
     */
    public function getFlightTime()
    {
        return $this->gps->last()->timestamp - $this->gps->first()->timestamp;
    }

    /**
     * @return array
     */
    public function toPoint()
    {
        return $this->form('Point', array($this->gps->first()->lat, $this->gps->first()->lat));
    }

    /**
     * @param Collection $gps
     * @return GeoFormHelper
     */
    public function setGps(Collection $gps)
    {
        $this->gps = $gps;
        return $this;
    }
}
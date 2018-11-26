<?php namespace App\Entities\Models;

use App;

class ModelFactory
{
    /**
     * @return Flight
     */
    public function flight()
    {
        return App::make(Flight::class);
    }

    /**
     * @return Gps
     */
    public function gps()
    {
        return App::make(Gps::class);
    }

    /**
     * @return Battery
     */
    public function battery()
    {
        return App::make(Battery::class);
    }
}
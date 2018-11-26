<?php namespace App\Http\Controllers\Flight;

use App\Entities\Helpers\FlightControllerHelper;
use App\Http\Controllers\Controller;

class FlightsController extends Controller
{
    /**
     * @var FlightControllerHelper
     */
    protected $helper;

    /**
     * FlightsController constructor.
     * @param FlightControllerHelper $flightControllerHelper
     */
    public function __construct(FlightControllerHelper $flightControllerHelper)
    {
        $this->helper = $flightControllerHelper;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function list()
    {
        $limit = 1000;
        if (request()->has('limit')) {
            $limit = request()->get('limit');
        }

        $offset = 0;
        if (request()->has('offset')) {
            $offset = request()->get('offset');
        }

        return $this->helper->list($limit, $offset);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Support\Collection
     */
    public function details($id)
    {
        $flightDetails = $this->helper->details($id);

        if ($flightDetails->isEmpty()) {
            return response()->json(array('Flight not found'), 404);
        }

        return $flightDetails;
    }
}
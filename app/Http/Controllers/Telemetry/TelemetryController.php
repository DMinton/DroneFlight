<?php namespace App\Http\Controllers\Telemetry;

use App\Entities\Telemetry\TelemetryFactory;
use App\Http\Controllers\Controller;

class TelemetryController extends Controller
{
    /**
     * @var TelemetryFactory
     */
    protected $telemetryFactory;

    /**
     * TelemetryController constructor.
     * @param TelemetryFactory $telemetryFactory
     */
    public function __construct(TelemetryFactory $telemetryFactory)
    {
        $this->telemetryFactory = $telemetryFactory;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function save()
    {
        // using a factory and abstract class since we can have many different telemetry files
        $telemetryObject = $this->telemetryFactory->setType(request()->get('type'))->get();

        if (!is_object($telemetryObject)) {
            return response()->json(array('Invalid telemetry type'), 400);
        }

        if (!request()->hasFile('file') || !$telemetryObject->setFile(request()->file('file'))->isValidFile()) {
            return response()->json(array('File is not valid'), 400);
        }

        if (!$telemetryObject->save()) {
            return response()->json(array('Failed to save file'), 400);
        }

        return response()->json();
    }
}
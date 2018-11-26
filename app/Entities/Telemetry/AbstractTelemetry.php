<?php namespace App\Entities\Telemetry;

use Illuminate\Http\UploadedFile;

abstract class AbstractTelemetry
{
    /**
     * @var UploadedFile
     */
    protected $file;

    /**
     * @param $file
     * @return $this
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return bool
     */
    abstract public function isValidFile();

    /**
     * @return bool
     */
    abstract public function save();

    /**
     * @return mixed
     */
    abstract public function list();

    /**
     * @return mixed
     */
    abstract public function details();
}
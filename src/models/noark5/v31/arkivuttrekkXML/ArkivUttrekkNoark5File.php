<?php

class ArkivUttrekkNoark5File
{
    protected $filename;
    protected $format;
    protected $checksumAlgorithm;
    protected $checksumValue;
    protected $schemas;

    // The following two are only applicable for arkivstruktur.xml
    protected $numberRegistrering;
    protected $numberMappe;

    public function __construct($filename)
    {
        $this->filename = $filename;
        $this->schemas = array();
    }

    public function create($filename, $format, $checksumAlgorithm, $checksumValue)
    {
        $this->filename = $filename;
        $this->format = $format;
        $this->checksumAlgorithm = $checksumAlgorithm;
        $this->checksumValue = $checksumValue;
        $this->schemas = null;
    }

    public function addSchema($filename, $format, $checksumAlgorithm, $checksumValue) {
        $this->schemas[] = new ArkivUttrekkFile($filename, $format, $checksumAlgorithm, $checksumValue);
    }


    public function getSchemas () {
        if (strcasecmp($this->format, Constants::XSDFILE) != 0) {
            return $this->schemas;
        }
        return null;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    public function getChecksumAlgorithm()
    {
        return $this->checksumAlgorithm;
    }

    public function setChecksumAlgorithm($checksumAlgorithm)
    {
        $this->checksumAlgorithm = $checksumAlgorithm;
        return $this;
    }

    public function getChecksumValue()
    {
        return $this->checksumValue;
    }

    public function setChecksumValue($checksumValue)
    {
        $this->checksumValue = $checksumValue;
        return $this;
    }

    public function getNumberRegistrering()
    {
        return $this->numberRegistrering;
    }

    public function setNumberRegistrering($numberRegistrering)
    {
        $this->numberRegistrering = $numberRegistrering;
        return $this;
    }

    public function getNumberMappe()
    {
        return $this->numberMappe;
    }

    public function setNumberMappe($numberMappe)
    {
        $this->numberMappe = $numberMappe;
        return $this;
    }




}
<?php


class ExtractionInfoXML
{
    protected $date;
    protected $method;
    protected $systemProducerInfo;

    function __construct() {
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function getSystemProducerInfo()
    {
        return $this->systemProducerInfo;
    }

    public function setSystemProducerInfo($systemProducerInfo)
    {
        $this->systemProducerInfo = $systemProducerInfo;
        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function __toString()
    {
        return 'date (' . $this->date . '), method (' . $this->method . '), systemProducerInfo (' . $this->systemProducerInfo . ')';
    }

}

?>
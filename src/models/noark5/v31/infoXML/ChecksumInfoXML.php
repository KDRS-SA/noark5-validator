<?php


class ChecksumInfoXML
{
    protected $filename;
    protected $checksumValue;
    protected $checksumAlgorithm;

    function __construct() {
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

    public function getChecksumValue()
    {
        return $this->checksumValue;
    }

    public function setChecksumValue($checksumValue)
    {
        $this->checksumValue = $checksumValue;
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

    public function __toString()
    {
        return 'filename (' . $this->filename . '), checksumValue (' . $this->checksumValue .'), checksumAlgorithm (' . $this->checksumAlgorithm . ')';
    }

}

?>
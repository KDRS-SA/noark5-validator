<?php

require_once 'models/noark5/v31/infoXML/ChecksumInfoXML.php';
require_once 'models/noark5/v31/infoXML/ExtractionInfoXML.php';
require_once 'models/noark5/v31/infoXML/FondsCreatorInfoXML.php';
require_once 'models/noark5/v31/infoXML/SystemInfoXML.php';

class InfoFileDetails
{
    protected $infoFileFilename;

    /**
     *
     * @var FondsCreatorInfoXML $fondsCreatorInfo:
     */
    protected $fondsCreatorInfo;

    /**
     *
     * @var ExtractionInfoXML $extractionInfo:
     */
    protected $extractionInfo;

    /**
     *
     * @var SystemInfoXML $systemInfo:
     */
    protected $systemInfo;

    /**
     *
     * @var ChecksumInfoXML $ChecksumInfo:
     */
    protected $checksumInfo;

    function __construct($infoFileFilename) {
        $this->infoFileFilename = $infoFileFilename;
        $this->fondsCreatorInfo = new FondsCreatorInfoXML();
        $this->checksumInfo = new ChecksumInfoXML();
        $this->extractionInfo = new ExtractionInfoXML();
        $this->systemInfo = new SystemInfoXML();
        $this->checksumInfo = new ChecksumInfoXML();
    }

    public function getInfoFilename()
    {
        return $this->infoFileFilename;
    }

    public function setInfoFilename($infoFileFilename)
    {
        $this->infoFileFilename = $infoFileFilename;
        return $this;
    }

    public function getChecksumArkivuttrekk()
    {
        return $this->checksumArkivuttrekk;
    }

    public function getFondsCreatorInfo()
    {
        return $this->fondsCreatorInfo;
    }

    public function getExtractionInfo()
    {
        return $this->extractionInfo;
    }

    public function getSystemInfo()
    {
        return $this->systemInfo;
    }

    public function getChecksumInfo()
    {
        return $this->checksumInfo;
    }

}

?>
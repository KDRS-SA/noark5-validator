<?php

require_once 'models/noark5/v31/infoXML/ChecksumInfo.php';
require_once 'models/noark5/v31/infoXML/ExtractionInfo.php';
require_once 'models/noark5/v31/infoXML/FondsCreatorInfo.php';
require_once 'models/noark5/v31/infoXML/System.php';


class InfoFileHandler
{

    protected $infoFile;
    protected $checksumArkivuttrekk;
    protected $fondsCreatorInfo;
    protected $extraction;
    protected $system;
    protected $checksumInfo;

    function __construct($infoFile) {
        $this->infoFile = $infoFile;
    }


    public function processInfofile() {

        $arkivUttrekk = simplexml_load_file($this->infoFile);

        $this->fondsCreatorInfo = new FondsCreatorInfo();

        $attributeObject = $arkivUttrekk->arkivskaperInfo->avleverendeMyndighet->attributes();
        if (isset($attributeObject['navn']) == true)
            $this->fondsCreatorInfo->setExtractionMyndighet($attributeObject['navn']);
        else
            $this->fondsCreatorInfo->setExtractionMyndighet(null);

        $attributeObject = $arkivUttrekk->arkivskaperInfo->kontaktperson->attributes();
        if (isset($attributeObject['navn']) == true)
            $this->fondsCreatorInfo->setContactPerson($attributeObject['navn']);
        else
            $this->fondsCreatorInfo->setContactPerson(null);

        $attributeObject = $arkivUttrekk->arkivskaperInfo->arkivskaper->attributes();
        if (isset($attributeObject['navn']) == true)
            $this->fondsCreatorInfo->setFondsCreator($attributeObject['navn']);
        else
            $this->fondsCreatorInfo->setFondsCreator(null);


        $this->extraction = new ExtractionInfo();
        $this->extraction->setDate($arkivUttrekk->uttrekk->uttrekksdato);
        $this->extraction->setMethod($arkivUttrekk->uttrekk->uttrekksmetode->metode);
        $attributeObject = $arkivUttrekk->uttrekk->produsentInfo->produsent->attributes();
        if (isset($attributeObject['navn']) == true)
            $this->extraction->setSystemProducerInfo($attributeObject['navn']);
        else
            $this->extraction->setSystemProducerInfo(null);


        $this->system = new System();
        $attributeObject = $arkivUttrekk->system->attributes();
        if (isset($attributeObject['systemType']) == true)
            $this->system->setSystemType($attributeObject['systemType']);
        else
            $this->system->setSystemType(null);

        if (isset($attributeObject['versjon']) == true)
            $this->system->setVersion($attributeObject['versjon']);
        else
            $this->system->setVersion(null);

        $this->system->setSystemName($arkivUttrekk->system->systemNavn);
        $this->system->setSystemVersion($arkivUttrekk->system->versjon);

        $this->checksumInfo = new ChecksumInfo();
        $this->checksumInfo->setFilename($arkivUttrekk->sjekksummer->filnavn);
        $this->checksumInfo->setChecksumValue($arkivUttrekk->sjekksummer->sjekksum);
        $this->checksumInfo->setChecksumAlgorithm($arkivUttrekk->sjekksummer->algoritme);

      //  print $this->system . PHP_EOL . $this->checksumInfo . PHP_EOL . $this->fondsCreatorInfo . PHP_EOL . $this->extraction . PHP_EOL;
    }

    public function getFondsCreatorInfo()
    {
        return $this->fondsCreatorInfo;
    }

    public function setFondsCreatorInfo($fondsCreatorInfo)
    {
        $this->fondsCreatorInfo = $fondsCreatorInfo;
        return $this;
    }

    public function getExtraction()
    {
        return $this->extraction;
    }

    public function setExtraction($extraction)
    {
        $this->extraction = $extraction;
        return $this;
    }

    public function getSystem()
    {
        return $this->system;
    }

    public function setSystem($system)
    {
        $this->system = $system;
        return $this;
    }

    public function getChecksumInfo()
    {
        return $this->checksumInfo;
    }

    public function setChecksumInfo($checksumInfo)
    {
        $this->checksumInfo = $checksumInfo;
        return $this;
    }

    public function getInfoFile()
    {
        return $this->infoFile;
    }

    public function setInfoFile($infoFile)
    {
        $this->infoFile = $infoFile;
        return $this;
    }


}

?>
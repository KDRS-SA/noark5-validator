<?php
require_once ('handler/InfoFileDetails.php');

class InfoFileHandler
{

    protected $infoFileDetails;

    function __construct($infoFilename) {
        $this->infoFileDetails = new InfoFileDetails($infoFilename);
    }

    public function processInfofile() {

        $arkivUttrekk = simplexml_load_file($this->infoFileDetails->getInfoFilename());

        $this->fondsCreatorInfo = $this->infoFileDetails->getFondsCreatorInfo();

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


        $this->extractionInfo = $this->infoFileDetails->getExtractionInfo();
        $this->extractionInfo->setDate($arkivUttrekk->uttrekk->uttrekksdato);
        $this->extractionInfo->setMethod($arkivUttrekk->uttrekk->uttrekksmetode->metode);
        $attributeObject = $arkivUttrekk->uttrekk->produsentInfo->produsent->attributes();
        if (isset($attributeObject['navn']) == true)
            $this->extractionInfo->setSystemProducerInfo($attributeObject['navn']);
        else
            $this->extractionInfo->setSystemProducerInfo(null);


        $this->systemInfo = $this->infoFileDetails->getSystemInfo();
        $attributeObject = $arkivUttrekk->system->attributes();
        if (isset($attributeObject['systemType']) == true)
            $this->systemInfo->setSystemType($attributeObject['systemType']);
        else
            $this->systemInfo->setSystemType(null);

        if (isset($attributeObject['versjon']) == true)
            $this->systemInfo->setVersion($attributeObject['versjon']);
        else
            $this->systemInfo->setVersion(null);

        $this->systemInfo->setSystemName($arkivUttrekk->system->systemNavn);
        $this->systemInfo->setSystemVersion($arkivUttrekk->system->versjon);

        $this->checksumInfo = $this->systemInfo = $this->infoFileDetails->getChecksumInfo();
        $this->checksumInfo->setFilename($arkivUttrekk->sjekksummer->filnavn);
        $this->checksumInfo->setChecksumValue($arkivUttrekk->sjekksummer->sjekksum);
        $this->checksumInfo->setChecksumAlgorithm($arkivUttrekk->sjekksummer->algoritme);
    }

    public function getInfoFileDetails()
    {
        return $this->infoFileDetails;
    }

}

?>
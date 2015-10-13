<?php

class FondsCreatorInfoXML
{
    protected $extractionMyndighet;
    protected $contactPerson;
    protected $fondsCreator;

    function __construct() {

    }

    public function getExtractionMyndighet()
    {
        return $this->extractionMyndighet;
    }

    public function setExtractionMyndighet($extractionMyndighet)
    {
        $this->extractionMyndighet = $extractionMyndighet;
        return $this;
    }

    public function getContactPerson()
    {
        return $this->contactPerson;
    }

    public function setContactPerson($contactPerson)
    {
        $this->contactPerson = $contactPerson;
        return $this;
    }

    public function getFondsCreator()
    {
        return $this->fondsCreator;
    }

    public function setFondsCreator($fondsCreator)
    {
        $this->fondsCreator = $fondsCreator;
        return $this;
    }

    public function __toString()
    {
        return 'extractionMyndighet (' . $this->extractionMyndighet . '), contactPerson (' . $this->contactPerson .'), fondsCreator (' . $this->fondsCreator . ')';
    }

}

?>
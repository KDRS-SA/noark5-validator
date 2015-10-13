<?php


class ArkivuttrekkExtractionInfo
{
    protected $inngaaendeSkille;
    protected $utgaaendeSkille;
    protected $extractionType;
    protected $inneholderSkjermetInformasjon;
    protected $omfatterDokumenterSomErKassert;
    protected $inneholderDokumenterSomSkalKasseres;
    protected $inneholderVirksomhetsspesifikkeMetadata;
    protected $antallDokumentfiler;
    protected $description;
    protected $type;
    protected $version;

    function __construct() {
    }


    /**
     * @return the $inngaaendeSkille
     */
    public function getInngaaendeSkille()
    {
        return $this->inngaaendeSkille;
    }

 /**
     * @return the $utgaaendeSkille
     */
    public function getUtgaaendeSkille()
    {
        return $this->utgaaendeSkille;
    }

 /**
     * @return the $systemProducerInfo
     */
    public function getSystemProducerInfo()
    {
        return $this->systemProducerInfo;
    }

 /**
     * @return the $inneholderSkjermetInformasjon
     */
    public function getInneholderSkjermetInformasjon()
    {
        return $this->inneholderSkjermetInformasjon;
    }

 /**
     * @return the $omfatterDokumenterSomErKassert
     */
    public function getOmfatterDokumenterSomErKassert()
    {
        return $this->omfatterDokumenterSomErKassert;
    }

 /**
     * @return the $inneholderDokumenterSomSkalKasseres
     */
    public function getInneholderDokumenterSomSkalKasseres()
    {
        return $this->inneholderDokumenterSomSkalKasseres;
    }

 /**
     * @return the $inneholderVirksomhetsspesifikkeMetadata
     */
    public function getInneholderVirksomhetsspesifikkeMetadata()
    {
        return $this->inneholderVirksomhetsspesifikkeMetadata;
    }

 /**
     * @return the $antallDokumentfiler
     */
    public function getAntallDokumentfiler()
    {
        return $this->antallDokumentfiler;
    }

 /**
     * @param field_type $inngaaendeSkille
     */
    public function setInngaaendeSkille($inngaaendeSkille)
    {
        $this->inngaaendeSkille = $inngaaendeSkille;
    }

 /**
     * @param field_type $utgaaendeSkille
     */
    public function setUtgaaendeSkille($utgaaendeSkille)
    {
        $this->utgaaendeSkille = $utgaaendeSkille;
    }

 /**
     * @param field_type $systemProducerInfo
     */
    public function setSystemProducerInfo($systemProducerInfo)
    {
        $this->systemProducerInfo = $systemProducerInfo;
    }

 /**
     * @param field_type $inneholderSkjermetInformasjon
     */
    public function setInneholderSkjermetInformasjon($inneholderSkjermetInformasjon)
    {
        $this->inneholderSkjermetInformasjon = $inneholderSkjermetInformasjon;
    }

 /**
     * @param field_type $omfatterDokumenterSomErKassert
     */
    public function setOmfatterDokumenterSomErKassert($omfatterDokumenterSomErKassert)
    {
        $this->omfatterDokumenterSomErKassert = $omfatterDokumenterSomErKassert;
    }

 /**
     * @param field_type $inneholderDokumenterSomSkalKasseres
     */
    public function setInneholderDokumenterSomSkalKasseres($inneholderDokumenterSomSkalKasseres)
    {
        $this->inneholderDokumenterSomSkalKasseres = $inneholderDokumenterSomSkalKasseres;
    }

 /**
     * @param field_type $inneholderVirksomhetsspesifikkeMetadata
     */
    public function setInneholderVirksomhetsspesifikkeMetadata($inneholderVirksomhetsspesifikkeMetadata)
    {
        $this->inneholderVirksomhetsspesifikkeMetadata = $inneholderVirksomhetsspesifikkeMetadata;
    }

 /**
     * @param field_type $antallDokumentfiler
     */
    public function setAntallDokumentfiler($antallDokumentfiler)
    {
        $this->antallDokumentfiler = $antallDokumentfiler;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    public function getExtractionType()
    {
        return $this->extractionType;
    }

    public function setExtractionType($extractionType)
    {
        $this->extractionType = $extractionType;
        return $this;
    }

    public function __toString()
    {
        return 'extractionType (' . $this->extractionType .'), inngaaendeSkille (' . $this->inngaaendeSkille . '), utgaaendeSkille (' . $this->utgaaendeSkille . '), inneholderSkjermetInformasjon (' . $this->printTrueFalse($this->inneholderSkjermetInformasjon) . '), ' .
            'omfatterDokumenterSomErKassert (' . $this->printTrueFalse($this->omfatterDokumenterSomErKassert) . '), inneholderDokumenterSomSkalKasseres (' . $this->printTrueFalse($this->inneholderDokumenterSomSkalKasseres) . '), inneholderVirksomhetsspesifikkeMetadata (' . $this->printTrueFalse($this->inneholderVirksomhetsspesifikkeMetadata) . ')' .
            'antallDokumentfiler (' . $this->antallDokumentfiler . '), version (' . $this->version . '), type (' . $this->type . '), description (' . $this->description . ')';
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    protected function  printTrueFalse($booleanValue) {
        if ($booleanValue == true)
            return 'true';
        else if ($booleanValue == false)
            return 'false';
        else return $booleanValue;
    }

}

?>
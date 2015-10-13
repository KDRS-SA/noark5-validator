<?php

class ArkivUttrekkDetails
{
    /**
     *
     * @var ArkivUttrekkNoark5File $arkivstruktur: Particular information about the file arkivstruktur.xml
     */
    protected $arkivstruktur;

    /**
     *
     * @var ArkivUttrekkNoark5File $offentligJournal: Particular information about the file offentligJournal.xml
     */
    protected $offentligJournal;


    /**
     *
     * @var ArkivUttrekkNoark5File $loependeJournal: Particular information about the file loependeJournal.xml
     */
    protected $loependeJournal;

    /**
     *
     * @var ArkivUttrekkNoark5File $endringslogg: Particular information about the file endringslogg.xml
     */
    protected $endringslogg;

     /**
     *
     * @var ArkivuttrekkExtractionInfo $extractionInfo: The top level information in arkivuttrekk.xml
     */
    protected $extractionInfo;

    function __construct() {}

    public function getArkivstruktur()
    {
        return $this->arkivstruktur;
    }

    public function setArkivstruktur($arkivstruktur)
    {
        $this->arkivstruktur = $arkivstruktur;
        return $this;
    }

    public function getOffentligJournal()
    {
        return $this->offentligJournal;
    }

    public function setOffentligJournal($offentligJournal)
    {
        $this->offentligJournal = $offentligJournal;
        return $this;
    }

    public function getLoependeJournal()
    {
        return $this->loependeJournal;
    }

    public function setLoependeJournal($loependeJournal)
    {
        $this->loependeJournal = $loependeJournal;
        return $this;
    }

    public function getEndringslogg()
    {
        return $this->endringslogg;
    }

    public function setEndringslogg($endringslogg)
    {
        $this->endringslogg = $endringslogg;
        return $this;
    }

    public function getExtractionInfo()
    {
        return $this->extractionInfo;
    }

    public function setExtractionInfo($extractionInfo)
    {
        $this->extractionInfo = $extractionInfo;
        return $this;
    }

}
?>
<?php
require_once 'models/noark5/v31/ArkivUttrekkFile.php';


class ArkivUttrekk
{
    protected $arkivstruktur;
    protected $offentligJournal;
    protected $loependeJournal;
    protected $endringslogg;
    protected $extractionInfo;

    function __construct() {
    }

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
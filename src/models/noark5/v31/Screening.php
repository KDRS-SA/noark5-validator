<?php

/** @Embeddable */
class Screening
{
    /** M500 - tilgangsrestriksjon n4 (JP.TGKODE) */
    /** @Column(name = "access_restriction", type = "string", nullable=true) **/
    protected $accessRestriction;

    /** M501 - skjermingshjemmel n4 (JP.UOFF) */
    /** @Column(name = "screening_authority", type = "string", nullable=true) **/
    protected $screeningAuthority;

    /** M502 - skjermingMetadata should be 1-M */
    /** @Column(name = "screened_metadata", type = "string", nullable=true) **/
    protected $screenedMetadata;

    /** M503 - skjermingDokument */
    /** @Column(name = "screened_document", type = "string", nullable=true) **/
    protected $screenedDocument;

    /** M505 - skjermingOpphoererDato n4(JP.AGDATO)*/
    /** @Column(name = "screening_expires", type = "datetime", nullable=true) **/
    protected $screenedExpires;

    /** M504 - skjermingsvarighet */
    /** @Column(name = "screening_duration", type = "string", nullable=true) **/
    protected $screenedDuration;

    function __construct()
    {}

    public function getAccessRestriction()
    {
        return $this->accessRestriction;
    }

    public function setAccessRestriction($accessRestriction)
    {
        $this->accessRestriction = $accessRestriction;
        return $this;
    }

    public function getScreeningAuthority()
    {
        return $this->screeningAuthority;
    }

    public function setScreeningAuthority($screeningAuthority)
    {
        $this->screeningAuthority = $screeningAuthority;
        return $this;
    }

    public function getScreenedMetadata()
    {
        return $this->screenedMetadata;
    }

    public function setScreenedMetadata($screenedMetadata)
    {
        $this->screenedMetadata = $screenedMetadata;
        return $this;
    }

    public function getScreenedDocument()
    {
        return $this->screenedDocument;
    }

    public function setScreenedDocument($screenedDocument)
    {
        $this->screenedDocument = $screenedDocument;
        return $this;
    }

    public function getScreenedExpires()
    {
        return $this->screenedExpires;
    }

    public function setScreenedExpires($screenedExpires)
    {
        $this->screenedExpires = $screenedExpires;
        return $this;
    }

    public function getScreenedDuration()
    {
        return $this->screenedDuration;
    }

    public function setScreenedDuration($screenedDuration)
    {
        $this->screenedDuration = $screenedDuration;
        return $this;
    }


}

?>
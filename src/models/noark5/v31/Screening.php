<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="screening")
 **/
class Screening
{
    /** @Id @Column(type="bigint", name="pk_screening_id", nullable=false) @GeneratedValue **/
    protected $id;

    /** M500 - tilgangsrestriksjon n4 (JP.TGKODE) */
    /** @Column(name = "access_restriction", type = "string", nullable=true) **/
    protected $accessRestriction;

    /** M501 - skjermingshjemmel n4 (JP.UOFF) */
    /** @Column(name = "screening_authority", type = "string", nullable=true) **/
    protected $screeningAuthority;

    /** M502 - skjermingMetadata should be 1-M */
    /** @Column(name = "screening_metadata", type = "string", nullable=true) **/
    protected $screeningMetadata;

    /** M503 - skjermingDokument */
    /** @Column(name = "screening_document", type = "string", nullable=true) **/
    protected $screeningDocument;

    /** M505 - skjermingOpphoererDato n4(JP.AGDATO)*/
    /** @Column(name = "screening_expires", type = "date", nullable=true) **/
    protected $screeningExpiresDate;

    /** M504 - skjermingsvarighet */
    /** @Column(name = "screening_duration", type = "string", nullable=true) **/
    protected $screeningDuration;

    // Links to Series
    /** @OneToMany(targetEntity="Series", mappedBy="referenceScreening", fetch="EXTRA_LAZY") **/
    protected $referenceSeries;

    // Links to Klass
    /** @OneToMany(targetEntity="Klass", mappedBy="referenceScreening", fetch="EXTRA_LAZY") **/
    protected $referenceKlass;

    // Links to File
    /** @OneToMany(targetEntity="File", mappedBy="referenceScreening", fetch="EXTRA_LAZY") **/
    protected $referenceFile;

    // Links to Record
    /** @OneToMany(targetEntity="Record", mappedBy="referenceScreening", fetch="EXTRA_LAZY") **/
    protected $referenceRecord;

    // Links to DocumentDescription
    /** @OneToMany(targetEntity="DocumentDescription", mappedBy="referenceScreening", fetch="EXTRA_LAZY") **/
    protected $referenceDocumentDescription;

    function __construct()
    {
        $this->referenceSeries = new ArrayCollection();
    }

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

    public function getScreeningMetadata()
    {
        return $this->screeningMetadata;
    }

    public function setScreeningMetadata($screeningMetadata)
    {
        $this->screeningMetadata = $screeningMetadata;
        return $this;
    }

    public function getScreeningDocument()
    {
        return $this->screeningDocument;
    }

    public function setScreeningDocument($screeningDocument)
    {
        $this->screeningDocument = $screeningDocument;
        return $this;
    }

    public function getScreeningExpiresDate()
    {
        return $this->screeningExpiresDate;
    }

    public function setScreeningExpiresDate($screeningExpiresDate)
    {
        $this->screeningExpiresDate = DateTime::createFromFormat(Constants::XSD_DATE_FORMAT, $screeningExpiresDate);
        return $this;
    }

    public function getScreeningDuration()
    {
        return $this->screeningDuration;
    }

    public function setScreeningDuration($screeningDuration)
    {
        $this->screeningDuration = $screeningDuration;
        return $this;
    }

    public function getReferenceSeries()
    {
        return $this->referenceSeries;
    }

    public function setReferenceSeries($referenceSeries)
    {
        $this->referenceSeries[] = $referenceSeries;
        $referenceSeries->setReferenceScreening($this);
        return $this;
    }

    public function getReferenceKlass()
    {
        return $this->referenceKlass;
    }

    public function setReferenceKlass($referenceKlass)
    {
        $this->referenceKlass = $referenceKlass;
        return $this;
    }

    public function getReferenceFile()
    {
        return $this->referenceFile;
    }

    public function setReferenceFile($referenceFile)
    {
        $this->referenceFile = $referenceFile;
        return $this;
    }

    public function __toString() {
        return 'Screening id' . $this->id . ' ' .   $this->accessRestriction . ' ' .   $this->screeningAuthority . ' ' .  $this->screeningMetadata;
    }

}

?>
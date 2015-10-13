<?php


/**
 * @Entity @Table(name="disposal")
 **/
class Disposal
{
    /** @Id @Column(type="bigint", name="pk_disposal_id", nullable=false) @GeneratedValue **/
    protected $id;

    /** M450 - kassasjonsvedtak (xs:string) */
    /** @Column(type="string", name = "disposal_decision", nullable=true) **/
    protected $disposalDecision;

    /** M453 - kassasjonshjemmel (xs:string) */
    /** @Column(type="string", name = "disposal_authority", nullable=true) **/
    protected $disposalAuthority;

    /** M451 - bevaringstid (xs:integer) */
    /** @Column(type="integer", name = "preservation_time", nullable=true) **/
    protected $preservationTime;

    /** M452 - kassasjonsdato (xs:date) */
    /** @Column(type="date", name = "disposal_date", nullable=true) **/
    protected $disposalDate;

    // Links to Series
    /** @OneToMany(targetEntity="Series", mappedBy="referenceDisposal", fetch="EXTRA_LAZY") **/
    protected $referenceSeries;

    // Links to Klass
    /** @OneToMany(targetEntity="Klass", mappedBy="referenceDisposal", fetch="EXTRA_LAZY") **/
    protected $referenceKlass;

    // Links to File
    /** @OneToMany(targetEntity="File", mappedBy="referenceDisposal", fetch="EXTRA_LAZY") **/
    protected $referenceFile;

    // Links to Record
    /** @OneToMany(targetEntity="Record", mappedBy="referenceDisposal", fetch="EXTRA_LAZY") **/
    protected $referenceRecord;

    // Links to DocumentDescription
    /** @OneToMany(targetEntity="DocumentDescription", mappedBy="referenceDisposal", fetch="EXTRA_LAZY") **/
    protected $referenceDocumentDescription;

    public function __construct(){}

    public function getId()
    {
        return $this->id;
    }

    public function getDisposalDecision()
    {
        return $this->disposalDecision;
    }

    public function setDisposalDecision($disposalDecision)
    {
        $this->disposalDecision = $disposalDecision;
        return $this;
    }

    public function getDisposalAuthority()
    {
        return $this->disposalAuthority;
    }

    public function setDisposalAuthority($disposalAuthority)
    {
        $this->disposalAuthority = $disposalAuthority;
        return $this;
    }

    public function getPreservationTime()
    {
        return $this->preservationTime;
    }

    public function setPreservationTime($preservationTime)
    {
        $this->preservationTime = $preservationTime;
        return $this;
    }

    public function getDisposalDate()
    {
        return $this->disposalDate;
    }

    public function setDisposalDate($disposalDate)
    {
        $this->disposalDate = DateTime::createFromFormat(Constants::XSD_DATE_FORMAT, $disposalDate);
        return $this;
    }

    public function getReferenceSeries()
    {
        return $this->referenceSeries;
    }

    public function setReferenceSeries($referenceSeries)
    {
        $this->referenceSeries = $referenceSeries;
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

    public function getReferenceRecord()
    {
        return $this->referenceRecord;
    }

    public function setReferenceRecord($referenceRecord)
    {
        $this->referenceRecord = $referenceRecord;
        return $this;
    }

    public function getReferenceDocumentDescription()
    {
        return $this->referenceDocumentDescription;
    }

    public function setReferenceDocumentDescription($referenceDocumentDescription)
    {
        $this->referenceDocumentDescription = $referenceDocumentDescription;
        return $this;
    }

    public function __toString() {

        return ' id[' . $this->id. '],' .
        ' disposalDecision[' . $this->disposalDecision. '],' .
        ' disposalAuthority[' . $this->disposalAuthority. '],' .
        ' preservationTime[' . $this->preservationTime. '],' .
        ' disposalDate[' . $this->disposalDate. '],' .
        ' referenceSeries[' . $this->referenceSeries. ']';

    }
}

?>
<?php


/**
 * @Entity @Table(name="disposal_undertaken")
 **/
class DisposalUndertaken
{
    /** @Id @Column(type="bigint", name="pk_disposal_undertaken_id", nullable=false) @GeneratedValue **/
    protected $id;

    /** M631 - kassertAv (xs:string) */
    /** @Column(type="string", name = "disposal_by", nullable=true) **/
    protected $disposalBy;

    /** M630 - kassertDato (xs:dateTime) */
    /** @Column(type="datetime", name = "disposal_date", nullable=true) **/
    protected $disposalDate;

    // Links to Series
    /** @OneToMany(targetEntity="Series", mappedBy="referenceDisposalUndertaken", fetch="EXTRA_LAZY") **/
    protected $referenceSeries;

    // Links to DocumentDescription
    /** @OneToMany(targetEntity="DocumentDescription", mappedBy="referenceDisposalUndertaken", fetch="EXTRA_LAZY") **/
    protected $referenceDocumentDescription;

    public function __construct(){}

    public function getId()
    {
        return $this->id;
    }

    public function getDisposalBy()
    {
        return $this->disposalBy;
    }

    public function setDisposalBy($disposalBy)
    {
        $this->disposalBy = $disposalBy;
        return $this;
    }

    public function getDisposalDate()
    {
        return $this->disposalDate;
    }

    public function setDisposalDate($disposalDate)
    {
        $this->disposalDate = DateTime::createFromFormat(Constants::XSD_DATETIME_FORMAT, $disposalDate);
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
        return  ' id[' . $this->id. '],' .
                ' disposalBy[' . $this->disposalBy. '],' .
                ' disposalDate[' . $this->disposalDate. '],' .
                ' referenceSeries[' . $this->referenceSeries. '],' .
                ' referenceDocumentDescription[' . $this->referenceDocumentDescription. ']';
    }
}

?>
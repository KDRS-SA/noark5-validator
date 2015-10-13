<?php

use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity @Table(name="precedence")
 **/
class Precedence
{
    /** @Id @Column(type="bigint", name="pk_precedence", nullable=false) @GeneratedValue **/
    protected $id;

    /** M111 - presedensDato (xs:date) */
    /** @Column(type="date", name="precedence_date", nullable=true) **/
    protected $precedenceDate;

    /** M600 - opprettetDato (xs:dateTime) */
    /** @Column(type="datetime", name="created_date", nullable=true) **/
    protected $createdDate;

    /** M602 - opprettetAv (xs:string) */
    /** @Column(type="string", name="created_by", nullable=true) **/
    protected $createdBy;

    /** M020 - tittel (xs:string) */
    /** @Column(type="string", name="title", nullable=true) **/
    protected $title;

    /** M021 - beskrivelse (xs:string) */
    /** @Column(type="string", name="description", nullable=true) **/
    protected $description;

    /** M311 - presedensHjemmel (xs:string) */
    /** @Column(type="string", name="precedence_authority", nullable=true) **/
    protected $precedenceAuthority;

    /** M312 - rettskildefaktor (xs:string) */
    /** @Column(type="string", name="source_of_law", nullable=true) **/
    protected $sourceOfLaw;

    /** M628 - presedensGodkjentDato (xs:date) */
    /** @Column(type="date", name="precedence_approved_date", nullable=true) **/
    protected $precedenceApprovedDate;

    /** M629 - presedensGodkjentAv (xs:string) */
    /** @Column(type="string", name = "precedence_approved_by", nullable=true) **/
    protected $precedenceApprovedBy;

    /** M602 avsluttetDato (xs:dateTime) */
    /** @Column(type="date", name = "finalised_date", nullable=true) **/
    protected $finalisedDate;

    /** M603 - avsluttetAv (xs:string) */
    /** @Column(type="string", name = "finalised_by", nullable=true) **/
    protected $finalisedBy;

    /** M056 - presedensStatus (xs:string) */
    /** @Column(type="string", name = "precedence_status", nullable=true) **/
    protected $precedenceStatus;

    // Link to RegistryEntry
    /** @ManyToMany(targetEntity="RegistryEntry", mappedBy="referencePrecedence") **/
    protected $referenceRegistryEntry;

    // Link to CaseFile
    /** @ManyToMany(targetEntity="CaseFile", mappedBy="referencePrecedence") **/
    protected $referenceCaseFile;

    public function __construct()
    {
        $this->referenceRegistryEntry = new ArrayCollection();
        $this->referenceCaseFile = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPrecedenceDate()
    {
        return $this->precedenceDate;
    }

    public function setPrecedenceDate($precedenceDate)
    {
        $this->precedenceDate = DateTime::createFromFormat(Constants::XSD_DATE_FORMAT, $precedenceDate);
        return $this;
    }

    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    public function setCreatedDate($createdDate)
    {
        $this->createdDate = DateTime::createFromFormat(Constants::XSD_DATETIME_FORMAT, $createdDate);
        return $this;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
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

    public function getPrecedenceAuthority()
    {
        return $this->precedenceAuthority;
    }

    public function setPrecedenceAuthority($precedenceAuthority)
    {
        $this->precedenceAuthority = $precedenceAuthority;
        return $this;
    }

    public function getSourceOfLaw()
    {
        return $this->sourceOfLaw;
    }

    public function setSourceOfLaw($sourceOfLaw)
    {
        $this->sourceOfLaw = $sourceOfLaw;
        return $this;
    }

    public function getPrecedenceApprovedDate()
    {
        return $this->precedenceApprovedDate;
    }

    public function setPrecedenceApprovedDate($precedenceApprovedDate)
    {
        $this->precedenceApprovedDate = DateTime::createFromFormat(Constants::XSD_DATE_FORMAT, $precedenceApprovedDate);
        return $this;
    }

    public function getPrecedenceApprovedBy()
    {
        return $this->precedenceApprovedBy;
    }

    public function setPrecedenceApprovedBy($precedenceApprovedBy)
    {
        $this->precedenceApprovedBy = $precedenceApprovedBy;
        return $this;
    }

    public function getFinalisedDate()
    {
        return $this->finalisedDate;
    }

    public function setFinalisedDate($finalisedDate)
    {
        $this->finalisedDate = DateTime::createFromFormat(Constants::XSD_DATETIME_FORMAT, $finalisedDate);
        return $this;
    }

    public function getFinalisedBy()
    {
        return $this->finalisedBy;
    }

    public function setFinalisedBy($finalisedBy)
    {
        $this->finalisedBy = $finalisedBy;
        return $this;
    }

    public function getPrecedenceStatus()
    {
        return $this->precedenceStatus;
    }

    public function setPrecedenceStatus($precedenceStatus)
    {
        $this->precedenceStatus = $precedenceStatus;
        return $this;
    }

    public function getReferenceRegistryEntry()
    {
        return $this->referenceRegistryEntry;
    }

    public function setReferenceRegistryEntry($referenceRegistryEntry)
    {
        $this->referenceRegistryEntry = $referenceRegistryEntry;
        return $this;
    }

    public function addReferenceRegistryEntry($registryEntry)
    {
        if ($this->referenceRegistryEntry->contains($registryEntry)) {
            return;
        }
        $this->referenceRegistryEntry[] = $registryEntry;
        return $this;
    }

    public function getReferenceCaseFile()
    {
        return $this->referenceCaseFile;
    }

    public function setReferenceCaseFile($referenceCaseFile)
    {
        $this->referenceCaseFile = $referenceCaseFile;
        return $this;
    }

    public function addReferenceCaseFile($caseFile)
    {
        if ($this->referenceCaseFile->contains($caseFile)) {
            return;
        }
        $this->referenceCaseFile[] = $caseFile;
        return $this;
    }

    public function __toString() {
        return
        ' id[' . $this->id. '],' .
        ' precedenceDate[' . ($this->precedenceDate == null ? null : $this->precedenceDate->format(Constants::XSD_DATE_FORMAT)) . '],' .
        ' createdDate[' . ($this->createdDate == null ? null : $this->createdDate->format(Constants::XSD_DATETIME_FORMAT)) . '],' .
        ' createdBy [' . $this->createdBy. '],' .
        ' title[' . $this->title. '],' .
        ' description[' . $this->description. '],' .
        ' precedenceAuthority[' . $this->precedenceAuthority. '],' .
        ' sourceOfLaw[' . $this->sourceOfLaw. '],' .
        ' precedenceApprovedDate[' . ($this->precedenceApprovedDate == null ? null : $this->precedenceApprovedDate->format(Constants::XSD_DATETIME_FORMAT)) . '],' .
        ' precedenceApprovedBy[' . $this->precedenceApprovedBy. '],' .
        ' finalisedDate[' . ($this->finalisedDate == null ? null : $this->finalisedDate->format(Constants::XSD_DATE_FORMAT)) . '],' .
        ' finalisedBy[' . $this->finalisedBy. '],' .
        ' precedenceStatus[' . $this->precedenceStatus. ']';
    }
}

?>
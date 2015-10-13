<?php

use Doctrine\Common\Collections\ArrayCollection;
require_once ('models/noark5/v31/BasicRecord.php');
require_once ('models/noark5/v31/Record.php');
require_once ('models/noark5/v31/CorrespondencePart.php');
require_once ('models/noark5/v31/SignOff.php');

/**
 * @Entity @Table(name="registry_entry")
 **/
class RegistryEntry extends BasicRecord
{

    /** M013 - journalaar (xs:integer) */
    /** @Column(type="integer", name = "record_year", nullable=true) **/
    protected $recordYear;

    /** M014 - journalsekvensnummer (xs:integer) */
    /** @Column(type="integer", name = "record_sequence_number", nullable=true) **/
    protected $recordSequenceNumber;

    /** M015 - journalpostnummer (xs:integer) */
    /** @Column(type="integer", name = "registry_entry_number", nullable=true) **/
    protected $registryEntryNumber;

    /** M082 - journalposttype (xs:string) */
    /** @Column(type="string", name = "registry_entry_type", nullable=true) **/
    protected $registryEntryType;

    /** M053 - journalstatus (xs:string) */
    /** @Column(type="string", name = "record_status", nullable=true) **/
    protected $recordStatus;

    /** M101 - journaldato (xs:date) */
    /** @Column(type="date", name = "record_date", nullable=true) **/
    protected $recordDate;

    /** M103 - dokumentetsDato (xs:date) */
    /** @Column(type="date", name = "document_date", nullable=true) **/
    protected $documentDate;

    /** M104 - mottattDato (xs:dateTime) */
    /** @Column(type="datetime", name = "received_date", nullable=true) **/
    protected $receivedDate;

    /** M105 - sendtDato (xs:dateTime) */
    /** @Column(type="datetime", name = "sent_date", nullable=true) **/
    protected $sentDate;

    /** M109 - forfallsdato (xs:date) */
    /** @Column(type="date", name = "due_date", nullable=true) **/
    protected $dueDate;

    /** M110 - offentlighetsvurdertDato (xs:date) */
    /** @Column(type="date", name = "freedom_assessment_date", nullable=true) **/
    protected $freedomAssessmentDate;

    /** M304 - antallVedlegg (xs:integer) */
    /** @Column(type="integer", name = "number_of_attachments", nullable=true) **/
    protected $numberOfAttachments;

    /** M106 - utlaantDato (xs:date) */
    /** @Column(type="date", name = "loaned_date", nullable=true) **/
    protected $loanedDate;

    /** M309 - utlaantTil (xs:string) */
    /** @Column(type="date", name = "loaned_to", nullable=true) **/
    protected $loanedTo;

    /** M308 - journalenhet (xs:string) */
    /** @Column(type="string", name = "records_management_unit", nullable=true) **/
    protected $recordsManagementUnit;

    // Links to CorrespondenceParts
    /** @ManyToMany(targetEntity="CorrespondencePart")
     *   @JoinTable(name="record_correspondence_part",
     *        joinColumns=@JoinColumn(
     *        name="f_pk_record_id",
     *        referencedColumnName="pk_record_id"),
     *    inverseJoinColumns=@JoinColumn(
     *        name="f_pk_correspondence_part_id",
     *        referencedColumnName="pk_correspondence_part_id"))
     * */
    protected $referenceCorrespondencePart;


    // Links to SignOff
    /** @ManyToMany(targetEntity="SignOff", fetch="EXTRA_LAZY")
     *   @JoinTable(name="record_sign_off",
     *        joinColumns=@JoinColumn(
     *        name="f_pk_record_id",
     *        referencedColumnName="pk_record_id"),
     *    inverseJoinColumns=@JoinColumn(
     *        name="f_pk_sign_off_id",
     *        referencedColumnName="pk_sign_off_id"))
     * */
    protected $referenceSignOff;

    /** @ManyToMany(targetEntity="Precedence", fetch="EXTRA_LAZY")
     *   @JoinTable(name="registr_entry_precedence",
     *        joinColumns=@JoinColumn(
     *        name="f_pk_record_id",
     *        referencedColumnName="pk_record_id"),
     *    inverseJoinColumns=@JoinColumn(
     *        name="f_pk_precedence",
     *        referencedColumnName="pk_precedence"))
     **/
    protected $referencePrecedence;

    // Links to Workflow
    /** @OneToMany(targetEntity="Workflow", mappedBy="referenceRegistryEntry", cascade={"persist", "remove"}) **/
    protected $referenceWorkflow;

    public function __construct()
    {
        $this->referenceCorrespondencePart = new ArrayCollection();
        $this->referenceSignOff = new ArrayCollection();
        $this->referencePrecedence = new ArrayCollection();
        $this->referenceWorkflow = new ArrayCollection();
    }

    public function getRecordYear()
    {
        return $this->recordYear;
    }

    public function setRecordYear($recordYear)
    {
        $this->recordYear = $recordYear;
        return $this;
    }

    public function getRecordSequenceNumber()
    {
        return $this->recordSequenceNumber;
    }

    public function setRecordSequenceNumber($recordSequenceNumber)
    {
        $this->recordSequenceNumber = $recordSequenceNumber;
        return $this;
    }

    public function getRegistryEntryNumber()
    {
        return $this->registryEntryNumber;
    }

    public function setRegistryEntryNumber($registryEntryNumber)
    {
        $this->registryEntryNumber = $registryEntryNumber;
        return $this;
    }

    public function getRegistryEntryType()
    {
        return $this->registryEntryType;
    }

    public function setRegistryEntryType($registryEntryType)
    {
        $this->registryEntryType = $registryEntryType;
        return $this;
    }

    public function getRecordStatus()
    {
        return $this->recordStatus;
    }

    public function setRecordStatus($recordStatus)
    {
        $this->recordStatus = $recordStatus;
        return $this;
    }

    public function getRecordDate()
    {
        return $this->recordDate;
    }

    public function setRecordDate($recordDate)
    {
           // have to convert from string object to datetime object
        $this->recordDate = DateTime::createFromFormat(Constants::XSD_DATE_FORMAT, $recordDate);
        return $this;
    }

    public function getDocumentDate()
    {
        return $this->documentDate;
    }

    public function setDocumentDate($documentDate)
    {
            // have to convert from string object to datetime object
        $this->documentDate = DateTime::createFromFormat(Constants::XSD_DATE_FORMAT, $documentDate);
        return $this;
    }

    public function getReceivedDate()
    {
        return $this->receivedDate;
    }

    public function setReceivedDate($receivedDate)
    {
            // have to convert from string object to datetime object
        $this->receivedDate = DateTime::createFromFormat(Constants::XSD_DATETIME_FORMAT, $receivedDate);
        return $this;
    }

    public function getSentDate()
    {
        return $this->sentDate;
    }

    public function setSentDate($sentDate)
    {
        // have to convert from string object to datetime object
        $this->sentDate = DateTime::createFromFormat(Constants::XSD_DATETIME_FORMAT, $sentDate);
        return $this;
    }

    public function getDueDate()
    {
        return $this->dueDate;
    }

    public function setDueDate($dueDate)
    {
        // have to convert from string object to datetime object
        $this->dueDate = DateTime::createFromFormat(Constants::XSD_DATE_FORMAT, $dueDate);
        return $this;
    }

    public function getFreedomAssessmentDate()
    {
        return $this->freedomAssessmentDate;
    }

    public function setFreedomAssessmentDate($freedomAssessmentDate)
    {
            // have to convert from string object to datetime object
        $this->freedomAssessmentDate = DateTime::createFromFormat(Constants::XSD_DATETIME_FORMAT, $freedomAssessmentDate);
        return $this;
    }

    public function getNumberOfAttachments()
    {
        return $this->numberOfAttachments;
    }

    public function setNumberOfAttachments($numberOfAttachments)
    {
        $this->numberOfAttachments = $numberOfAttachments;
        return $this;
    }

    public function getLoanedDate()
    {
        return $this->loanedDate;
    }

    public function setLoanedDate($loanedDate)
    {
            // have to convert from string object to datetime object
        $this->loanedDate = DateTime::createFromFormat(Constants::XSD_DATE_FORMAT, $loanedDate);
        return $this;
    }

    public function getLoanedTo()
    {
        return $this->loanedTo;
    }

    public function setLoanedTo($loanedTo)
    {
        $this->loanedTo = $loanedTo;
        return $this;
    }

    public function getRecordsManagementUnit()
    {
        return $this->recordsManagementUnit;
    }

    public function setRecordsManagementUnit($recordsManagementUnit)
    {
        $this->recordsManagementUnit = $recordsManagementUnit;
        return $this;
    }

    public function getReferenceCorrespondencePart()
    {
        return $this->referenceCorrespondencePart;
    }

    public function setReferenceCorrespondencePart($referenceCorrespondencePart)
    {
        $this->referenceCorrespondencePart = $referenceCorrespondencePart;
        return $this;
    }

    public function addCorrespondencePart($correspondencePart)
    {
        $this->referenceCorrespondencePart[] = $correspondencePart;
        $correspondencePart->addRecord($this);
        return $this;
    }

    public function getReferenceSignOff()
    {
        return $this->referenceSignOff;
    }

    public function setReferenceSignOff($referenceSignOff)
    {
        $this->referenceSignOff = $referenceSignOff;
        $referenceSignOff->addReferenceRecord($this);
        return $this;
    }

    public function addSignOff($signOff)
    {
        $this->referenceSignOff[] = $signOff;
        return $this;
    }

    public function getReferencePrecedence()
    {
        return $this->referencePrecedence;
    }

    public function setReferencePrecedence($referencePrecedence)
    {
        $this->referencePrecedence = $referencePrecedence;
        return $this;
    }

    public function addReferencePrecedence($precedence)
    {
        if ($this->referencePrecedence->contains($precedence)) {
            return;
        }
        $this->referencePrecedence[] = $precedence;
        $precedence->addReferenceRegistryEntry($this);
        return $this;
    }
}

?>
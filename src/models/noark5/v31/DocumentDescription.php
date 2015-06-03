<?php

use Doctrine\Common\Collections\ArrayCollection;
require_once ('models/noark5/v31/DocumentObject.php');
require_once ('models/noark5/v31/Author.php');

/**
 * @Entity @Table(name="document_description")
 **/
class DocumentDescription
{
    /** @Id @Column(type="bigint", name="pk_document_description_id", nullable=false) @GeneratedValue **/
    protected $id;

    /** M001 - systemID (xs:string) */
    /** @Column(type="string", name = "system_id", nullable=true) **/
    protected $systemId;

    /** M083 - dokumenttype (xs:string) */
    /** @Column(type="string", name = "document_type", nullable=true) **/
    protected $documentType;

    /** M054 - dokumentstatus (xs:string) */
    /** @Column(type="string", name = "document_status", nullable=true) **/
    protected $documentStatus;

    /** M020 - tittel (xs:string) */
    /** @Column(type="string", name = "title", nullable=true) **/
    protected $title;

    /** M021 - beskrivelse (xs:string) */
    /** @Column(type="string", name = "description", nullable=true) **/
    protected $description;

    /** M600 - opprettetDato (xs:dateTime) */
    /** @Column(type="datetime", name = "created_date", nullable=true) **/
    protected $createdDate;

    /** M601 - opprettetAv (xs:string) */
    /** @Column(type="string", name = "created_by", nullable=true) **/
    protected $createdBy;

    /** M300 - dokumentmedium (xs:string) */
    /** @Column(type="string", name = "document_medium", nullable=true) **/
    protected $documentMedium;

    /** M217 - tilknyttetRegistreringSom (xs:string) */
    /** @Column(type="string", name = "associated_with_record_as", nullable=true) **/
    protected $associatedWithRecordAs;

    /** M007 - dokumentnummer (xs:integer) */
    /** @Column(type="string", name = "document_number", nullable=true) **/
    protected $documentNumber;

    /** M620 - tilknyttetDato (xs:datetime) */
    /** @Column(type="datetime", name = "association_date", nullable=true) **/
    protected $associationDate;

    /** M621 - tilknyttetAv (xs:string) */
    /** @Column(type="string", name = "associated_by", nullable=true) **/
    protected $associatedBy;

    // Links to Records
    /** @ManyToMany(targetEntity="Record", mappedBy="referenceDocumentDescription") **/
    protected $referenceRecord;

    // Links to DocumentObjects
    /** @OneToMany(targetEntity="DocumentObject", mappedBy = "referenceDocumentDescription", cascade={"persist", "remove"}) **/
    protected $referenceDocumentObject;

    // Links to Authors
    /**
     * @ManyToMany(targetEntity="Author", inversedBy="referenceDocumentDescription", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     *       @JoinTable(name="document_description_author",
     *           joinColumns={@JoinColumn(name="f_pk_document_description_id", referencedColumnName="pk_document_description_id")},
     *            inverseJoinColumns={@JoinColumn(name="f_pk_author_id", referencedColumnName="pk_author_id")}
     *      )
     **/
     protected $referenceAuthor;

    /** @Embedded(class = "Screening") */
    protected $screening;

    public function __construct()
    {
        $this->referenceAuthor = new ArrayCollection();
        $this->referenceRecord= new ArrayCollection();
        $this->referenceDocumentObject= new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSystemId()
    {
        return $this->systemId;
    }

    public function setSystemId($systemId)
    {
        $this->systemId = $systemId;
        return $this;
    }

    public function getDocumentType()
    {
        return $this->documentType;
    }

    public function setDocumentType($documentType)
    {
        $this->documentType = $documentType;
        return $this;
    }

    public function getDocumentStatus()
    {
        return $this->documentStatus;
    }

    public function setDocumentStatus($documentStatus)
    {
        $this->documentStatus = $documentStatus;
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

    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    public function setCreatedDate($createdDate)
    {
        // have to convert from string object to datetime object
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

    public function getDocumentMedium()
    {
        return $this->documentMedium;
    }

    public function setDocumentMedium($documentMedium)
    {
        $this->documentMedium = $documentMedium;
        return $this;
    }

    public function getAssociatedWithRecordAs()
    {
        return $this->associatedWithRecordAs;
    }

    public function setAssociatedWithRecordAs($associatedWithRecordAs)
    {
        $this->associatedWithRecordAs = $associatedWithRecordAs;
        return $this;
    }

    public function getDocumentNumber()
    {
        return $this->documentNumber;
    }

    public function setDocumentNumber($documentNumber)
    {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    public function getAssociationDate()
    {
        return $this->associationDate;
    }

    public function setAssociationDate($associationDate)
    {
        // have to convert from string object to datetime object
        $this->associationDate  = DateTime::createFromFormat(Constants::XSD_DATETIME_FORMAT, $associationDate);
        return $this;
    }

    public function getAssociatedBy()
    {
        return $this->associatedBy;
    }

    public function setAssociatedBy($associatedBy)
    {
        $this->associatedBy = $associatedBy;
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

    public function addReferenceRecord($record)
    {
        $this->referenceRecord[] = $record;
        return $this;
    }

    public function getReferenceDocumentObject()
    {
        return $this->referenceDocumentObject;
    }

    public function setReferenceDocumentObject($referenceDocumentObject)
    {
        $this->referenceDocumentObject = $referenceDocumentObject;
        return $this;
    }

    public function addReferenceDocumentObject($documentObject)
    {
        $this->referenceDocumentObject[] = $documentObject;
        return $this;
    }


    public function getReferenceAuthor()
    {
        return $this->referenceAuthor;
    }

    public function setReferenceAuthor($referenceAuthor)
    {
        $this->referenceAuthor = $referenceAuthor;
        return $this;
    }

    public function addAuthor($author)
    {
        $this->referenceAuthor[] = $author;
        return $this;
    }


    public function getScreening()
    {
        return $this->screening;
    }

    public function setScreening($screening)
    {
        $this->screening = $screening;
        return $this;
    }

    public function __toString()
    {
        return __METHOD__ . " id[" . $this->id . "], " . "systemId[" . $this->systemId . "]";
    }

}

?>
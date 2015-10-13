<?php
use Doctrine\Common\Collections\ArrayCollection;
require_once ('models/noark5/v31/DocumentObject.php');
require_once ('models/noark5/v31/Author.php');

/**
 * @Entity @Table(name="document_description")
 */
class DocumentDescription
{

    /**
     * @Id @Column(type="bigint", name="pk_document_description_id", nullable=false) @GeneratedValue *
     */
    protected $id;

    /**
     * M001 - systemID (xs:string)
     */
    /**
     * @Column(type="string", name = "system_id", nullable=true) *
     */
    protected $systemId;

    /**
     * M083 - dokumenttype (xs:string)
     */
    /**
     * @Column(type="string", name = "document_type", nullable=true) *
     */
    protected $documentType;

    /**
     * M054 - dokumentstatus (xs:string)
     */
    /**
     * @Column(type="string", name = "document_status", nullable=true) *
     */
    protected $documentStatus;

    /**
     * M020 - tittel (xs:string)
     */
    /**
     * @Column(type="string", name = "title", nullable=true) *
     */
    protected $title;

    /**
     * M021 - beskrivelse (xs:string)
     */
    /**
     * @Column(type="string", name = "description", nullable=true) *
     */
    protected $description;

    /**
     * M600 - opprettetDato (xs:dateTime)
     */
    /**
     * @Column(type="datetime", name = "created_date", nullable=true) *
     */
    protected $createdDate;

    /**
     * M601 - opprettetAv (xs:string)
     */
    /**
     * @Column(type="string", name = "created_by", nullable=true) *
     */
    protected $createdBy;

    /**
     * M300 - dokumentmedium (xs:string)
     */
    /**
     * @Column(type="string", name = "document_medium", nullable=true) *
     */
    protected $documentMedium;

    /**
     * M217 - tilknyttetRegistreringSom (xs:string)
     */
    /**
     * @Column(type="string", name = "associated_with_record_as", nullable=true) *
     */
    protected $associatedWithRecordAs;

    /**
     * M007 - dokumentnummer (xs:integer)
     */
    /**
     * @Column(type="string", name = "document_number", nullable=true) *
     */
    protected $documentNumber;

    /**
     * M620 - tilknyttetDato (xs:datetime)
     */
    /**
     * @Column(type="datetime", name = "association_date", nullable=true) *
     */
    protected $associationDate;

    /**
     * M621 - tilknyttetAv (xs:string)
     */
    /**
     * @Column(type="string", name = "associated_by", nullable=true) *
     */
    protected $associatedBy;

    // Links to Records
    /**
     * @ManyToMany(targetEntity="Record", mappedBy="referenceDocumentDescription") *
     */
    protected $referenceRecord;

    // Links to DocumentObjects
    /**
     * @OneToMany(targetEntity="DocumentObject", mappedBy = "referenceDocumentDescription", cascade={"persist", "remove"}) *
     */
    protected $referenceDocumentObject;

    // Links to Authors
    /**
     * @ManyToMany(targetEntity="Author", inversedBy="referenceDocumentDescription", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @JoinTable(name="document_description_author",
     * joinColumns={@JoinColumn(name="f_pk_document_description_id", referencedColumnName="pk_document_description_id")},
     * inverseJoinColumns={@JoinColumn(name="f_pk_author_id", referencedColumnName="pk_author_id")}
     * )
     */
    protected $referenceAuthor;

    // Links to StorageLocation
    /**
     * @ManyToOne(targetEntity="StorageLocation")
     * @JoinColumn(name = "document_description_storage_location_id", referencedColumnName = "pk_storage_location_id")
     */
    protected $referenceStorageLocation;

    /**
     * @OneToOne(targetEntity="ElectronicSignature", mappedBy="referenceDocumentDescription")
     */
    protected $referenceElectronicSignature;

    // Link to Screening
    /**
     * @ManyToOne(targetEntity="Screening", fetch="EXTRA_LAZY")
     * @JoinColumn(name="class_screening_id",
     * referencedColumnName="pk_screening_id")
     */
    protected $referenceScreening;

    // Link to Classified
    /**
     * @ManyToOne(targetEntity="Classified", fetch="EXTRA_LAZY")
     * @JoinColumn(name="document_description_classified_id",
     * referencedColumnName="pk_classified_id")
     */
    protected $referenceClassified;

    // Link to DisposalUndertaken
    /**
     * @ManyToOne(targetEntity="DisposalUndertaken", fetch="EXTRA_LAZY")
     * @JoinColumn(name="document_description_disposal_undertaken_id",
     * referencedColumnName="pk_disposal_undertaken_id")
     */
    protected $referenceDisposalUndertaken;

    // Link to Disposal
    /**
     * @ManyToOne(targetEntity="Disposal", fetch="EXTRA_LAZY")
     * @JoinColumn(name="document_description_disposal_id",
     * referencedColumnName="pk_disposal_id")
     */
    protected $referenceDisposal;

    // Link to Deletion
    /**
     * @ManyToOne(targetEntity="Deletion", fetch="EXTRA_LAZY")
     * @JoinColumn(name="document_description_deletion_id",
     * referencedColumnName="pk_deletion_id")
     */
    protected $referenceDeletion;

    // Links to Comment
    /**
     * @ManyToMany(targetEntity="Comment", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @JoinTable(name="document_description_comment",
     * joinColumns=@JoinColumn(
     * name="f_pk_document_description_id",
     * referencedColumnName="pk_document_description_id"),
     * inverseJoinColumns=@JoinColumn(
     * name="f_pk_comment_id",
     * referencedColumnName="pk_comment_id"))
     */
    protected $referenceComment;

    public function __construct()
    {
        $this->referenceAuthor = new ArrayCollection();
        $this->referenceRecord = new ArrayCollection();
        $this->referenceDocumentObject = new ArrayCollection();
        $this->referenceComment = new ArrayCollection();
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
        $this->associationDate = DateTime::createFromFormat(Constants::XSD_DATETIME_FORMAT, $associationDate);
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
        $record->addReferenceDocumentDescription($this);
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

    public function getReferenceStorageLocation()
    {
        return $this->referenceStorageLocation;
    }

    public function setReferenceStorageLocation($referenceStorageLocation)
    {
        $this->referenceStorageLocation = $referenceStorageLocation;
        return $this;
    }

    public function getReferenceScreening()
    {
        return $this->screening;
    }

    public function setReferenceScreening($screening)
    {
        $this->referenceScreening = $screening;
        return $this;
    }

    public function getReferenceElectronicSignature()
    {
        return $this->referenceElectronicSignature;
    }

    public function setReferenceElectronicSignature($referenceElectronicSignature)
    {
        $this->referenceElectronicSignature = $referenceElectronicSignature;
        return $this;
    }

    public function getReferenceClassified()
    {
        return $this->referenceClassified;
    }

    public function setReferenceClassified($referenceClassified)
    {
        $this->referenceClassified = $referenceClassified;
        return $this;
    }

    public function getReferenceDisposalUndertaken()
    {
        return $this->referenceDisposalUndertaken;
    }

    public function setReferenceDisposalUndertaken($referenceDisposalUndertaken)
    {
        $this->referenceDisposalUndertaken = $referenceDisposalUndertaken;
        return $this;
    }

    public function getReferenceDisposal()
    {
        return $this->referenceDisposal;
    }

    public function setReferenceDisposal($referenceDisposal)
    {
        $this->referenceDisposal = $referenceDisposal;
        return $this;
    }

    public function getReferenceDeletion()
    {
        return $this->referenceDeletion;
    }

    public function setReferenceDeletion($referenceDeletion)
    {
        $this->referenceDeletion = $referenceDeletion;
        return $this;
    }

    public function getReferenceComment()
    {
        return $this->referenceComment;
    }

    public function setReferenceComment($referenceComment)
    {
        $this->referenceComment = $referenceComment;
        return $this;
    }

    public function addReferenceComment($comment)
    {
        if ($this->referenceComment->contains($comment)) {
            return;
        }
        $this->referenceComment[] = $comment;
        $comment->addReferenceDocumentDescription($this);
        return $this;
    }

    public function __toString()
    {
        return __METHOD__ . " id[" . $this->id . "], " . "systemId[" . $this->systemId . "]";
    }
}

?>
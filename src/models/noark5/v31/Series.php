<?php

use Doctrine\Common\Collections\ArrayCollection;
require_once ('models/noark5/v31/Fonds.php');
require_once ('utils/Constants.php');

/**
 * @Entity @Table(name="series", indexes={@Index(name="series_search_idx", columns={"system_id"})})
 **/
class Series
{
    /** @Id @Column(type="bigint", name="pk_series_id", nullable=false) @GeneratedValue **/
    protected $id;

    /** M001 - systemID (xs:string) */
    /**  @Column(type="string", name="system_id", nullable=true) **/
    protected $systemId;

    /** M020 - tittel (xs:string) */
    /**  @Column(type="string", name="title", nullable=true) **/
    protected $title;

    /** M021 - beskrivelse (xs:string) */
    /**  @Column(type="string", name="description", nullable=true) **/
    protected $description;

    /** M051 - arkivdelstatus (xs:string) */
    /**  @Column(type="string", name="series_status", nullable=true) **/
    protected $seriesStatus;

    /** M300 - dokumentmedium (xs:string) */
    /**  @Column(type="string", name="document_medium", nullable=true) **/
    protected $documentMedium;

    /** M600 - opprettetDato (xs:dateTime) */
    /**  @Column(type="datetime", name="created_date", nullable=true) **/
    protected $createdDate;

    /** M601 - opprettetAv (xs:string) */
    /**  @Column(type="string", name="created_by", nullable=true) **/
    protected $createdBy;

    /** M602 - avsluttetDato (xs:dateTime) */
    /**  @Column(type="datetime", name="finalised_date", nullable=true) **/
    protected $finalisedDate;

    /** M603 - avsluttetAv (xs:string) */
    /**  @Column(type="string", name="finalised_by", nullable=true) **/
    protected $finalisedBy;

    /** M107 - arkivperiodeStartDato (xs:date) */
    /** @Column(type="date", name="series_start_date", nullable=true) **/

    protected $seriesStartDate;

    /** M108 - arkivperiodeSluttDato (xs:date) */
    /** @Column(type="date", name="series_end_date", nullable=true) **/
    protected $seriesEndDate;

    // Links to StorageLocations
    /** @ManyToMany(targetEntity="StorageLocation", fetch="EXTRA_LAZY")
     *   @JoinTable(name="series_storage_location",
     *        joinColumns=@JoinColumn(
     *        name="f_pk_series_id",
     *        referencedColumnName="pk_series_id"),
     *    inverseJoinColumns=@JoinColumn(
     *        name="f_pk_storage_location_id",
     *        referencedColumnName="pk_storage_location_id"))
     * */
    protected $referenceStorageLocation;

    // Link to Fonds
    /** @ManyToOne(targetEntity="Fonds", fetch="EXTRA_LAZY")
    *   @JoinColumn(name="series_fonds_id",
    *        referencedColumnName="pk_fonds_id")
    **/
    protected $referenceFonds;

    // Link to precursor Series
    /** @OneToOne(targetEntity="Series", fetch="EXTRA_LAZY", mappedBy = "referenceSuccessor") **/
    protected $referencePrecursor;

    // Link to successor Series
    /** @OneToOne(targetEntity="Series", fetch="EXTRA_LAZY", mappedBy = "referencePrecursor") **/
    protected $referenceSuccessor;

    // Link to ClassificationSystem
    /**
     * @ManyToMany(targetEntity="ClassificationSystem", fetch="EXTRA_LAZY")
     * @JoinTable(name="series_classfication_system",
     * joinColumns=@JoinColumn(
     * name="f_pk_series_id",
     * referencedColumnName="pk_series_id"),
     * inverseJoinColumns=@JoinColumn(
     * name="f_pk_classification_system_id",
     * referencedColumnName="pk_classification_system_id"))
     */
    protected $referenceClassificationSystem;

    // Links to Files
    /** @OneToMany(targetEntity="File", mappedBy = "referenceSeries", fetch="EXTRA_LAZY") **/
    protected $referenceFile;

    // Links to Records
    /** @OneToMany(targetEntity="Record", mappedBy = "referenceSeries", fetch="EXTRA_LAZY") **/
    protected $referenceRecord;

    // Link to Classified
    /** @ManyToOne(targetEntity="Classified", fetch="EXTRA_LAZY")
     *   @JoinColumn(name="series_classified_id",
     *        referencedColumnName="pk_classified_id")
     **/
    protected $referenceClassified;

    // Link to DisposalUndertaken
    /** @ManyToOne(targetEntity="DisposalUndertaken", fetch="EXTRA_LAZY")
     *   @JoinColumn(name="series_disposal_undertaken_id",
     *        referencedColumnName="pk_disposal_undertaken_id")
     **/
    protected $referenceDisposalUndertaken;

    // Link to Disposal
    /** @ManyToOne(targetEntity="Disposal", fetch="EXTRA_LAZY")
     *   @JoinColumn(name="series_disposal_id",
     *        referencedColumnName="pk_disposal_id")
     **/
    protected $referenceDisposal;

    // Link to Deletion
    /** @ManyToOne(targetEntity="Deletion", fetch="EXTRA_LAZY")
     *   @JoinColumn(name="series_deletion_id",
     *        referencedColumnName="pk_deletion_id")
     **/
    protected $referenceDeletion;

    // Link to Screening
    /** @ManyToOne(targetEntity="Screening", fetch="EXTRA_LAZY")
     *   @JoinColumn(name="series_screening_id",
     *        referencedColumnName="pk_screening_id")
     **/
    protected $referenceScreening;

    public function __construct()
    {
        $this->referenceStorageLocation = new ArrayCollection();
        $this->referenceFile = new ArrayCollection();
        $this->referenceRecord = new ArrayCollection();
        //TODO: CHECK THIS $this->referenceClassified = new ArrayCollection();
        $this->referenceClassificationSystem = new ArrayCollection();
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

    public function getSeriesStatus()
    {
        return $this->seriesStatus;
    }

    public function setSeriesStatus($seriesStatus)
    {
        $this->seriesStatus = $seriesStatus;
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

    public function getFinalisedDate()
    {
        return $this->finalisedDate;
    }

    public function setFinalisedDate($finalisedDate)
    {
        // have to convert from string object to datetime object
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

    public function getSeriesStartDate()
    {
        return $this->seriesStartDate;
    }

    public function setSeriesStartDate($seriesStartDate)
    {
        // have to convert from string object to datetime object
        $this->seriesStartDate = DateTime::createFromFormat(Constants::XSD_DATE_FORMAT, $seriesStartDate);
        return $this;
    }

    public function getSeriesEndDate()
    {
        return $this->seriesEndDate;
    }

    public function setSeriesEndDate($seriesEndDate)
    {
        // have to convert from string object to datetime object
        $this->seriesEndDate = DateTime::createFromFormat(Constants::XSD_DATE_FORMAT, $seriesEndDate);
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

    public function addReferenceStorageLocation($storageLocation)
    {
        $this->referenceStorageLocation[] = $storageLocation;
        return $this;
    }

    public function getReferenceFonds()
    {
        return $this->referenceFonds;
    }

    public function setReferenceFonds($referenceFonds)
    {
        $this->referenceFonds = $referenceFonds;
        return $this;
    }

    public function getReferencePrecursor()
    {
        return $this->referencePrecursor;
    }

    public function setReferencePrecursor($referencePrecursor)
    {
        $this->referencePrecursor = $referencePrecursor;
        return $this;
    }

    public function getReferenceSuccessor()
    {
        return $this->referenceSuccessor;
    }

    public function setReferenceSuccessor($referenceSuccessor)
    {
        $this->referenceSuccessor = $referenceSuccessor;
        return $this;
    }

    public function getReferenceClassificationSystem()
    {
        return $this->referenceClassificationSystem;
    }

    public function setReferenceClassificationSystem($referenceClassificationSystem)
    {
        $this->referenceClassificationSystem = $referenceClassificationSystem;
        return $this;
    }

    public function addReferenceClassificationSystem($classificationSystem)
    {
        if ($this->referenceClassificationSystem->contains($classificationSystem)) {
            return;
        }
        $this->referenceClassificationSystem[]  = $classificationSystem;
        $classificationSystem->addReferenceSeries($this);
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

    public function addReferenceFile($file) {
        $this->referenceFile[] = $file;
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

    public function addReferenceRecord($record) {
        $this->referenceRecord[] = $record;
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

    public function getReferenceScreening()
    {
        return $this->referenceScreening;
    }

    public function setReferenceScreening($referenceScreening)
    {
        $this->referenceScreening = $referenceScreening;
        return $this;
    }

    public function __toString()
    {
        return __METHOD__ . " id[" . $this->id . "], " . "systemId[" . $this->systemId . "]";
    }
}

?>

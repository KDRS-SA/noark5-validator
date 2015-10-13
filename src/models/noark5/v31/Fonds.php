<?php

use Doctrine\Common\Collections\ArrayCollection;
require_once ('models/noark5/v31/Series.php');
require_once ('models/noark5/v31/StorageLocation.php');
require_once ('models/noark5/v31/FondsCreator.php');
require_once ('utils/Constants.php');

/**
 * @Entity @Table(name="fonds")
 **/
class Fonds
{
    /** @Id @Column(type="bigint", name="pk_fonds_id", nullable=false) @GeneratedValue **/
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

    /** M050 - arkivstatus (xs:string) */
	/**  @Column(type="string", name="fonds_status", nullable=true) **/
	protected $fondsStatus;

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

    // Links to Series
    /** @OneToMany(targetEntity="Series", mappedBy="referenceFonds", fetch="EXTRA_LAZY") **/
    protected $referenceSeries;

    // Link to parent Fonds
    /**
    * @ManyToOne(targetEntity="Fonds", inversedBy="referenceChildFonds", fetch="EXTRA_LAZY")
    * @JoinColumn(name="referenceParentFonds_pk_fonds_id", referencedColumnName="pk_fonds_id")
    * */
    protected $referenceParentFonds;

    // Links to child Fonds
    /** @OneToMany(targetEntity="Fonds", mappedBy="referenceParentFonds", fetch="EXTRA_LAZY") **/
    protected $referenceChildFonds;

    // Links to StorageLocations
    /** @ManyToMany(targetEntity="StorageLocation", inversedBy="referenceFonds", fetch="EXTRA_LAZY")
    *   @JoinTable(name="fonds_storage_location",
    *        joinColumns=@JoinColumn(
    *        name="f_pk_fonds_id",
    *        referencedColumnName="pk_fonds_id"),
    *    inverseJoinColumns=@JoinColumn(
    *        name="f_pk_storage_location_id",
    *        referencedColumnName="pk_storage_location_id"))
    * */
    protected $referenceStorageLocation;

    // Links to FondsCreators
    /** @ManyToMany(targetEntity="FondsCreator", fetch="EXTRA_LAZY")
    *   @JoinTable(name="fonds_fonds_creator",
    *       joinColumns=@JoinColumn(
    *         name="f_pk_fonds_id",
    *         referencedColumnName="pk_fonds_id"),
    *    inverseJoinColumns=@JoinColumn(
    *        name="f_pk_fonds_creator_id",
    *        referencedColumnName="pk_fonds_creator_id"))
    **/
    protected $referenceFondsCreator;

    public function __construct()
    {
        $this->referenceFondsCreator = new ArrayCollection();
        $this->referenceStorageLocation = new ArrayCollection();
        $this->referenceChildFonds = new ArrayCollection();
        $this->referenceSeries = new ArrayCollection();
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
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

    public function getFondsStatus()
    {
        return $this->fondsStatus;
    }

    public function setFondsStatus($fondsStatus)
    {
        $this->fondsStatus = $fondsStatus;
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

    public function getReferenceSeries()
    {
        return $this->referenceSeries;
    }

    public function setReferenceSeries($referenceSeries)
    {
        $this->referenceSeries = $referenceSeries;
        return $this;
    }

    public function getReferenceParentFonds()
    {
        return $this->referenceParentFonds;
    }

    public function setReferenceParentFonds($referenceParentFonds)
    {
        $this->referenceParentFonds = $referenceParentFonds;
        return $this;
    }

    public function getReferenceChildFonds()
    {
        return $this->referenceChildFonds;
    }

    public function setReferenceChildFonds($referenceChildFonds)
    {
        $this->referenceChildFonds = $referenceChildFonds;
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

    public function getReferenceFondsCreator()
    {
        return $this->referenceFondsCreator;
    }

    public function setReferenceFondsCreator($referenceFondsCreator)
    {
        $this->referenceFondsCreator = $referenceFondsCreator;
        return $this;
    }

    public function addReferenceFondsCreator($fondsCreator) {
        $this->referenceFondsCreator[] = $fondsCreator;
    }

    public function addReferenceChildFonds($childFonds) {
        $this->referenceChildFonds[] = $childFonds;
    }

    public function addReferenceSeries($referenceSeries) {
        $this->referenceSeries[] = $referenceSeries;
    }

    public function __toString()
    {
        return "id[" . $this->id . "], " . "systemId[" . $this->systemId . "] " . "title[" . $this->title. "] ";
    }
}

?>
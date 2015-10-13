<?php

use Doctrine\Common\Collections\ArrayCollection;
require_once ('models/noark5/v31/Fonds.php');
require_once ('models/noark5/v31/BasicRecord.php');
require_once ('models/noark5/v31/File.php');
require_once ('models/noark5/v31/Series.php');

/**
 * @Entity @Table(name="storage_location")
 **/
class StorageLocation
{
    /** @Id @Column(type="bigint", name="pk_storage_location_id", nullable=false) @GeneratedValue **/
    protected $id;

    /** M001 - systemID (xs:string) */
    /**  @Column(type="string", name="system_id", nullable=true) **/
    protected $systemId;

 	/** M301 - oppbevaringssted (xs:string) */
    /**  @Column(type="string", name="storage_location", nullable=true) **/
    protected $storageLocation;

    // Links to Fonds
    /** @ManyToMany(mappedBy="referenceStorageLocation", targetEntity="Fonds", fetch="EXTRA_LAZY") **/
    protected $referenceFonds;

    // Links to Series
    /** @ManyToMany(mappedBy = "referenceStorageLocation", targetEntity="Series", fetch="EXTRA_LAZY") **/
    protected $referenceSeries;

    // Links to Files
    /** @ManyToMany(mappedBy = "referenceStorageLocation", targetEntity="File", fetch="EXTRA_LAZY") **/
    protected $referenceFile;

    // Links to BasicRecords
    /** @ManyToMany(mappedBy = "referenceStorageLocation", targetEntity="BasicRecord", fetch="EXTRA_LAZY") **/
    protected $referenceRecord;

    // Links to DocumentDescription
    /** @OneToMany(mappedBy = "referenceStorageLocation", targetEntity="DocumentDescription", fetch="EXTRA_LAZY") **/
    protected $referenceDocumentDescription;

    function __construct()
    {
        $this->referenceFonds = new ArrayCollection();
        $this->referenceSeries = new ArrayCollection();
        $this->referenceFile = new ArrayCollection();
        $this->referenceRecord = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
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

    public function getStorageLocation()
    {
        return $this->storageLocation;
    }

    public function setStorageLocation($storageLocation)
    {
        $this->storageLocation = $storageLocation;
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

    public function addReferenceFonds($fonds)
    {
        if ($this->referenceFonds->contains($fonds)) {
            return;
        }
        $this->referenceFonds[] = $fonds;
        $fonds->addReferenceStorageLocation($this);
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

    public function addReferenceSeries($series)
    {
        $this->referenceSeries[] = $series;
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

    public function addReferenceFile($file)
    {
        $this->referenceFile[] = $file;
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

    public function getReferenceDocumentDescription()
    {
        return $this->referenceDocumentDescription;
    }

    public function setReferenceDocumentDescription($referenceDocumentDescription)
    {
        $this->referenceDocumentDescription = $referenceDocumentDescription;
        return $this;
    }

    public function addReferenceDocumentDescription($documentDescription)
    {
        $this->referenceDocumentDescription[] = $documentDescription;
        return $this;
    }

    public function __toString()
    {
        return "id[" . $this->id . "], " . "systemId[" . $this->systemId . "]";
    }
}

?>
<?php

use Doctrine\Common\Collections\ArrayCollection;
require_once ('models/noark5/v31/Series.php');
require_once ('models/noark5/v31/Klass.php');
require_once ('utils/Constants.php');

/**
 * @Entity @Table(name="classification_system")
 **/
class ClassificationSystem
{
    /** @Id @Column(type="bigint", name="pk_classification_system_id", nullable=false) @GeneratedValue **/
    protected $id;

    /** M001 - systemID (xs:string) */
    /**  @Column(type="string", name="system_id", nullable=true) **/
    protected $systemId;

    /** M002 - klasseID (xs:string) */
    /**  @Column(type="string", name="classification_type", nullable=true) **/
    protected $classificationType;

    /** M020 - tittel (xs:string) */
    /**  @Column(type="string", name="title", nullable=true) **/
    protected $title;

    /** M021 - beskrivelse (xs:string) */
    /**  @Column(type="string", name="description", nullable=true) **/
    protected $description;

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
    /** @ManyToMany(targetEntity="Series", mappedBy="referenceClassificationSystem", fetch="EXTRA_LAZY") **/
    protected $referenceSeries;

    // Links to child Classes
    /** @OneToMany(targetEntity="Klass", mappedBy="referenceClassificationSystem", fetch="EXTRA_LAZY") **/
    protected $referenceClass;

    function __construct()
    {
        $this->referenceSeries = new ArrayCollection();;
        $this->referenceClass = new ArrayCollection();;
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

    public function getClassificationType()
    {
        return $this->classificationType;
    }

    public function setClassificationType($classificationType)
    {
        $this->classificationType = $classificationType;
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

    public function addReferenceSeries($series)
    {
        if ($this->referenceSeries->contains($series)) {
            return;
        }
        $this->referenceSeries[] = $series;
        return $this;
    }


    public function setReferenceSeries($referenceSeries)
    {
        $this->referenceSeries = $referenceSeries;
        return $this;
    }

    public function getReferenceClass()
    {
        return $this->referenceClass;
    }

    public function setReferenceClass($referenceClass)
    {
        $this->referenceClass = $referenceClass;
        return $this;
    }

    public function addReferenceClass($class)
    {
        if ($this->referenceClass->contains($class)) {
            return;
        }
        $this->referenceClass[] = $class;
        return $this;
    }

    public function __toString()
    {
        return "id[" . $this->id . "], " . "systemId[" . $this->systemId . "] " . "title[" . $this->title. "] ";
    }
}

?>
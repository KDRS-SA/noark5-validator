<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="class")
 **/
class Klass
{

    /**
     * @Id @Column(type="bigint", name="pk_class_id", nullable=false) @GeneratedValue *
     */
    protected $id;

    /**
     * M001 - systemID (xs:string)
     */
    /**
     * @Column(type="string", name="system_id", nullable=true) *
     */
    protected $systemId;

    /**
     * M002 - klasseID (xs:string)
     */
    /**
     * @Column(type="string", name="class_id", nullable=true) *
     */
    protected $classId;

    /**
     * M020 - tittel (xs:string)
     */
    /**
     * @Column(type="string", name="title", nullable=true) *
     */
    protected $title;

    /**
     * M021 - beskrivelse (xs:string)
     */
    /**
     * @Column(type="string", name="description", nullable=true) *
     */
    protected $description;

    /**
     * M600 - opprettetDato (xs:dateTime)
     */
    /**
     * @Column(type="datetime", name="created_date", nullable=true) *
     */
    protected $createdDate;

    /**
     * M601 - opprettetAv (xs:string)
     */
    /**
     * @Column(type="string", name="created_by", nullable=true) *
     */
    protected $createdBy;

    /**
     * M602 - avsluttetDato (xs:dateTime)
     */
    /**
     * @Column(type="datetime", name="finalised_date", nullable=true) *
     */
    protected $finalisedDate;

    /**
     * M603 - avsluttetAv (xs:string)
     */
    /**
     * @Column(type="string", name="finalised_by", nullable=true) *
     */
    protected $finalisedBy;

    // Links to Keywords
    /**
     * @ManyToMany(targetEntity="Keyword", fetch="EXTRA_LAZY")
     * @JoinTable(name="class_keyword",
     * joinColumns={@JoinColumn(name="f_pk_class_id", referencedColumnName="pk_class_id")},
     * inverseJoinColumns={@JoinColumn(name="f_pk_keyword_id", referencedColumnName="pk_keyword_id")}
     * )
     */
    protected $referenceKeyword;

    // Link to ClassificationSystem
    /**
     * @ManyToOne(targetEntity="ClassificationSystem", fetch="EXTRA_LAZY")
     * @JoinColumn(name="class_classification_system_id",
     * referencedColumnName="pk_classification_system_id")
     */
    protected $referenceClassificationSystem;

    // Link to parent Class
    /**
     * @ManyToOne(targetEntity="Klass", inversedBy="referenceChildClass", fetch="EXTRA_LAZY")
     * @JoinColumn(name="referenceParentClass_pk_class_id", referencedColumnName="pk_class_id")
     */
    protected $referenceParentClass;

    // Links to child Class
    /**
     * @OneToMany(targetEntity="Klass", mappedBy="referenceParentClass", fetch="EXTRA_LAZY") *
     */
    protected $referenceChildClass;

    // Links to Files
    /**
     * @OneToMany(targetEntity="File", mappedBy="referenceClass", fetch="EXTRA_LAZY") *
     */
    protected $referenceFile;

    // Links to Records
    /**
     * @OneToMany(targetEntity="File", mappedBy="referenceClass", fetch="EXTRA_LAZY") *
     */
    protected $referenceRecord;

    // Links to CrossReference
    /**
     * @OneToMany(targetEntity="CrossReference", mappedBy="referenceClass", fetch="EXTRA_LAZY") *
     */
    protected $referenceCrossReference;

    // Link to Classified
    /**
     * @ManyToOne(targetEntity="Classified", fetch="EXTRA_LAZY")
     * @JoinColumn(name="class_classified_id",
     * referencedColumnName="pk_classified_id")
     */
    protected $referenceClassified;

    // Link to Disposal
    /**
     * @ManyToOne(targetEntity="Disposal", fetch="EXTRA_LAZY")
     * @JoinColumn(name="class_disposal_id",
     * referencedColumnName="pk_disposal_id")
     */
    protected $referenceDisposal;

    // Link to Screening
    /**
     * @ManyToOne(targetEntity="Screening", fetch="EXTRA_LAZY")
     * @JoinColumn(name="class_screening_id",
     * referencedColumnName="pk_screening_id")
     */
    protected $referenceScreening;

    function __construct()
    {
        $this->referenceKeyword = new ArrayCollection();
        $this->referenceFile = new ArrayCollection();
        $this->referenceCrossReference = new ArrayCollection();
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

    public function getClassId()
    {
        return $this->classId;
    }

    public function setClassId($classId)
    {
        $this->classId = $classId;
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

    public function getReferenceKeyword()
    {
        return $this->referenceKeyword;
    }

    public function setReferenceKeyword($referenceKeyword)
    {
        $this->referenceKeyword = $referenceKeyword;
        return $this;
    }

    public function addKeyword($referenceKeyword)
    {
        $this->referenceKeyword[] = $referenceKeyword;
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

    public function getReferenceParentClass()
    {
        return $this->referenceParentClass;
    }

    public function setReferenceParentClass($referenceParentClass)
    {
        $this->referenceParentClass = $referenceParentClass;
        return $this;
    }

    public function getReferenceChildClass()
    {
        return $this->referenceChildClass;
    }

    public function setReferenceChildClass($referenceChildClass)
    {
        $this->referenceChildClass = $referenceChildClass;
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
        $this->referenceFile[]  = $file;
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

    public function getReferenceCrossReference()
    {
        return $this->referenceCrossReference;
    }

    public function setReferenceCrossReference($referenceCrossReference)
    {
        $this->referenceCrossReference = $referenceCrossReference;
        return $this;
    }

    public function addReferenceCrossReference($crossReference)
    {
        if ($this->referenceCrossReference->contains($crossReference)) {
            return $this;
        }

        $this->referenceCrossReference[] = $crossReference;
        $crossReference->setReferenceClass($this);
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

    public function getReferenceDisposal()
    {
        return $this->referenceDisposal;
    }

    public function setReferenceDisposal($referenceDisposal)
    {
        $this->referenceDisposal = $referenceDisposal;
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
        return "id[" . $this->id . "], " . "systemId[" . $this->systemId . "] " . "title[" . $this->title. "] ";
    }
}

?>
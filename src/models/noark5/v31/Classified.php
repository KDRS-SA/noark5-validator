<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="classified")
 **/
class Classified
{
    /** @Id @Column(type="bigint", name="pk_classified_id", nullable=false) @GeneratedValue **/
    protected $id;

    /** M506 - gradering (xs:string) */
    /** @Column(type="string", name="classification", nullable=true) **/
    protected $classification;

    /** M624 - graderingsdato (xs:dateTime) */
    /** @Column(type="datetime", name="classification_date", nullable=true) **/
    protected $classificationDate;

    /** M629 - gradertAv (xs:string) */
    /** @Column(type="string", name = "classification_by", nullable=true) **/
    protected $classificationBy;

    /** M626 - nedgraderingsdato (xs:dateTime) */
    /** @Column(type="datetime", name="classification_downgraded_date", nullable=true) **/
    protected $classificationDowngradedDate;

    /** M627 - nedgradertAv (xs:string) */
    /** @Column(type="string", name = "classification_downgraded_by", nullable=true) **/
    protected $classificationDowngradedBy;

    // Links to Series
    /** @OneToMany(targetEntity="Series", mappedBy="referenceClassification", fetch="EXTRA_LAZY") **/
    protected $referenceSeries;

    // Links to Klass
    /** @OneToMany(targetEntity="Klass", mappedBy="referenceClassification", fetch="EXTRA_LAZY") **/
    protected $referenceKlass;

    // Links to File
    /** @OneToMany(targetEntity="File", mappedBy="referenceClassification", fetch="EXTRA_LAZY") **/
    protected $referenceFile;

    // Links to Record
    /** @OneToMany(targetEntity="Record", mappedBy="referenceClassification", fetch="EXTRA_LAZY") **/
    protected $referenceRecord;

    // Links to DocumentDescription
    /** @OneToMany(targetEntity="DocumentDescription", mappedBy="referenceClassification", fetch="EXTRA_LAZY") **/

    protected $referenceDocumentDescription;


    public function __construct()
    {
        $this->referenceDocumentDescription = new ArrayCollection();
        $this->referenceRecord = new ArrayCollection();
        $this->referenceFile = new ArrayCollection();
        $this->referenceKlass = new ArrayCollection();
        $this->referenceSeries = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getClassification()
    {
        return $this->classification;
    }

    public function setClassification($classification)
    {
        $this->classification = $classification;
        return $this;
    }

    public function getClassificationDate()
    {
        return $this->classificationDate;
    }

    public function setClassificationDate($classificationDate)
    {

        $this->classificationDate = DateTime::createFromFormat(Constants::XSD_DATETIME_FORMAT, $classificationDate);
        return $this;
    }

    public function getClassificationBy()
    {
        return $this->classificationBy;
    }

    public function setClassificationBy($classificationBy)
    {
        $this->classificationBy = $classificationBy;
        return $this;
    }

    public function getClassificationDowngradedDate()
    {
        return $this->classificationDowngradedDate;
    }

    public function setClassificationDowngradedDate($classificationDowngradedDate)
    {
        $this->classificationDowngradedDate = DateTime::createFromFormat(Constants::XSD_DATETIME_FORMAT, $classificationDowngradedDate);
        return $this;
    }

    public function getClassificationDowngradedBy()
    {
        return $this->classificationDowngradedBy;
    }

    public function setClassificationDowngradedBy($classificationDowngradedBy)
    {
        $this->classificationDowngradedBy = $classificationDowngradedBy;
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
        if ($this->referenceSeries->contains($series)) {
            return;
        }
        $this->referenceSeries[] = $series;
        return $this;
    }

    public function getReferenceKlass()
    {
        return $this->referenceKlass;
    }

    public function setReferenceKlass($referenceKlass)
    {
        $this->referenceKlass = $referenceKlass;
        return $this;
    }

    public function addReferenceKlass($klass)
    {
        if ($this->referenceKlass->contains($klass)) {
            return;
        }
        $this->referenceKlass[] = $klass;
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
        if ($this->referenceFile->contains($file)) {
            return;
        }
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
        if ($this->referenceRecord->contains($record)) {
            return;
        }
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
        if ($this->referenceDocumentDescription->contains($documentDescription)) {
            return;
        }
        $this->referenceDocumentDescription[] = $documentDescription;
        return $this;
    }


    public function __toString() {
        return
        ' [' . $this->id. '],' .
        ' [' . $this->classification. '],' .
        ' [' . ($this->classificationDate == null ? null : $this->classificationDate->format(Constants::XSD_DATETIME_FORMAT)) . '],' .
        ' [' . $this->classificationBy. '],' .
        ' [' . ($this->classificationDowngradedDate == null ? null : $this->classificationDowngradedDate->format(Constants::XSD_DATETIME_FORMAT)) . '],' .
        ' [' . $this->classificationDowngradedBy. '],';
    }
}

?>
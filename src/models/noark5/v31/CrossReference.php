<?php


/**
 * @Entity @Table(name="cross_reference")
 **/
class CrossReference
{
    /** @Id @Column(type="bigint", name="pk_cross_reference_id", nullable=false) @GeneratedValue **/
    protected $id;

    // Link to Class
    /** @ManyToOne(targetEntity="Klass", fetch="EXTRA_LAZY")
     *   @JoinColumn(name="cross_reference_class_id",
     *        referencedColumnName="pk_class_id")
     **/
    protected $referenceClass;

    // Link to File
    /** @ManyToOne(targetEntity="Klass", fetch="EXTRA_LAZY")
     *   @JoinColumn(name="cross_reference_file_id",
     *        referencedColumnName="pk_class_id")
     **/
    protected $referenceFile;

    // Link to Record
    /** @ManyToOne(targetEntity="Klass", fetch="EXTRA_LAZY")
     *   @JoinColumn(name="cross_reference_record_id",
     *        referencedColumnName="pk_class_id")
     **/
    protected $referenceRecord;

    /** M219 - referanseTilKlasse (xs:string) **/
    /** @Column(type="string", name = "reference_to_class", nullable=true) **/
    protected $referenceToClass;

    /** M210 - referanseTilMappe (xs:string) **/
    /** @Column(type="string", name = "reference_to_file", nullable=true) **/
    protected $referenceToFile;

    /** M212 - referanseTilRegistrering (xs:string) **/
    /** @Column(type="string", name = "reference_to_record", nullable=true) **/
    protected $referenceToRecord;


    public function __construct(){}

    public function getId()
    {
        return $this->id;
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

    public function getReferenceFile()
    {
        return $this->referenceFile;
    }

    public function setReferenceFile($referenceFile)
    {
        $this->referenceFile = $referenceFile;
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

    public function getReferenceToClass()
    {
        return $this->referenceToClass;
    }

    public function setReferenceToClass($referenceToClass)
    {
        $this->referenceToClass = $referenceToClass;
        return $this;
    }

    public function getReferenceToFile()
    {
        return $this->referenceToFile;
    }

    public function setReferenceToFile($referenceToFile)
    {
        $this->referenceToFile = $referenceToFile;
        return $this;
    }

    public function getReferenceToRecord()
    {
        return $this->referenceToRecord;
    }

    public function setReferenceToRecord($referenceToRecord)
    {
        $this->referenceToRecord = $referenceToRecord;
        return $this;
    }

    public function  __toString() {
        return 'id[' . $this->id  . '], referenceToClass [' . $this->referenceToClass . '], referenceToFile [' . $this->referenceToFile . '], referenceToRecord' . $this->referenceToRecord . ']';
    }

}

?>
<?php

use Doctrine\Common\Collections\ArrayCollection;
require_once ('models/noark5/v31/File.php');
require_once ('models/noark5/v31/Klass.php');
require_once ('models/noark5/v31/BasicRecord.php');

/**
 * @Entity @Table(name="keyword")
 **/

class Keyword {

    /** @Id @Column(type="bigint", name="pk_keyword_id", nullable=false) @GeneratedValue **/
    protected $id;

    /** M001 - systemID (xs:string) */
    /**  @Column(type="string", name="system_id", nullable=true) **/
    protected $systemId;

    /** M022 - tittel (xs:string) */
    /**  @Column(type="string", name="keyword", nullable=true) **/
    protected $keyword;

    // Link to Class
    /** @ManyToMany(targetEntity="Klass", mappedBy="referenceKeyword") **/
    protected $referenceClass;

    // Links to File
    /** @ManyToMany(targetEntity="File", mappedBy="referenceKeyword") **/
    protected $referenceFile;

    // Links to BasicRecord
    /** @ManyToMany(targetEntity="BasicRecord", mappedBy = "referenceKeyword") **/
    protected $referenceBasicRecord;

    public function __construct() {
        $this->referenceBasicRecord = new ArrayCollection();
        $this->referenceFile = new ArrayCollection();
        $this->referenceClass = new ArrayCollection();
        //$this->referenceClass = new ArrayCollection();
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

    public function getKeyword()
    {
        return $this->keyword;
    }

    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
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

    public function addReferenceClass($klass)
    {
        $this->referenceClass[] = $klass;
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

    public function getReferenceBasicRecord()
    {
        return $this->referenceBasicRecord;
    }

    public function setReferenceBasicRecord($referenceBasicRecord)
    {
        $this->referenceBasicRecord = $referenceBasicRecord;
        return $this;
    }

    public function addReferenceBasicRecord($basicRecord)
    {
        $this->referenceBasicRecord[] = $basicRecord;
        return $this;
    }

    public function __toString()
    {
        return "id[" . $this->id . "], " . "systemId[" . $this->systemId . "] " . "keyword[" . $this->keyword . "] ";
    }
}

?>
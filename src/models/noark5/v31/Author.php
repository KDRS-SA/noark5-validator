<?php

use Doctrine\Common\Collections\ArrayCollection;
require_once ('models/noark5/v31/DocumentDescription.php');
require_once ('models/noark5/v31/BasicRecord.php');

/**
 * @Entity @Table(name="author")
 **/
class Author
{
    /** @Id @Column(type="bigint", name="pk_author_id", nullable=false) @GeneratedValue **/
    protected $id;

    /** M001 - systemID (xs:string) */
    /**  @Column(type="string", name="system_id", nullable=true) **/
    protected $systemId;

    /** M024 - forfatter (xs:string) */
    /**  @Column(type="string", name="author", nullable=true) **/
    protected $author;

    // Links to BasicRecords
    /**  @ManyToMany(targetEntity="BasicRecord", mappedBy="referenceAuthor") **/
    protected $referenceBasicRecord;

    // Links to BasicRecords
    /**  @ManyToMany(targetEntity="DocumentDescription", mappedBy="referenceAuthor") **/
    protected $referenceDocumentDescription;

    public function __construct()
    {
        $this->referenceDocumentDescription = new ArrayCollection();
        $this->referenceBasicRecord = new ArrayCollection();
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

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    public function addBasicRecord($basicRecord)
    {
        if ($this->referenceBasicRecord->contains($basicRecord)) {
            return;
        }
        $this->referenceBasicRecord[] = $basicRecord;
        $basicRecord->addReferenceAuthor($this);
        return $this;
    }

    public function addDocumentDescription($documentDescription)
    {
        if ($this->referenceDocumentDescription->contains($documentDescription)) {
            return;
        }

        $this->referenceDocumentDescription[] = $documentDescription;
        $documentDescription->addReferenceAuthor($this);
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

    public function getReferenceDocumentDescription()
    {
        return $this->referenceDocumentDescription;
    }

    public function setReferenceDocumentDescription($referenceDocumentDescription)
    {
        $this->referenceDocumentDescription = $referenceDocumentDescription;
        return $this;
    }

    public function __toString()
    {
        return "id[" . $this->id . "], " . "systemId[" . $this->systemId . "], author [" . $this->author . "].";
    }

}

?>
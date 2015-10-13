<?php

use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity @Table(name="comment")
 **/
class Comment
{
    /** @Id @Column(type="bigint", name="pk_comment_id", nullable=false) @GeneratedValue **/
    protected $id;

    /** M310 - merknadstekst (xs:string) */
    /** @Column(type="string", name="comment_text", nullable=true, length = 2000) **/
    protected $commentText;

    /** M084 - merknadstype (xs:string) */
    /** @Column(type="string", name="comment_type", nullable=true) **/
    protected $commentType;

    /** M611 - merknadsdato (xs:dateTime)*/
    /** @Column(type="datetime", name="comment_time", nullable=true) **/
    protected $commentDate;

    /** M612 - merknadRegistrertAv (xs:string) */
    /** @Column(type="string", name="comment_registered_by", nullable=true) **/
    protected $commentRegisteredBy;

    // Link to File
    /**
     * @ManyToMany(targetEntity="File", mappedBy="referenceComment", cascade={"persist", "remove"})
     */
    protected $referenceFile;

    // Link to BasicRecord
    /**
     * @ManyToMany(targetEntity="BasicRecord", mappedBy="referenceComment")
     */
    protected  $referenceBasicRecord;

    // Link to DocumentDescription
    /**
     * @ManyToMany(targetEntity="DocumentDescription", mappedBy="referenceComment")
     */
    protected $referenceDocumentDescription;

    public function __construct()
    {
        $this->referenceFile = new ArrayCollection();
        $this->referenceDocumentDescription = new ArrayCollection();
        $this->referenceBasicRecord = new ArrayCollection();
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getCommentText()
    {
        return $this->commentText;
    }

    public function setCommentText($commentText)
    {
        $this->commentText = $commentText;
        return $this;
    }

    public function getCommentType()
    {
        return $this->commentType;
    }

    public function setCommentType($commentType)
    {
        $this->commentType = $commentType;
        return $this;
    }

    public function getCommentDate()
    {
        return $this->commentDate;
    }

    public function setCommentDate($commentDate)
    {
        $this->commentDate = DateTime::createFromFormat(Constants::XSD_DATETIME_FORMAT, $commentDate);
        return $this;
    }

    public function getCommentRegisteredBy()
    {
        return $this->commentRegisteredBy;
    }

    public function setCommentRegisteredBy($commentRegisteredBy)
    {
        $this->commentRegisteredBy = $commentRegisteredBy;
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
        $this->referenceFile [] = $file;
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

    public function addReferenceBasicRecord($basicRecord) {
        if ($this->referenceBasicRecord->contains($basicRecord)) {
            return;
        }
        $this->referenceBasicRecord[] = $basicRecord;
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
        return 'id[' . $this->id .'], commentText [' . $this->commentText. '] commentType [ ' .   $this->commentType . '] ';
    }

}

?>
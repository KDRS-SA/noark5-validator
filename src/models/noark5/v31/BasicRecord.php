<?php
use Doctrine\Common\Collections\ArrayCollection;
require_once ('models/noark5/v31/Author.php');
require_once ('models/noark5/v31/Record.php');
require_once ('models/noark5/v31/StorageLocation.php');
require_once ('models/noark5/v31/Keyword.php');

/**
 * @Entity @Table(name="basic_record")
 */
class BasicRecord extends Record
{

    /**
     * M004 - registreringsID (xs:string)
     */
    /**
     * @Column(type="string", name = "record_id", nullable=true) *
     */
    protected $recordId;

    /**
     * M020 - tittel (xs:string)
     */
    /**
     * @Column(type="string", name = "title", nullable=true) *
     */
    protected $title;

    /**
     * M025 - offentligTittel (xs:string)
     */
    /**
     * @Column(type="string", name = "official_title", nullable=true) *
     */
    protected $officialTitle;

    /**
     * M021 - beskrivelse (xs:string)
     */
    /**
     * @Column(type="string", name = "description", nullable=true) *
     */
    protected $description;

    /**
     * M300 - dokumentmedium (xs:string)
     */
    /**
     * @Column(type="string", name = "document_medium", nullable=true) *
     */
    protected $documentMedium;

    // Links to StorageLocations
    /** @ManyToMany(targetEntity="StorageLocation", inversedBy="referenceRecord", fetch="EXTRA_LAZY")
    *   @JoinTable(name="basic_record_storage_location",
    *        joinColumns=@JoinColumn(
    *        name="f_pk_record_id",
    *        referencedColumnName="pk_record_id"),
    *    inverseJoinColumns=@JoinColumn(
    *        name="f_pk_storage_location_id",
    *        referencedColumnName="pk_storage_location_id"))
    * */
    protected $referenceStorageLocation;

    // Links to Keywords
    /**
     * @ManyToMany(targetEntity="Keyword", inversedBy="referenceBasicRecord", cascade={"persist", "remove"})
     * @JoinTable(name="basic_record_keyword",
     * joinColumns={@JoinColumn(name="f_pk_record_id", referencedColumnName="pk_record_id")},
     * inverseJoinColumns={@JoinColumn(name="f_pk_keyword_id", referencedColumnName="pk_keyword_id")}
     * )
     */
    protected $referenceKeyword;

    // Links to Authors
    /**
     * @ManyToMany(targetEntity="Author", inversedBy="referenceBasicRecord", cascade={"persist", "remove"})
     * @JoinTable(name="basic_record_author",
     * joinColumns={@JoinColumn(name="f_pk_record_id", referencedColumnName="pk_record_id")},
     * inverseJoinColumns={@JoinColumn(name="f_pk_author_id", referencedColumnName="pk_author_id")}
     * )
     */
    protected $referenceAuthor;

    /**
     * @OneToOne(targetEntity="ElectronicSignature", mappedBy="referenceBasicRecord")
     */
    protected $referenceElectronicSignature;

    // Links to Comment
    /**
     * @ManyToMany(targetEntity="Comment", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @JoinTable(name="basic_record_comment",
     * joinColumns=@JoinColumn(
     * name="f_pk_basic_record_id",
     * referencedColumnName="pk_record_id"),
     * inverseJoinColumns=@JoinColumn(
     * name="f_pk_comment_id",
     * referencedColumnName="pk_comment_id"))
     */
     protected $referenceComment;

    public function __construct()
    {
        parent::__construct();
        $this->referenceAuthor = new ArrayCollection();
        $this->referenceStorageLocation = new ArrayCollection();
        $this->referenceKeyword = new ArrayCollection();
        $this->referenceComment = new ArrayCollection();
    }

    public function getRecordId()
    {
        return $this->recordId;
    }

    public function setRecordId($recordId)
    {
        $this->recordId = $recordId;
        return $this;
    }

    public function getOfficialTitle()
    {
        return $this->officialTitle;
    }

    public function setOfficialTitle($officialTitle)
    {
        $this->officialTitle = $officialTitle;
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

    public function getDocumentMedium()
    {
        return $this->documentMedium;
    }

    public function setDocumentMedium($documentMedium)
    {
        $this->documentMedium = $documentMedium;
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

    public function getReferenceKeyword()
    {
        return $this->referenceKeyword;
    }

    public function setReferenceKeyword($referenceKeyword)
    {
        $this->referenceKeyword = $referenceKeyword;
        return $this;
    }

    public function getReferenceAuthor()
    {
        return $this->referenceAuthor;
    }

    public function addAuthor($author)
    {
        $this->referenceAuthor[] = $author;
        return $this;
    }

    public function setReferenceAuthor($referenceAuthor)
    {
        $this->referenceAuthor = $referenceAuthor;
        return $this;
    }

    public function getReferenceElectronicSignature()
    {
        return $this->referenceElectronicSignature;
    }

    public function setReferenceElectronicSignature($referenceElectronicSignature)
    {
        $this->referenceElectronicSignature = $referenceElectronicSignature;
        return $this;
    }

    public function getReferenceComment()
    {
        return $this->referenceComment;
    }

    public function addReferenceComment($comment)
    {
        if ($this->referenceComment->contains($comment)) {
            return;
        }
        $this->referenceComment[] = $comment;
        $comment->addReferenceBasicRecord($this);
        return $this;
    }

    public function __toString()
    {
        return "id[" . $this->id . "], " . "systemId[" . $this->systemId . "] " . "title[" . $this->title . "] ";
    }
}

?>
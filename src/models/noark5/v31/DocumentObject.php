<?php
use Doctrine\Common\Collections\ArrayCollection;

require_once ('models/noark5/v31/DocumentDescription.php');
require_once ('models/noark5/v31/Record.php');

/**
 * @Entity @Table(name="document_object")
 **/
class DocumentObject
{
    /** @Id @Column(type="bigint", name="pk_document_object_id", nullable=false) @GeneratedValue **/
    protected $id;

	/** M005 - versjonsnummer (xs:integer) */
	/** @Column(type="integer", name = "version_number", nullable=true) **/
	protected $versionNumber;

	/** M700 - variantformat (xs:string) */
	/** @Column(type="string", name = "variant_format", nullable=true) **/
	protected $variantFormat;

	/** M701 - format (xs:string) */
	/** @Column(type="string", name = "format", nullable=true) **/
	protected $format;

	/** M702 - formatDetaljer (xs:string) */
	/** @Column(type="string", name = "format_details", nullable=true) **/
	protected $formatDetails;

	/** M600 - opprettetDato (xs:dateTime) */
	/** @Column(type="datetime", name = "created_date", nullable=true) **/
	protected $createdDate;

	/** M601 - opprettetAv (xs:string) */
	/** @Column(type="string", name = "created_by", nullable=true) **/
	protected $createdBy;

	/** M218 - referanseDokumentfil (xs:string) */
	/** @Column(type="string", name = "reference_document_file", nullable=true) **/
	protected $referenceDocumentFile;

	/** M705 - sjekksum (xs:string) */
	/** @Column(type="string", name = "checksum", nullable=true) **/
	protected $checksum;

	/** M706 - sjekksumAlgoritme (xs:string) */
	/** @Column(type="string", name = "checksum_algorithm", nullable=true) **/
	protected $checksumAlgorithm;

	/** M707 - filstoerrelse (xs:string) */
	/** @Column(type="integer", name = "file_size", nullable=true) **/
	protected $fileSize;

	// Link to DocumentDescription
	/**
	 *   @ManyToOne (targetEntity="DocumentDescription", fetch="EXTRA_LAZY")
	 *       @JoinColumn(name="document_object_document_description_id",
	 *         referencedColumnName="pk_document_description_id")
	 **/
	protected  $referenceDocumentDescription;

	// Link to Record
	/**
	 *   @ManyToOne (targetEntity="Record", fetch="EXTRA_LAZY")
	 *       @JoinColumn(name="document_object_record_id",
	 *         referencedColumnName="pk_record_id")
	 **/
	protected $referenceRecord;

	// Links to Conversion
	/** @OneToMany(targetEntity="Conversion", mappedBy="referenceDocumentObject", fetch="EXTRA_LAZY") **/
	protected $referenceConversion;

	/**
	 * @OneToOne(targetEntity="ElectronicSignature", mappedBy="referenceDocumentObject")
	 **/
	protected $referenceElectronicSignature;

    public function __construct()
    {
        $this->referenceConversion =  new ArrayCollection();
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

    public function getVersionNumber()
    {
        return $this->versionNumber;
    }

    public function setVersionNumber($versionNumber)
    {
        $this->versionNumber = $versionNumber;
        return $this;
    }

    public function getVariantFormat()
    {
        return $this->variantFormat;
    }

    public function setVariantFormat($variantFormat)
    {
        $this->variantFormat = $variantFormat;
        return $this;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    public function getFormatDetails()
    {
        return $this->formatDetails;
    }

    public function setFormatDetails($formatDetails)
    {
        $this->formatDetails = $formatDetails;
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

    public function getReferenceDocumentFile()
    {
        return $this->referenceDocumentFile;
    }

    public function setReferenceDocumentFile($referenceDocumentFile)
    {
        $this->referenceDocumentFile = $referenceDocumentFile;
        return $this;
    }

    public function getChecksum()
    {
        return $this->checksum;
    }

    public function setChecksum($checksum)
    {
        $this->checksum = $checksum;
        return $this;
    }

    public function getChecksumAlgorithm()
    {
        return $this->checksumAlgorithm;
    }

    public function setChecksumAlgorithm($checksumAlgorithm)
    {
        $this->checksumAlgorithm = $checksumAlgorithm;
        return $this;
    }

    public function getFileSize()
    {
        return $this->fileSize;
    }

    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
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

    public function getReferenceRecord()
    {
        return $this->referenceRecord;
    }

    public function setReferenceRecord($referenceRecord)
    {
        $this->referenceRecord = $referenceRecord;
        return $this;
    }

    public function getReferenceConversion()
    {
        return $this->referenceConversion;
    }

    public function setReferenceConversion($referenceConversion)
    {
        $this->referenceConversion = $referenceConversion;
        return $this;
    }

    public function addReferenceConversion($conversion) {
        if ($this->referenceConversion->contains($conversion)) {
            return;
        }
        $this->referenceConversion[] = $conversion;

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


    public function __toString()
    {
        return __METHOD__ . " id[" . $this->id . "]";
    }

}

?>
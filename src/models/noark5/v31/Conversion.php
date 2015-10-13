<?php

/**
 * @Entity @Table(name="conversion")
 **/
class Conversion
{
    /** @Id @Column(type="bigint", name="pk_conversion_id", nullable=false) @GeneratedValue **/
    protected $id;

    /** M615 - konvertertDato (xs:dateTime) */
    /** @Column(type="datetime", name = "converted_date", nullable=true) **/
    protected $convertedDate;

    /** M616 - konvertertAv (xs:string) */
    /** @Column(type="string", name = "converted_by", nullable=true) **/
    protected $convertedBy;

    /** M712 - konvertertFraFormat (xs:string) */
    /** @Column(type="string", name = "converted_from_format", nullable=true) **/
    protected $convertedFromFormat;

    /** M713 - konvertertTilFormat (xs:string) */
    /** @Column(type="string", name = "converted_to_format", nullable=true) **/
    protected $convertedToFormat;

    /** M714 - konverteringsverktoey (xs:string) */
    /** @Column(type="string", name = "conversion_tool", nullable=true) **/
    protected $conversionTool;

    /** M715 - konverteringskommentar (xs:string) */
    /** @Column(type="string", name = "conversion_comment", nullable=true) **/
    protected $conversionComment;

    // Link to DocumentObject
    /** @ManyToOne(targetEntity="DocumentObject", fetch="EXTRA_LAZY")
     *   @JoinColumn(name="conversion_document_object_id",
     *        referencedColumnName="pk_document_object_id")
     **/
    protected $referenceDocumentObject;

    public function __construct()
    {

    }

    public function getId()
    {
        return $this->id;
    }

    public function getConvertedDate()
    {
        return $this->convertedDate;
    }

    public function setConvertedDate($convertedDate)
    {
        $this->convertedDate = DateTime::createFromFormat(Constants::XSD_DATETIME_FORMAT, $convertedDate);
        return $this;
    }

    public function getConvertedBy()
    {
        return $this->convertedBy;
    }

    public function setConvertedBy($convertedBy)
    {
        $this->convertedBy = $convertedBy;
        return $this;
    }

    public function getConvertedFromFormat()
    {
        return $this->convertedFromFormat;
    }

    public function setConvertedFromFormat($convertedFromFormat)
    {
        $this->convertedFromFormat = $convertedFromFormat;
        return $this;
    }

    public function getConvertedToFormat()
    {
        return $this->convertedToFormat;
    }

    public function setConvertedToFormat($convertedToFormat)
    {
        $this->convertedToFormat = $convertedToFormat;
        return $this;
    }

    public function getConversionTool()
    {
        return $this->conversionTool;
    }

    public function setConversionTool($conversionTool)
    {
        $this->conversionTool = $conversionTool;
        return $this;
    }

    public function getConversionComment()
    {
        return $this->conversionComment;
    }

    public function setConversionComment($conversionComment)
    {
        $this->conversionComment = $conversionComment;
        return $this;
    }

    public function getReferenceDocumentObject()
    {
        return $this->referenceDocumentObject;
    }

    public function setReferenceDocumentObject($referenceDocumentObject)
    {
        $this->referenceDocumentObject = $referenceDocumentObject;
        return $this;
    }


    public function __toString() {
        return 'id[' . $this->id .'], convertedBy [' . $this->convertedBy. '], convertedFromFormat [ ' .   $this->convertedFromFormat . '] ';
    }

}

?>
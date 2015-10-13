<?php


/**
 * @Entity @Table(name="electronic_signature")
 **/

class ElectronicSignature
{
    /** @Id @Column(type="bigint", name="pk_electronic_signature_id", nullable=false) @GeneratedValue **/
    protected $id;

    /** M507 - elektroniskSignaturSikkerhetsnivaa (xs:string) */
    /** @Column(type="string", name = "electronic_signature_security_level", nullable=true) **/
    protected $electronicSignatureSecurityLevel;

    /** M508 - elektroniskSignaturVerifisert (xs:string) */
    /** @Column(type="string", name = "electronic_sSignature_verified", nullable=true) **/
    protected $electronicSignatureVerified;

    /** M622 - verifisertDato (xs:date) */
    /** @Column(type="string", name = "verified_date", nullable=true) **/
    protected $verifiedDate;

    /** M623 - verifisertAv (xs:string) */
    /** @Column(type="string", name = "verified_by", nullable=true) **/
    protected $verifiedBy;

    /**
     * @OneToOne(targetEntity="BasicRecord", inversedBy="referenceElectronicSignature")
     * @JoinColumn(name="pk_electronic_signature_id", referencedColumnName="pk_record_id")
     **/
    protected $referenceBasicRecord;

    /**
     * @OneToOne(targetEntity="DocumentObject", inversedBy="referenceElectronicSignature")
     * @JoinColumn(name="pk_electronic_signature_id", referencedColumnName="pk_document_object_id")
     **/
    protected $referenceDocumentObject;

    /**
     * @OneToOne(targetEntity="DocumentDescription", inversedBy="referenceElectronicSignature")
     * @JoinColumn(name="pk_electronic_signature_id", referencedColumnName="pk_document_description_id")
     **/
    protected $referenceDocumentDescription;

    public function __construct() {}

    public function getId()
    {
        return $this->id;
    }

    public function getElectronicSignatureSecurityLevel()
    {
        return $this->electronicSignatureSecurityLevel;
    }

    public function setElectronicSignatureSecurityLevel($electronicSignatureSecurityLevel)
    {
        $this->electronicSignatureSecurityLevel = $electronicSignatureSecurityLevel;
        return $this;
    }

    public function getElectronicSignatureVerified()
    {
        return $this->electronicSignatureVerified;
    }

    public function setElectronicSignatureVerified($electronicSignatureVerified)
    {
        $this->electronicSignatureVerified = $electronicSignatureVerified;
        return $this;
    }

    public function getVerifiedDate()
    {
        return $this->verifiedDate;
    }

    public function setVerifiedDate($verifiedDate)
    {
        $this->verifiedDate = DateTime::createFromFormat(Constants::XSD_DATETIME_FORMAT, $verifiedDate);
        return $this;
    }

    public function getVerifiedBy()
    {
        return $this->verifiedBy;
    }

    public function setVerifiedBy($verifiedBy)
    {
        $this->verifiedBy = $verifiedBy;
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

    public function getReferenceDocumentObject()
    {
        return $this->referenceDocumentObject;
    }

    public function setReferenceDocumentObject($referenceDocumentObject)
    {
        $this->referenceDocumentObject = $referenceDocumentObject;
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
}

?>
<?php

/**
 * @Entity @Table(name="case_party")
 **/
class CaseParty
{
    /** @Id @Column(type="bigint", name="pk_case_party_id", nullable=false) @GeneratedValue **/
    protected $id;

    /** M010 - sakspartID (xs:string) */
    /** @Column(type="string",name = "case_party_id", nullable=true) **/
    protected $casePartyId;

    /** M302 - sakspartNavn (xs:string) */
    /** @Column(type="string",name = "case_party_name", nullable=true) **/
    protected $casePartyName;

    /** M303 - sakspartRolle (xs:string) */
    /** @Column(type="string",name = "case_party_role", nullable=true) **/
    protected $casePartyRole;

    /** M406 - postadresse (xs:string) */
    /** @Column(type="string",name = "postal_address", nullable=true) **/
    protected $postalAddress;

    /** M407 - postnummer (xs:string) */
    /** @Column(type="string",name = "post_code", nullable=true) **/
    protected $postCode;

    /** M408 - poststed (xs:string) */
    /** @Column(type="string",name = "postal_town", nullable=true) **/
    protected $postalTown;

    /** M409 - land (xs:string) */
    /** @Column(type="string",name = "country", nullable=true) **/
    protected $country;

    /** M410 - epostadresse (xs:string) */
    /** @Column(type="string",name = "email_address", nullable=true) **/
    protected $emailAddress;

    /** M411 - telefonnummer (xs:string) */
    /** @Column(type="string",name = "telephone_number", nullable=true) **/
    protected $telephoneNumber;

    /** M412 - kontaktperson (xs:string) */
    /** @Column(type="string",name = "contact_person", nullable=true) **/
    protected $contactPerson;

    // Links to CaseFiles
    /** @ManyToMany(targetEntity="CaseFile", mappedBy="referenceCaseParty") **/
    protected $referenceCaseFile;

    public function __construct()
    {}

    public function getId()
    {
        return $this->id;
    }

    public function getCasePartyId()
    {
        return $this->casePartyId;
    }

    public function setCasePartyId($casePartyId)
    {
        $this->casePartyId = $casePartyId;
        return $this;
    }

    public function getCasePartyName()
    {
        return $this->casePartyName;
    }

    public function setCasePartyName($casePartyName)
    {
        $this->casePartyName = $casePartyName;
        return $this;
    }

    public function getCasePartyRole()
    {
        return $this->casePartyRole;
    }

    public function setCasePartyRole($casePartyRole)
    {
        $this->casePartyRole = $casePartyRole;
        return $this;
    }

    public function getPostalAddress()
    {
        return $this->postalAddress;
    }

    public function setPostalAddress($postalAddress)
    {
        $this->postalAddress = $postalAddress;
        return $this;
    }

    public function getPostCode()
    {
        return $this->postCode;
    }

    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;
        return $this;
    }

    public function getPostalTown()
    {
        return $this->postalTown;
    }

    public function setPostalTown($postalTown)
    {
        $this->postalTown = $postalTown;
        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    public function getTelephoneNumber()
    {
        return $this->telephoneNumber;
    }

    public function setTelephoneNumber($telephoneNumber)
    {
        $this->telephoneNumber = $telephoneNumber;
        return $this;
    }

    public function getContactPerson()
    {
        return $this->contactPerson;
    }

    public function setContactPerson($contactPerson)
    {
        $this->contactPerson = $contactPerson;
        return $this;
    }

    public function getReferenceCaseFile()
    {
        return $this->referenceCaseFile;
    }

    public function setReferenceCaseFile($referenceCaseFile)
    {
        $this->referenceCaseFile = $referenceCaseFile;
        return $this;
    }

    public function addReferenceCaseParty($caseParty)
    {
        if ($this->referenceCaseParty->contains($caseParty)) {
            return;
        }
        $this->referenceCaseParty = $caseParty;
        return $this;
    }

    public function __toString() {
        return
            ' id[' . $this->id . '],' .
            ' casePartyId[' . $this->casePartyId . '],' .
            ' casePartyName[' . $this->casePartyName . '],' .
            ' casePartyRole[' . $this->casePartyRole . '],' .
            ' postalAddress[' . $this->postalAddress . '],' .
            ' postCode[' . $this->postCode . '],' .
            ' postalTown[' . $this->postalTown . '],' .
            ' country[' . $this->country . '],' .
            ' emailAddress[' . $this->emailAddress . '],' .
            ' telephoneNumber[' . $this->telephoneNumber . '],' .
            ' contactPerson[' . $this->contactPerson . '],';
    }
}

?>
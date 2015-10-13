<?php

require_once ('models/noark5/v31/FondsCreator.php');
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity @Table(name="correspondence_part")
 **/
class CorrespondencePart
{
    /** @Id @Column(type="bigint", name="pk_correspondence_part_id", nullable=false) @GeneratedValue **/
    protected $id;

    /** M087 - korrespondanseparttype */
    /** @Column(type="string", name = "correspondance_part_type", nullable=true) **/
    protected $correspondancePartType;

    /** M400 - korrespondansepartNavn (xs:string) */
    /** @Column(type="string",name = "correspondance_part_name", nullable=true) **/
    protected $correspondancePartName;

    /** M406 - postadresse (xs:string) */
    /** @Column(type="string", name = "postal_address", nullable=true) **/
    protected $postalAddress;

    /** M407 - postnummer (xs:string) */
    /** @Column(type="string", name = "post_code", nullable=true) **/
    protected $postCode;

    /** M408 - poststed (xs:string) */
    /** @Column(type="string", name = "postal_town", nullable=true) **/
    protected $postalTown;

    /** M409 - land (xs:string) */
    /** @Column(type="string", name = "country", nullable=true) **/
    protected $country;

    /** M410 - epostadresse (xs:string) */
    /** @Column(type="string", name = "email_address", nullable=true) **/
    protected $emailAddress;

    /** M411 - telefonnummer (xs:string) */
    /** @Column(type="string", name = "telephone_number", nullable=true) **/
    protected $telephoneNumber;

    /** M412 - kontaktperson (xs:string) */
    /** @Column(type="string", name = "contact_person", nullable=true) **/
    protected $contactPerson;

    /** M305 - administrativEnhet (xs:string) */
    /** @Column(type="string", name = "administrative_unit", nullable=true) **/
    protected $administrativeUnit;

    /** M307 - saksbehandler */
    /** @Column(type="string", name = "case_handler", nullable=true) **/
    protected $caseHandler;

    // Links to Records
    /** @ManyToMany(targetEntity="RegistryEntry", mappedBy="referenceCorrespondancePart", cascade={"persist", "remove"}) **/
    protected $referenceRegistryEntry;

    public function __construct()
    {
        $this->referenceRegistryEntry = new ArrayCollection();
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

    public function getCorrespondancePartName()
    {
        return $this->correspondancePartName;
    }

    public function setCorrespondancePartName($correspondancePartName)
    {
        $this->correspondancePartName = $correspondancePartName;
        return $this;
    }

    public function getCorrespondancePartType()
    {
        return $this->correspondancePartType;
    }

    public function setCorrespondancePartType($correspondancePartType)
    {
        $this->correspondancePartType = $correspondancePartType;
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

    public function getAdministrativeUnit()
    {
        return $this->administrativeUnit;
    }

    public function setAdministrativeUnit($administrativeUnit)
    {
        $this->administrativeUnit = $administrativeUnit;
        return $this;
    }

    public function getCaseHandler()
    {
        return $this->caseHandler;
    }

    public function setCaseHandler($caseHandler)
    {
        $this->caseHandler = $caseHandler;
        return $this;
    }

    public function getreferenceRegistryEntry()
    {
        return $this->referenceRegistryEntry;
    }

    public function setreferenceRegistryEntry($referenceRegistryEntry)
    {
        $this->referenceRegistryEntry = $referenceRegistryEntry;
        return $this;
    }
    public function addRecord($record) {

        if ($this->referenceRegistryEntry->contains($record)) {
            return;
        }
        $this->referenceRegistryEntry[] = $record;
    }

    public function __toString()
    {
        return __METHOD__ . " id[" . $this->id . "], " . "correspondancePartName[" . $this->correspondancePartName . "]";
    }

}

?>
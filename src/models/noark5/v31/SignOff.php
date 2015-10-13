<?php

use Doctrine\Common\Collections\ArrayCollection;
require_once ('models/noark5/v31/RegistryEntry.php');

/**
 * @Entity @Table(name="sign_off")
 **/
class SignOff
{
    /** @Id @Column(type="bigint", name="pk_sign_off_id", nullable=false) @GeneratedValue **/
    protected $id;

    /** M617 - avskrivningsdato */
    /** @Column(type="date", name = "sign_off_date", nullable=true) **/
    protected $signOffDate;

    /** M618 - avskrevetAv */
    /** @Column(type="string", name = "sign_off_name", nullable=true) **/
    protected $signOffBy;

    /** M619 - avskrivningsmaate */
    /** @Column(type="string", name = "sign_off_method", nullable=true) **/
    protected $signOffMethod;

    /** M215 referanseAvskrivesAvJournalpost */
    /** No doctrine association, it's on systemId anyway, not on normal FK/PK relationships */
    /** @Column(type="string", name = "signed_off_by_registryentry", nullable=true) **/
    protected $referenceSignedOffByRegistryEntry;

    // Links to Records
    /** @ManyToMany(targetEntity="RegistryEntry", mappedBy = "referenceSignOff") **/
    protected $referenceRecord;


    function __construct()
    {
        $this->referenceRecord = new ArrayCollection();
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

    public function getSignOffDate()
    {
        return $this->signOffDate;
    }

    public function setSignOffDate($signOffDate)
    {
        // have to convert from string object to datetime object
        $this->signOffDate = DateTime::createFromFormat(Constants::XSD_DATE_FORMAT, $signOffDate);
        return $this;
    }

    public function getSignOffBy()
    {
        return $this->signOffBy;
    }

    public function setSignOffBy($signOffBy)
    {
        $this->signOffBy = $signOffBy;
        return $this;
    }

    public function getSignOffMethod()
    {
        return $this->signOffMethod;
    }

    public function setSignOffMethod($signOffMethod)
    {
        $this->signOffMethod = $signOffMethod;
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

    public function addReferenceRecord($record)
    {
        $this->referenceRecord[] = $record;
        return $this;
    }

    public function getReferenceSignedOffByRegistryEntry()
    {
        return $this->referenceSignedOffByRegistryEntry;
    }

    public function setReferenceSignedOffByRegistryEntry($referenceSignedOffByRegistryEntry)
    {
        $this->referenceSignedOffByRegistryEntry = $referenceSignedOffByRegistryEntry;
        return $this;
    }

    public function __toString() {
        return 'SignOff id[' . $this->id . '], signOffBy[' .   $this->signOffBy . '], signOffMethod [' .   $this->signOffMethod . ']';
    }
}

?>
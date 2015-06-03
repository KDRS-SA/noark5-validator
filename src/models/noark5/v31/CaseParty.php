<?php

/**
 * @Entity @Table(name="case_party")
 **/
class CaseParty
{
    /** @Id @Column(type="bigint", name="pk_case_party_id", nullable=false) @GeneratedValue **/
    protected $id;

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

}

?>
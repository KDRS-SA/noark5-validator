<?php
use Doctrine\Common\Collections\ArrayCollection;
require_once ('models/noark5/v31/File.php');
require_once ('models/noark5/v31/CaseParty.php');

/**
 * @Entity @Table(name="case_file")
 **/

class CaseFile extends File
{

    /** M011 - saksaar (xs:integer) */
    /** @Column(type="integer", name="case_year", nullable=true) **/
	protected $caseYear;

	/** M012 - sakssekvensnummer (xs:integer) */
	/** @Column(type="integer", name="case_sequence_number", nullable=true) **/
	protected $caseSequenceNumber;

	/** M100 - saksdato (xs:date) */
	/** @Column(type="date", name="case_date", nullable=true) **/
	protected $caseDate;

	/** M305 - administrativEnhet (xs:string) */
	/** @Column(type="integer", name="administrative_unit", nullable=true) **/
	protected $administrativeUnit;

	/** M306 - saksansvarlig (xs:string) */
	/** @Column(type="string", name="case_responsible", nullable=true) **/
	protected $caseResponsible;

	/** M308 - journalenhet (xs:string) */
	/** @Column(type="string", name="records_management_unit", nullable=true) **/
	protected $recordsManagementUnit;

	/** M052 - saksstatus (xs:string) */
	/** @Column(type="string", name="case_status", nullable=true) **/
	protected $caseStatus;

	/** M106 - utlaantDato (xs:date) */
	/** @Column(type="datetime", name="loaned_date", nullable=true) **/
	protected $loanedDate;

	/** M309 - utlaantTil (xs:string) */
	/** @Column(type="string", name="loaned_to", nullable=true) **/
	protected $loanedTo;

	// Links to CaseParty
	/** @ManyToMany(targetEntity="CaseParty", fetch="EXTRA_LAZY")
	 *   @JoinTable(name="record_case_party",
	 *        joinColumns=@JoinColumn(
	 *        name="f_pk_file_id",
	 *        referencedColumnName="pk_file_id"),
	 *    inverseJoinColumns=@JoinColumn(
	 *        name="f_pk_case_party_id",
	 *        referencedColumnName="pk_case_party_id"))
	 **/
	protected $referenceCaseParty;

	/** @ManyToMany(targetEntity="Precedence", fetch="EXTRA_LAZY")
	 *   @JoinTable(name="case_file_precedence",
	 *        joinColumns=@JoinColumn(
	 *        name="f_pk_file_id",
	 *        referencedColumnName="pk_file_id"),
	 *    inverseJoinColumns=@JoinColumn(
	 *        name="f_pk_precedence",
	 *        referencedColumnName="pk_precedence"))
	 **/
	protected $referencePrecedence;

    public function __construct()
    {
        parent::__construct();
        $this->referenceCaseParty = new ArrayCollection();
        $this->referencePrecedence = new ArrayCollection();
    }

    public function getCaseYear()
    {
        return $this->caseYear;
    }

    public function setCaseYear($caseYear)
    {
        $this->caseYear = $caseYear;
        return $this;
    }

    public function getCaseSequenceNumber()
    {
        return $this->caseSequenceNumber;
    }

    public function setCaseSequenceNumber($caseSequenceNumber)
    {
        $this->caseSequenceNumber = $caseSequenceNumber;
        return $this;
    }

    public function getCaseDate()
    {
        return $this->caseDate;
    }

    public function setCaseDate($caseDate)
    {
        // have to convert from string object to datetime object
        $this->caseDate = DateTime::createFromFormat(Constants::XSD_DATE_FORMAT, $caseDate);
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

    public function getCaseResponsible()
    {
        return $this->caseResponsible;
    }

    public function setCaseResponsible($caseResponsible)
    {
        $this->caseResponsible = $caseResponsible;
        return $this;
    }

    public function getRecordsManagementUnit()
    {
        return $this->recordsManagementUnit;
    }

    public function setRecordsManagementUnit($recordsManagementUnit)
    {
        $this->recordsManagementUnit = $recordsManagementUnit;
        return $this;
    }

    public function getCaseStatus()
    {
        return $this->caseStatus;
    }

    public function setCaseStatus($caseStatus)
    {
        $this->caseStatus = $caseStatus;
        return $this;
    }

    public function getLoanedDate()
    {
        return $this->loanedDate;
    }

    public function setLoanedDate($loanedDate)
    {
        // have to convert from string object to datetime object
        $this->loanedDate = DateTime::createFromFormat(Constants::XSD_DATETIME_FORMAT, $loanedDate);
        return $this;
    }

    public function getLoanedTo()
    {
        return $this->loanedTo;
    }

    public function setLoanedTo($loanedTo)
    {
        $this->loanedTo = $loanedTo;
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

    public function getReferenceCaseParty()
    {
        return $this->referenceCaseParty;
    }

    public function setReferenceCaseParty($referenceCaseParty)
    {
        $this->referenceCaseParty = $referenceCaseParty;
        return $this;
    }

    public function addReferenceCaseParty($caseParty)
    {
        if ($this->referenceCaseParty->contains($caseParty)) {
            return;
        }
        $this->referenceCaseParty[] = $caseParty;
        $caseParty->addReferenceCaseFile($this);
        return $this;
    }

    public function getReferencePrecedence()
    {
        return $this->referencePrecedence;
    }

    public function setReferencePrecedence($referencePrecedence)
    {
        $this->referencePrecedence = $referencePrecedence;
        return $this;
    }

    public function addReferencePrecedence($precedence)
    {
        if ($this->referencePrecedence->contains($precedence)) {
            return;
        }
        $this->referencePrecedence[] = $precedence;
        $precedence->addReferenceCaseFile($this);
        return $this;
    }
}

?>
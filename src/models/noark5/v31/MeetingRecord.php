<?php
require_once ('models/noark5/v31/BasicRecord.php');


/**
 * @Entity @Table(name="meeting_record")
 **/

class MeetingRecord //extends BasicRecord
{
    /** TODO: REMOVE @Id @Column(type="bigint", name="pk_record_id", nullable=false) @GeneratedValue **/
    protected $id;

    /** M085 - moeteregistreringstype (xs:string) */
    /** @Column(type="string", name = "meeting_record_type", nullable=true) **/
    protected $meetingRecordType;

    /** M088 - moetesakstype (xs:string) */
    /** @Column(type="string", name = "meeting_ase_type", nullable=true) **/
    protected $meetingCaseType;

    /** M305 - administrativEnhet (xs:string) */
    /** @Column(type="string", name = "meeting_record_status", nullable=true) **/
    protected $meetingRecordStatus;

    /** M305 - administrativEnhet (xs:string) */
    /** @Column(type="string", name = "administrative_unit", nullable=true) **/
    protected $administrativeUnit;

    /** M307 - saksbehandler */
    /** @Column(type="string", name = "case_handler", nullable=true) **/
    protected $caseHandler;

    // Link to precursor MeetingRecord
    /** @OneToOne(targetEntity="MeetingRecord", fetch="EXTRA_LAZY", mappedBy = "referenceFromMeetingRegistration") **/
    protected $referenceToMeetingRegistration;

    // Link to successor MeetingRecord
    /** @OneToOne(targetEntity="MeetingRecord", fetch="EXTRA_LAZY", mappedBy = "referenceToMeetingRegistration") **/
    protected $referenceFromMeetingRegistration;

    public function __construct()
    {
        parent::__construct();
    }

    public function getMeetingRecordType()
    {
        return $this->meetingRecordType;
    }

    public function setMeetingRecordType($meetingRecordType)
    {
        $this->meetingRecordType = $meetingRecordType;
        return $this;
    }

    public function getMeetingCaseType()
    {
        return $this->meetingCaseType;
    }

    public function setMeetingCaseType($meetingCaseType)
    {
        $this->meetingCaseType = $meetingCaseType;
        return $this;
    }

    public function getMeetingRecordStatus()
    {
        return $this->meetingRecordStatus;
    }

    public function setMeetingRecordStatus($meetingRecordStatus)
    {
        $this->meetingRecordStatus = $meetingRecordStatus;
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

    public function getreferenceToMeetingRegistration()
    {
        return $this->referenceToMeetingRegistration;
    }

    public function setreferenceToMeetingRegistration($referenceToMeetingRegistration)
    {
        $this->referenceToMeetingRegistration = $referenceToMeetingRegistration;
        return $this;
    }

    public function getreferenceFromMeetingRegistration()
    {
        return $this->referenceFromMeetingRegistration;
    }

    public function setreferenceFromMeetingRegistration($referenceFromMeetingRegistration)
    {
        $this->referenceFromMeetingRegistration = $referenceFromMeetingRegistration;
        return $this;
    }

}

?>
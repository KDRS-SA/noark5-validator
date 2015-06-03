<?php
require_once ('models/noark5/v31/File.php');


/**
 * @Entity @Table(name="meeting_file")
 **/

class MeetingFile extends File
{
    /** M008 - moetenummer (xs:string) */
    /** @Column(type="string", name="meeting_number", nullable=true) **/
    protected $meetingNumber;

    /** M370 - utvalg (xs:string) */
    /** @Column(type="string", name="committee", nullable=true) **/
    protected $committee;

    /** M102 - moetedato (xs:date) */
    /** @Column(type="datetime", name="loaned_date", nullable=true) **/
    protected $meetingDate;

    /** M371 - moetested (xs:string) */
    /** @Column(type="string", name="meeting_place", nullable=true) **/
    protected $meetingPlace;

    // Link to precursor MeetingRecord
    /** @OneToOne(targetEntity="MeetingRecord", fetch="EXTRA_LAZY", mappedBy = "referanceFromMeetingRegistration") **/
    protected $referenceNextMeeting;

    // Link to successor MeetingRecord
    /** @OneToOne(targetEntity="MeetingRecord", fetch="EXTRA_LAZY", mappedBy = "referanceToMeetingRegistration") **/
    protected $referencePreviousMeeting;

    public function __construct()
    {
        parent::__construct();
    }

    public function getMeetingNumber()
    {
        return $this->meetingNumber;
    }

    public function setMeetingNumber($meetingNumber)
    {
        $this->meetingNumber = $meetingNumber;
        return $this;
    }

    public function getCommittee()
    {
        return $this->committee;
    }

    public function setCommittee($committee)
    {
        $this->committee = $committee;
        return $this;
    }

    public function getMeetingDate()
    {
        return $this->meetingDate;
    }

    public function setMeetingDate($meetingDate)
    {
        // have to convert from string object to datetime object
        $this->meetingDate = DateTime::createFromFormat(Constants::XSD_DATE_FORMAT, $meetingDate);
        return $this;
    }

    public function getMeetingPlace()
    {
        return $this->meetingPlace;
    }

    public function setMeetingPlace($meetingPlace)
    {
        $this->meetingPlace = $meetingPlace;
        return $this;
    }

    public function getReferenceNextMeeting()
    {
        return $this->referenceNextMeeting;
    }

    public function setReferenceNextMeeting($referenceNextMeeting)
    {
        $this->referenceNextMeeting = $referenceNextMeeting;
        return $this;
    }

    public function getReferencePreviousMeeting()
    {
        return $this->referencePreviousMeeting;
    }

    public function setReferencePreviousMeeting($referencePreviousMeeting)
    {
        $this->referencePreviousMeeting = $referencePreviousMeeting;
        return $this;
    }

}

?>
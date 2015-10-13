<?php
use Doctrine\Common\Collections\ArrayCollection;
require_once ('models/noark5/v31/File.php');

/**
 * @Entity @Table(name="meeting_file")
 *
 * NOTE: referenceNextMeeting and referenceNextMeeting are stored as strings
 * in the table. Ideally they should be a one2one relationship, but it is not
 * possible to link objects together that haven't been processed and might not
 * actually be in the XML-file.
 *
 * The premeetings will probablt be OK, except for the case where a new arkivdel
 * starts and the pre meeting is in an older arkivdel. The same with the post meeting.
 * But the post meeting have not yet been processed and in linear processing will end
 * up being null.
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

    /** M221 - referanseForrigeMoete (xs:string) **/
    protected $referenceNextMeeting;

    /** M222 - referanseNesteMoete (xs:string) **/
    protected $referencePreviousMeeting;

    // Link to MeetingRecord
    /** @OneToMany(targetEntity="MeetingParticipant", mappedBy="referenceMeetingFile", fetch="EXTRA_LAZY") **/
    protected $referenceMeetingRecord;

    // Links to MeetingParticipant
    /** @OneToMany(targetEntity="MeetingParticipant", mappedBy="referenceMeetingFile", fetch="EXTRA_LAZY") **/
    protected $referenceMeetingParticipant;

    public function __construct()
    {
        parent::__construct();
        $this->referenceMeetingParticipant = new ArrayCollection();
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

    public function getReferenceMeetingRecord()
    {
        return $this->referenceMeetingRecord;
    }

    public function setReferenceMeetingRecord($referenceMeetingRecord)
    {
        $this->referenceMeetingRecord = $referenceMeetingRecord;
        return $this;
    }

    public function getReferenceMeetingParticipant()
    {
        return $this->referenceMeetingParticipant;
    }

    public function setReferenceMeetingParticipant($referenceMeetingParticipant)
    {
        $this->referenceMeetingParticipant = $referenceMeetingParticipant;
        return $this;
    }

    public function addReferenceMeetingParticipant($meetingParticipant)
    {

        if ($this->referenceMeetingParticipant->contains($meetingParticipant)) {
            return $this;
        }

        $this->referenceMeetingParticipant[] = $meetingParticipant;
        return $this;
    }

}

?>
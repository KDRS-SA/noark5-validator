<?php
require_once ('models/noark5/v31/MeetingFile.php');

/**
 * @Entity @Table(name="meeting_participant")
 */
class MeetingParticipant
{

    /**
     * @Id @Column(type="bigint", name="pk_meeting_participant_id", nullable=false) @GeneratedValue *
     */
    protected $id;

    /**
     * M372 - moetedeltakerNavn (xs:string)
     */
    /**
     * @Column(type="string", name = "meeting_participant_name", nullable=true) *
     */
    protected $meetingParticipantName;

    /**
     * M373 - moetedeltakerFunksjon (xs:string)
     */
    /**
     * @Column(type="string", name = "meeting_participant_function", nullable=true) *
     */
    protected $meetingParticipantFunction;

    // Link to MeetingFile
    /**
     * @ManyToOne(targetEntity="MeetingFile", fetch="EXTRA_LAZY")
     * @JoinColumn(name="meeting_participant_meeting_file_id",
     * referencedColumnName="pk_file_id")
     */
    protected $referenceMeetingFile;

    public function __construct()
    {}

    public function getId()
    {
        return $this->id;
    }

    public function getMeetingParticipantName()
    {
        return $this->meetingParticipantName;
    }

    public function setMeetingParticipantName($meetingParticipantName)
    {
        $this->meetingParticipantName = $meetingParticipantName;
        return $this;
    }

    public function getMeetingParticipantFunction()
    {
        return $this->meetingParticipantFunction;
    }

    public function setMeetingParticipantFunction($meetingParticipantFunction)
    {
        $this->meetingParticipantFunction = $meetingParticipantFunction;
        return $this;
    }

    public function getReferenceMeetingFile()
    {
        return $this->referenceMeetingFile;
    }

    public function setReferenceMeetingFile($referenceMeetingFile)
    {
        $this->referenceMeetingFile = $referenceMeetingFile;
        return $this;
    }

    public function __toString()
    {
        return 'id [' . $this->id . '], meetingParticipantName[' . $this->meetingParticipantName . '], meetingParticipantFunction[' . $this->meetingParticipantFunction . ']';
    }
}

?>
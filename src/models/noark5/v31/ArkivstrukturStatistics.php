<?php

class ArkivstrukturStatistics
{
    /**
     *
     * @var int $numberOfFondsProcessed: The number of Fonds <arkiv> elements that are processed
     */
    public $numberOfFondsProcessed = 0;

    /**
     *
     * @var int $numberOfFondsCreatorProcessed: The number of FondsCreator <arkivskaper> elements that are processed
     */
    public $numberOfFondsCreatorProcessed = 0;

    /**
     *
     * @var int $numberOfSeriesProcessed: The number of Series, <arkivdel> elements that are processed
     */
    public $numberOfSeriesProcessed = 0;

    /**
     *
     * @var int $numberOfClassificationSystemProcessed: The number of ClassificationSystem, <klassifikasjonssystem,> elements that are processed
     */
    public $numberOfClassificationSystemProcessed = 0;

    /**
     *
     * @var int $numberOfClassProcessed: The number of Class, <klasse> elements that are processed
     */
    public $numberOfClassProcessed = 0;

    /**
     *
     * @var int $numberOfFileProcessed: The number of File, <mappe> elements that are processed
     */
    public $numberOfFileProcessed = 0;

    /**
     *
     * @var int $numberOfCaseFileProcessed: The number of CaseFile, <saksmappe> elements that are processed
     */
    public $numberOfCaseFileProcessed = 0;

    /**
     *
     * @var int $numberOfMeetingFileProcessed: The number of MeetingFile, <moetemappe> elements that are processed
     */
    public $numberOfMeetingFileProcessed = 0;

    /**
     *
     * @var int $numberOfRecordProcessed: The number of Record, <registrering> elements that are processed
     */
    public $numberOfRecordProcessed = 0;

    /**
     *
     * @var int $numberOfBasicRecordProcessed: The number of BasicRecord, <basisregistrering> elements that are processed
     */
    public $numberOfBasicRecordProcessed = 0;

    /**
     *
     * @var int $numberOfRegistryEntryProcessed: The number of RegistryEntry, <journalpost> elements that are processed
     */
    public $numberOfRegistryEntryProcessed = 0;

    /**
     *
     * @var int $numberOfMeetingRecordProcessed: The number of MeetingRecord, <moeteregistrering> elements that are processed
     */
    public $numberOfMeetingRecordProcessed = 0;

    /**
     *
     * @var int $numberOfDocumentDescriptionProcessed: The number of DocumentDescription, <dokumentbeskrivelse> elements that are processed
     */
    public $numberOfDocumentDescriptionProcessed = 0;

    /**
     *
     * @var int $numberOfDocumentObjectProcessed: The number of DocumentObject, <documentobjekt> elements that are processed
     */
    public $numberOfDocumentObjectProcessed = 0;

    /**
     *
     * @var int $numberOfSignOffProcessed: The number of SignOff, <avskrivning> elements that are processed
     */
    public $numberOfSignOffProcessed = 0;

    /**
     *
     * @var int $numberOfCorrespondancePartProcessed: The number of CorrespondancePart, <korrespondansepart> elements that are processed
     */
    public $numberOfCorrespondancePartProcessed = 0;

    /**
     *
     * @var int $numberOfClassificationProcessed: The number of Classification, <gradering> elements that are processed
     */
    public $numberOfClassificationProcessed = 0;

    /**
     *
     * @var int $numberOfDeletionProcessed: The number of Deletion, <sletting> elements that are processed
     */
    public $numberOfDeletionProcessed = 0;

    /**
     *
     * @var int $numberOfDisposalProcessed: The number of Disposal, <kassasjon> elements that are processed
     */
    public $numberOfDisposalProcessed = 0;

    /**
     *
     * @var int $numberOfDisposalUndertakenProcessed: The number of DisposalUndertaken, <utfoertKassasjon> elements that are processed
     */
    public $numberOfDisposalUndertakenProcessed = 0;

    /**
     *
     * @var int $numberOfPrecedenceProcessed: The number of Precedence, <presedens> elements that are processed
     */
    public $numberOfPrecedenceProcessed = 0;

    /**
     *
     * @var int $numberOfCrossReferenceProcessed: The number of CrossReference, <kryssreferanse> elements that are processed
     */
    public $numberOfCrossReferenceProcessed = 0;

    /**
     *
     * @var int $numberOfElectronicSignatureProcessed: The number of ElectronicSignature, <elektroniskSignatur> elements that are processed
     */
    public $numberOfElectronicSignatureProcessed = 0;

    /**
     *
     * @var int $numberOfScreeningProcessed: The number of Screening, <skjerming> elements that are processed
     */
    public $numberOfScreeningProcessed = 0;

    /**
     *
     * @var int $numberOfCommentProcessed: The number of Comment, <merknad> elements that are processed
     */
    public $numberOfCommentProcessed = 0;

    /**
     *
     * @var int $numberOfConversionProcessed: The number of Conversion, <konvertering> elements that are processed
     */
    public $numberOfConversionProcessed = 0;

    /**
     *
     * @var int $numberOfCasePartyProcessed: The number of CaseParty, <sakspart> elements that are processed
     */
    public $numberOfCasePartyProcessed = 0;

    /**
     *
     * @var int $numberOfWorkflowProcessed: The number of Workflow, <dokumentflyt> elements that are processed
     */
    public $numberOfWorkflowProcessed = 0;

    /**
     *
     * @var int $numberOfCorrespondencePartProcessed: The number of CorrespondencePart, <korrespondansepart> elements that are processed
     */
    public $numberOfCorrespondencePartProcessed = 0;

    /**
     *
     * @var int $numberOfMeetingParticipantProcessed: The number of MeetingParticipant, <moetedeltager> elements that are processed
     */
    public $numberOfMeetingParticipantProcessed = 0;
    function __construct() {}

    public function getNumberOfFondsProcessed()
    {
        return $this->numberOfFondsProcessed;
    }

    public function setNumberOfFondsProcessed($numberOfFondsProcessed)
    {
        $this->numberOfFondsProcessed = $numberOfFondsProcessed;
        return $this;
    }

    public function getNumberOfFondsCreatorProcessed()
    {
        return $this->numberOfFondsCreatorProcessed;
    }

    public function setNumberOfFondsCreatorProcessed($numberOfFondsCreatorProcessed)
    {
        $this->numberOfFondsCreatorProcessed = $numberOfFondsCreatorProcessed;
        return $this;
    }

    public function getNumberOfSeriesProcessed()
    {
        return $this->numberOfSeriesProcessed;
    }

    public function setNumberOfSeriesProcessed($numberOfSeriesProcessed)
    {
        $this->numberOfSeriesProcessed = $numberOfSeriesProcessed;
        return $this;
    }

    public function getNumberOfClassificationSystemProcessed()
    {
        return $this->numberOfClassificationSystemProcessed;
    }

    public function setNumberOfClassificationSystemProcessed($numberOfClassificationSystemProcessed)
    {
        $this->numberOfClassificationSystemProcessed = $numberOfClassificationSystemProcessed;
        return $this;
    }

    public function getNumberOfClassProcessed()
    {
        return $this->numberOfClassProcessed;
    }

    public function setNumberOfClassProcessed($numberOfClassProcessed)
    {
        $this->numberOfClassProcessed = $numberOfClassProcessed;
        return $this;
    }

    public function getNumberOfFileProcessed()
    {
        return $this->numberOfFileProcessed;
    }

    public function setNumberOfFileProcessed($numberOfFileProcessed)
    {
        $this->numberOfFileProcessed = $numberOfFileProcessed;
        return $this;
    }

    public function getNumberOfCaseFileProcessed()
    {
        return $this->numberOfCaseFileProcessed;
    }

    public function setNumberOfCaseFileProcessed($numberOfCaseFileProcessed)
    {
        $this->numberOfCaseFileProcessed = $numberOfCaseFileProcessed;
        return $this;
    }

    public function getNumberOfMeetingFileProcessed()
    {
        return $this->numberOfMeetingFileProcessed;
    }

    public function setNumberOfMeetingFileProcessed($numberOfMeetingFileProcessed)
    {
        $this->numberOfMeetingFileProcessed = $numberOfMeetingFileProcessed;
        return $this;
    }

    public function getNumberOfRecordProcessed()
    {
        return $this->numberOfRecordProcessed;
    }

    public function setNumberOfRecordProcessed($numberOfRecordProcessed)
    {
        $this->numberOfRecordProcessed = $numberOfRecordProcessed;
        return $this;
    }

    public function getNumberOfBasicRecordProcessed()
    {
        return $this->numberOfBasicRecordProcessed;
    }

    public function setNumberOfBasicRecordProcessed($numberOfBasicRecordProcessed)
    {
        $this->numberOfBasicRecordProcessed = $numberOfBasicRecordProcessed;
        return $this;
    }

    public function getNumberOfRegistryEntryProcessed()
    {
        return $this->numberOfRegistryEntryProcessed;
    }

    public function setNumberOfRegistryEntryProcessed($numberOfRegistryEntryProcessed)
    {
        $this->numberOfRegistryEntryProcessed = $numberOfRegistryEntryProcessed;
        return $this;
    }

    public function getNumberOfMeetingRecordProcessed()
    {
        return $this->numberOfMeetingRecordProcessed;
    }

    public function setNumberOfMeetingRecordProcessed($numberOfMeetingRecordProcessed)
    {
        $this->numberOfMeetingRecordProcessed = $numberOfMeetingRecordProcessed;
        return $this;
    }

    public function getNumberOfDocumentDescriptionProcessed()
    {
        return $this->numberOfDocumentDescriptionProcessed;
    }

    public function setNumberOfDocumentDescriptionProcessed($numberOfDocumentDescriptionProcessed)
    {
        $this->numberOfDocumentDescriptionProcessed = $numberOfDocumentDescriptionProcessed;
        return $this;
    }

    public function getNumberOfDocumentObjectProcessed()
    {
        return $this->numberOfDocumentObjectProcessed;
    }

    public function setNumberOfDocumentObjectProcessed($numberOfDocumentObjectProcessed)
    {
        $this->numberOfDocumentObjectProcessed = $numberOfDocumentObjectProcessed;
        return $this;
    }

    public function getNumberOfSignOffProcessed()
    {
        return $this->numberOfSignOffProcessed;
    }

    public function setNumberOfSignOffProcessed($numberOfSignOffProcessed)
    {
        $this->numberOfSignOffProcessed = $numberOfSignOffProcessed;
        return $this;
    }

    public function getNumberOfCorrespondancePartProcessed()
    {
        return $this->numberOfCorrespondancePartProcessed;
    }

    public function setNumberOfCorrespondancePartProcessed($numberOfCorrespondancePartProcessed)
    {
        $this->numberOfCorrespondancePartProcessed = $numberOfCorrespondancePartProcessed;
        return $this;
    }

    public function getNumberOfClassificationProcessed()
    {
        return $this->numberOfClassificationProcessed;
    }

    public function setNumberOfClassificationProcessed($numberOfClassificationProcessed)
    {
        $this->numberOfClassificationProcessed = $numberOfClassificationProcessed;
        return $this;
    }

    public function getNumberOfDeletionProcessed()
    {
        return $this->numberOfDeletionProcessed;
    }

    public function setNumberOfDeletionProcessed($numberOfDeletionProcessed)
    {
        $this->numberOfDeletionProcessed = $numberOfDeletionProcessed;
        return $this;
    }

    public function getNumberOfDisposalProcessed()
    {
        return $this->numberOfDisposalProcessed;
    }

    public function setNumberOfDisposalProcessed($numberOfDisposalProcessed)
    {
        $this->numberOfDisposalProcessed = $numberOfDisposalProcessed;
        return $this;
    }

    public function getNumberOfDisposalUndertakenProcessed()
    {
        return $this->numberOfDisposalUndertakenProcessed;
    }

    public function setNumberOfDisposalUndertakenProcessed($numberOfDisposalUndertakenProcessed)
    {
        $this->numberOfDisposalUndertakenProcessed = $numberOfDisposalUndertakenProcessed;
        return $this;
    }

    public function getNumberOfPrecedenceProcessed()
    {
        return $this->numberOfPrecedenceProcessed;
    }

    public function setNumberOfPrecedenceProcessed($numberOfPrecedenceProcessed)
    {
        $this->numberOfPrecedenceProcessed = $numberOfPrecedenceProcessed;
        return $this;
    }

    public function getNumberOfCrossReferenceProcessed()
    {
        return $this->numberOfCrossReferenceProcessed;
    }

    public function setNumberOfCrossReferenceProcessed($numberOfCrossReferenceProcessed)
    {
        $this->numberOfCrossReferenceProcessed = $numberOfCrossReferenceProcessed;
        return $this;
    }

    public function getNumberOfElectronicSignatureProcessed()
    {
        return $this->numberOfElectronicSignatureProcessed;
    }

    public function setNumberOfElectronicSignatureProcessed($numberOfElectronicSignatureProcessed)
    {
        $this->numberOfElectronicSignatureProcessed = $numberOfElectronicSignatureProcessed;
        return $this;
    }

    public function getNumberOfScreeningProcessed()
    {
        return $this->numberOfScreeningProcessed;
    }

    public function setNumberOfScreeningProcessed($numberOfScreeningProcessed)
    {
        $this->numberOfScreeningProcessed = $numberOfScreeningProcessed;
        return $this;
    }

    public function getNumberOfCommentProcessed()
    {
        return $this->numberOfCommentProcessed;
    }

    public function setNumberOfCommentProcessed($numberOfCommentProcessed)
    {
        $this->numberOfCommentProcessed = $numberOfCommentProcessed;
        return $this;
    }

    public function getNumberOfConversionProcessed()
    {
        return $this->numberOfConversionProcessed;
    }

    public function setNumberOfConversionProcessed($numberOfConversionProcessed)
    {
        $this->numberOfConversionProcessed = $numberOfConversionProcessed;
        return $this;
    }

    public function getNumberOfCasePartyProcessed()
    {
        return $this->numberOfCasePartyProcessed;
    }

    public function setNumberOfCasePartyProcessed($numberOfCasePartyProcessed)
    {
        $this->numberOfCasePartyProcessed = $numberOfCasePartyProcessed;
        return $this;
    }

    public function getNumberOfWorkflowProcessed()
    {
        return $this->numberOfWorkflowProcessed;
    }

    public function setNumberOfWorkflowProcessed($numberOfWorkflowProcessed)
    {
        $this->numberOfWorkflowProcessed = $numberOfWorkflowProcessed;
        return $this;
    }
}

?>
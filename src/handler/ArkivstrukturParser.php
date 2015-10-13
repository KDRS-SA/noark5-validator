<?php
require_once ('models/noark5/v31/BasicRecord.php');
require_once ('models/noark5/v31/CaseFile.php');
require_once ('models/noark5/v31/CaseParty.php');
require_once ('models/noark5/v31/Classified.php');
require_once ('models/noark5/v31/ClassificationSystem.php');
require_once ('models/noark5/v31/Comment.php');
require_once ('models/noark5/v31/Conversion.php');
require_once ('models/noark5/v31/CorrespondencePart.php');
require_once ('models/noark5/v31/CrossReference.php');
require_once ('models/noark5/v31/Deletion.php');
require_once ('models/noark5/v31/Disposal.php');
require_once ('models/noark5/v31/DisposalUndertaken.php');
require_once ('models/noark5/v31/DocumentObject.php');
require_once ('models/noark5/v31/DocumentDescription.php');
require_once ('models/noark5/v31/ElectronicSignature.php');
require_once ('models/noark5/v31/File.php');
require_once ('models/noark5/v31/Fonds.php');
require_once ('models/noark5/v31/FondsCreator.php');
require_once ('models/noark5/v31/Keyword.php');
require_once ('models/noark5/v31/Klass.php');
require_once ('models/noark5/v31/MeetingFile.php');
require_once ('models/noark5/v31/MeetingParticipant.php');
require_once ('models/noark5/v31/MeetingRecord.php');
require_once ('models/noark5/v31/Precedence.php');
require_once ('models/noark5/v31/Record.php');
require_once ('models/noark5/v31/RegistryEntry.php');
require_once ('models/noark5/v31/Screening.php');
require_once ('models/noark5/v31/Series.php');
require_once ('models/noark5/v31/SignOff.php');
require_once ('models/noark5/v31/Workflow.php');
require_once ('tests/file/ChecksumTest.php');
require_once ('vendor/apache/log4php/src/main/php/Logger.php');
require_once ('models/noark5/v31/ArkivstrukturStatistics.php');

/*
 * This is a pretty straight forward implementation of callback methods
 * for the SAX based parser created by the command xml_parser_create()
 *
 * At a higher level, there are three important functions startElement,
 * endElement and cdata. startElement is called every time a new element
 * is detected. We distinguish between the noark 5 complexTypes (arkiv, arkivdel)
 * etc and simpleTypes (title, systemID) etc. The complexTypes are listed first and
 * are pushed to a stack ($stack) to keep track of where we are in the XML-file.
 * endElement pops the stack and there is a check on type to ensure we are in sync
 * with the file.
 *
 * Only a few of the functions are documented in detail.
 *
 * The methods in this class are as follows. First we have the startElement, endElement and cdata
 * for parsing, followed by checkObjectClassTypeCorrect. Then all the handle methods for the various
 * simpletypes, followed by a list of pre and post process methods that can be overridden and finally
 * the getter and setters for the counts. If you want to build a test on top of this parser, you should
 * probably be overriding a post processor of a Noark 5 complex type and or one of the handle methods.
 *
 * NOTE: You will not be able to access all the simpleElements (systemId) in a noark 5 complexType (arkiv
 * arkivdel etc) until the post process event has occured. A preprocess method is invoked when the start tag
 * is seen, a postprocess when the closing tag in encountered.
 *
 * NOTE: Only values specified belonging to arkivstruktur.xml validated by arkivstruktur.xsd will be found
 *       here. Other values in the metadatacatlog are not included. e.g M580, brukerNavn
 *       The same applies to fields belonging to endringslogg.xml etc
 *
 */
class ArkivstrukturParser
{

    /**
     *
     * @var array $stack: Stack that holds parsed Noark 5 objects (complexTypes, simpleTypes are object variables)
     */
    protected $stack;

    /**
     *
     * @var string $currentCdata: Stack that holds parsed Noark 5 objects (complexTypes, simpleTypes are object variables)
     */
    protected $currentCdata;

    /**
     *
     * @var ArkivstrukturStatistics $statistics: Holds counts of the various complexTypes encountered
     */
    protected $statistics;

    /**
     *
     * @var boolean $graderingIsSimpleType:  arkivstruktur.xsd has <gradering> both as a complexType
                                             and as a simpleType. This makes handling both <gradering>
                                             a bit more difficult as I have to keep track of whether
                                             or not the current <gradering> is the simpleType.
     */
    protected $graderingIsSimpleType = false;

    /**
     *
     * @var Logger $logger: The Log4Php logger object
     */
    protected $logger;

    /**
     *
     * @var boolean $errorsEncountered: Whether or not errors were encountered. Not used locally,
     *                                  but intended for subclasses
     */
    protected $errorsEncountered = false;

    /**
     *
     * @var int $numberErrorsEncountered: The number of errors that were encountered. Not used locally,
     *                                    but intended for subclasses
     */
    protected $numberErrorsEncountered = 0;


    function __construct()
    {
        $this->stack = array();
        $this->currentCdata = "";
        $this->statistics = new ArkivstrukturStatistics();
        $this->logger = Logger::getLogger($GLOBALS['toolboxLogger']);
        $this->logger->trace('Constructing an instance of ' . get_class($this));
    }

    /**
     * startElement is called whenever the parser encounters a opening tag.
     * Here
     * we are only interested in handling the opening tag of a Noark 5 complexType.
     * This is done to create an instance of a Noark 5 object (no:arkivenhet, e.g arkiv)
     * that is added to the stack so that the subsequent simpleType (e.g systemId) elements
     * that are found can be set to the object (that is at the top of the queue).
     * This function provides a handle method (e.g. preProcessFonds) for subclasses to call
     * when processing a Noark 5 arkivstruktur.xml file.
     *
     * @param xml_parser $parser
     *            Link to parser, variable is not used
     *
     * @param string $tag
     *            The actual tag that has been encountered
     *
     * @param array $attributes
     *            An array of attributes contained within the element
     *
     */
    function startElement($parser, $tag, $attributes)
    {
        $this->logger->trace('Processing startElement ' . $tag);
        switch ($tag) {
            case 'arkiv':
                $this->stack[] = new Fonds();
                $this->preProcessFonds();
                break;
            case 'arkivskaper':
                $this->stack[] = new FondsCreator();
                $this->preProcessFondsCreator();
                break;
            case 'arkivdel':
                $this->stack[] = new Series();
                $this->preProcessSeries();
                break;
            case 'klassifikasjonssystem':
                $this->stack[] = new ClassificationSystem();
                $this->preProcessClassificationSystem();
                break;
            case 'klasse':
                $this->stack[] = new Klass();
                $this->preProcessClass();
                break;
            case 'mappe':
                $classType = 'File';
                if (count($attributes) > 0) {
                    if (isset($attributes['xsi:type']) == true) {
                        if (strcmp($attributes['xsi:type'], 'saksmappe') == 0) {
                            $this->stack[] = new CaseFile();
                            $classType = 'CaseFile';
                        } elseif (strcmp($attributes['xsi:type'], 'moetemappe') == 0) {
                            $this->stack[] = new MeetingFile();
                            $classType = 'MeetingFile';
                        } else {
                            $this->logger->error(Constants::EXCEPTION_UNKNOWN_NOARK5_OBJECT . ' Cannot handle mappe xsi:type = ' . $attributes['xsi:type']);
                            throw new Exception(Constants::EXCEPTION_UNKNOWN_NOARK5_OBJECT . ' Cannot handle mappe xsi:type = ' . $attributes['xsi:type']);
                        }
                    }
                } else {
                    $this->stack[] = new File();
                }
                $this->preProcessFile($classType);
                break;
            case 'registrering':
                $classType = 'Record';
                if (count($attributes) > 0) {
                    if (isset($attributes['xsi:type']) == true) {
                        if (strcmp($attributes['xsi:type'], 'basisregistrering') == 0) {
                            $this->stack[] = new BasicRecord();
                            $classType = 'BasicRecord';
                        } elseif (strcmp($attributes['xsi:type'], 'journalpost') == 0) {
                            $this->stack[] = new RegistryEntry();
                            $classType = 'RegistryEntry';
                        } elseif (strcmp($attributes['xsi:type'], 'moeteregistrering') == 0) {
                            $this->stack[] = new MeetingRecord();
                            $classType = 'MeetingRecord';
                        } else {
                            $this->logger->error(Constants::EXCEPTION_UNKNOWN_NOARK5_OBJECT . ' Cannot handle registrering xsi:type = ' . $attributes['xsi:type']);
                            throw new Exception(Constants::EXCEPTION_UNKNOWN_NOARK5_OBJECT . ' Cannot handle registrering xsi:type = ' . $attributes['xsi:type']);
                        }
                    }
                } else {
                    $this->stack[] = new Record();
                }
                $this->preProcessRecord($classType);
                break;
            case 'korrespondansepart':
                $this->stack[] = new CorrespondencePart();
                $this->preProcessCorrespondencePart();
                break;
            case 'avskrivning':
                $this->stack[] = new SignOff();
                $this->preProcessSignOff();
                break;
            case 'dokumentflyt':
                $this->stack[] = new Workflow();
                $this->preProcessWorkflow();
                break;
            case 'presedens':
                $this->stack[] = new Precedence();
                $this->preProcessPrecedence();
                break;
            case 'dokumentbeskrivelse':
                $this->stack[] = new DocumentDescription();
                $this->preProcessDocumentDescription();
                break;
            case 'dokumentobjekt':
                $this->stack[] = new DocumentObject();
                $this->preProcessDocumentObject();
                break;
            case 'elektroniskSignatur':
                $this->stack[] = new ElectronicSignature();
                $this->preProcessElectornicSignature();
                break;
            case 'gradering':
                /**
                 * NOTE: arkivstruktur.xsd has <gradering> both as a complexType
                 * and as a simpleType. This makes handling the element a little
                 * more complex in an event-based parser. Here we check to see if
                 * the head of the stack has an object of type gradering and if it
                 * does then this <gradering> element is ignored.
                 * $this->graderingIsSimpleType is set to true so that we know
                 * the endElement will be processed accordingly.
                 */

                if (get_class(end($this->stack)) === "Classified") {
                    $this->graderingIsSimpleType = true;
                }
                else {
                    $this->graderingIsSimpleType = false;
                    $this->stack[] = new Classified();
                    $this->preProcessClassified();
                }
                break;
            case 'kassasjon':
                $this->stack[] = new Disposal();
                $this->preProcessDisposal();
                break;
            case 'konvertering':
                $this->stack[] = new Conversion();
                $this->preProcessConversion();
                break;
            case 'kryssreferanse':
                $this->stack[] = new CrossReference();
                $this->preProcessCrossReference();
                break;
            case 'merknad':
                $this->stack[] = new Comment();
                $this->preProcessComment();
                break;
            case 'presedens':
                $this->stack[] = new Precedence();
                $this->preProcessPrecedence();
                break;
            case 'skjerming':
                $this->stack[] = new Screening();
                $this->preProcessScreening();
                break;
            case 'sletting':
                $this->stack[] = new Deletion();
                $this->preProcessDeletion();
                break;
            case 'utfoertKassasjon':
                $this->stack[] = new DisposalUndertaken();
                $this->preProcessDisposalUndertaken();
                break;
            case 'sakspart':
                $this->stack[] = new CaseParty();
                $this->preProcessCaseParty();
                break;
            case 'moetedeltaker':
                $this->stack[]  = new MeetingParticipant();
                $this->preProcessMeetingParticipant();
                break;
        }
    }

    /**
     * endElement is called whenever the parser encounters a closing tag.
     * Here
     * we are interested in handling both the closing tag of a Noark 5 complexType as well
     * as that of simpleType.
     * A simple check that the head of the stack is in sync with the xml file is undertaken
     * as well as calling a postProcess() function for the complexTypes and a handle function
     * for the simpleTypes. The handle functions copy the value in currentCdata to the appropriate
     * variable in the Noark 5 object. $this->currentCdata gets its value from the cdata function
     *
     * These handle and postProcess functions are very useful when creating a subclass.
     *
     * Note: Only when you have processed the end tag, will you actually have a complete instance
     * of a Noark 5 object (no:arkivenhet)
     *
     * Note: It is important that this function resets currentCdata to an empty value, "". Otherwise
     * you will have problems with your element values. This is done in the last statement of
     * this function.
     *
     * @param xml_parser $parser
     *            Link to parser, not used
     *
     * @param string $tag
     *            The actual tag that has been encountered
     *
     */
    function endElement($parser, $tag)
    {
        $this->logger->trace('Processing endElement ' . $tag);
        switch ($tag) {
            case 'arkiv':
                $this->checkObjectClassTypeCorrect('Fonds');
                $this->postProcessFonds();
                $this->statistics->numberOfFondsProcessed++;
                array_pop($this->stack);
                break;
            case 'arkivdel':
                $this->checkObjectClassTypeCorrect('Series');
                $this->postProcessSeries();
                $this->statistics->numberOfSeriesProcessed++;
                array_pop($this->stack);
                break;
            case 'mappe':
                $classType = get_class(end($this->stack));

                if (strcasecmp($classType, 'CaseFile') == 0) {
                    $this->checkObjectClassTypeCorrect('CaseFile');
                    $this->statistics->numberOfFileProcessed++;
                } elseif (strcasecmp($classType, 'File') == 0) {
                    $this->checkObjectClassTypeCorrect('File');
                    $this->statistics->numberOfCaseFileProcessed++;
                } elseif (strcasecmp($classType, 'MeetingFile') == 0) {
                    $this->checkObjectClassTypeCorrect('MeetingFile');
                    $this->statistics->numberOfMeetingFileProcessed++;
                } else {
                    $this->logger->error('Unable to process a specific mappe type. Type identified as (' . $classType . ')');
                    throw new Exception('Unable to process a specific mappe type. Type identified as (' . $classType . ')');
                }
                $this->postProcessFile($classType);
                array_pop($this->stack);
                break;
            case 'registrering':
                $classType = get_class(end($this->stack));

                if (strcasecmp($classType, 'Record') == 0) {
                    $this->checkObjectClassTypeCorrect('Record');
                    $this->statistics->numberOfRecordProcessed++;
                } elseif (strcasecmp($classType, 'BasicRecord') == 0) {
                    $this->checkObjectClassTypeCorrect('BasicRecord');
                    $this->statistics->numberOfBasicRecordProcessed++;
                } elseif (strcasecmp($classType, 'RegistryEntry') == 0) {
                    $this->checkObjectClassTypeCorrect('RegistryEntry');
                    $this->statistics->numberOfRegistryEntryProcessed++;
                } elseif (strcasecmp($classType, 'MeetingRecord') == 0) {
                    $this->checkObjectClassTypeCorrect('MeetingRecord');
                    $this->statistics->numberOfMeetingRecordProcessed++;
                } else {
                    $this->logger->error('Unable to process a specific registrering type. Type identified as (' . $classType . ')');
                    throw new Exception('Unable to process a specific registrering type. Type identified as (' . $classType . ')');
                }
                $this->postProcessRecord($classType);
                array_pop($this->stack);
                break;
            case 'korrespondansepart':
                $this->checkObjectClassTypeCorrect('CorrespondencePart');
                $this->statistics->numberOfCorrespondencePartProcessed++;
                $this->postProcessCorrespondencePart();
                array_pop($this->stack);
                break;
            case 'avskrivning':
                $this->checkObjectClassTypeCorrect('SignOff');
                $this->postProcessSignOff();
                $this->statistics->numberOfSignOffProcessed++;
                array_pop($this->stack);
                break;
            case 'presedens':
                $this->checkObjectClassTypeCorrect('Precedence');
                $this->postProcessPrecedence();
                $this->statistics->numberOfPrecedenceProcessed++;
                array_pop($this->stack);
                break;
            case 'dokumentbeskrivelse':
                $this->checkObjectClassTypeCorrect('DocumentDescription');
                $this->postProcessDocumentDescription();
                $this->statistics->numberOfDocumentDescriptionProcessed++;
                array_pop($this->stack);
                break;
            case 'dokumentobjekt':
                $this->checkObjectClassTypeCorrect('DocumentObject');
                $this->statistics->numberOfDocumentObjectProcessed++;
                $this->postProcessDocumentObject();
                array_pop($this->stack);
                break;
            case 'arkivskaper':
                $this->checkObjectClassTypeCorrect('FondsCreator');
                $this->postProcessFondsCreator();
                $this->statistics->numberOfFondsCreatorProcessed++;
                array_pop($this->stack);
                break;
            case 'gradering':
                /**
                 * NOTE: arkivstruktur.xsd has <gradering> both as a complexType
                 * and as a simpleType. This makes handling the element a little
                 * more complex in an event-based parser.
                 *
                 * There are potential two ways of dealing with this. First, check
                 * to see if $this->currentCdata is empty or not. If it is, then
                 * this is most likely the complexType. However, I think
                 * <gradering></gradering> would be misinterpreted in this
                 * situation. So I have decided to set a boolean flag when we detect
                 * <gradering> as a simpleType, then we know whether this closing
                 * element is a simpleType or a complexType
                 */

                if ($this->graderingIsSimpleType === true) {
                    $this->handleClassification();
                    $this->graderingIsSimpleType = false;
                }
                else {
                    $this->checkObjectClassTypeCorrect('Classified');
                    $this->postProcessClassified();
                    $this->statistics->numberOfClassificationProcessed++;
                    array_pop($this->stack);
                }
                break;

            case 'klasse':
                $this->checkObjectClassTypeCorrect('Klass');
                $this->postProcessClass();
                $this->statistics->numberOfClassProcessed++;
                array_pop($this->stack);
                break;
            case 'klassifikasjonssystem':
                $this->checkObjectClassTypeCorrect('ClassificationSystem');
                $this->postProcessClassificationSystem();
                $this->statistics->numberOfClassificationSystemProcessed++;
                array_pop($this->stack);
                break;
            case 'kryssreferanse':
                $this->checkObjectClassTypeCorrect('CrossReference');
                $this->postProcessCrossReference();
                $this->statistics->numberOfCrossReferenceProcessed++;
                array_pop($this->stack);
                break;
            case 'sletting':
                $this->checkObjectClassTypeCorrect('Deletion');
                $this->postProcessDeletion();
                $this->statistics->numberOfDeletionProcessed++;
                array_pop($this->stack);
                break;
            case 'kassasjon':
                $this->checkObjectClassTypeCorrect('Disposal');
                $this->postProcessDisposal();
                $this->statistics->numberOfDisposalProcessed++;
                array_pop($this->stack);
                break;
            case 'utfoertKassasjon':
                $this->checkObjectClassTypeCorrect('DisposalUndertaken');
                $this->postProcessDisposal();
                $this->statistics->numberOfDisposalUndertakenProcessed++;
                array_pop($this->stack);
                break;
            case 'sakspart':
                $this->checkObjectClassTypeCorrect('CaseParty');
                $this->postProcessDisposal();
                $this->statistics->numberOfCasePartyProcessed++;
                array_pop($this->stack);
                break;
            case 'elektroniskSignatur':
                $this->checkObjectClassTypeCorrect('ElectronicSignature');
                $this->postProcessDisposal();
                $this->statistics->numberOfElectronicSignatureProcessed++;
                array_pop($this->stack);
                break;
            case 'skjerming':
                $this->checkObjectClassTypeCorrect('Screening');
                $this->postProcessScreening();
                $this->statistics->numberOfScreeningProcessed++;
                array_pop($this->stack);
                break;
            case 'merknad':
                $this->checkObjectClassTypeCorrect('Comment');
                $this->postProcessComment();
                $this->statistics->numberOfCommentProcessed++;
                array_pop($this->stack);
                break;
           case 'konvertering':
                $this->checkObjectClassTypeCorrect('Conversion');
                $this->postProcessConversion();
                $this->statistics->numberOfConversionProcessed++;
                array_pop($this->stack);
                break;
            case 'dokumentflyt':
                $this->checkObjectClassTypeCorrect('Workflow');
                $this->postProcessWorkflow();
                $this->statistics->numberOfWorkflowProcessed++;
                array_pop($this->stack);
                break;
            case 'moetedeltaker':
                $this->checkObjectClassTypeCorrect('MeetingParticipant');
                $this->postProcessMeetingParticipant();
                $this->statistics->numberOfMeetingParticipantProcessed++;
                array_pop($this->stack);
                break;

            // The rest of the elements are elements that simpleTypes and
            // within one of the complexTypes above
            case 'administrativEnhet':
                $this->handleAdministrativeUnit();
                break;
            case 'antallVedlegg':
                $this->handleNumberOfAttachments();
                break;
            case 'arkivdelstatus':
                $this->handleSeriesStatus();
                break;
            case 'arkivskaperID':
                $this->handleFondsCreatorID();
                break;
            case 'arkivskaperNavn':
                $this->handleFondsCreatorName();
                break;
            case 'arkivstatus':
                $this->handleFondsStatus();
                break;
            case 'avskrevetAv':
                $this->handleSignOffBy();
                break;
            case 'avskrivningsdato':
                $this->handleSignOffDate();
                break;
            case 'avskrivningsmaate':
                $this->handleSignOffMethod();
                break;
            case 'avsluttetAv':
                $this->handleFinalisedBy();
                break;
            case 'avsluttetDato':
                $this->handleFinalisedDate();
                break;
            case 'arkivertAv':
                $this->handleArchivedBy();
                break;
            case 'arkivertDato':
                $this->handleArchivedDate();
                break;
            case 'arkivperiodeStartDato':
                $this->handleSeriesStartDate();
                break;
            case 'arkivperiodeSluttDato':
                $this->handleSeriesEndDate();
                break;
            case 'beskrivelse':
                $this->handleDescription();
                break;
            case 'bevaringstid':
                $this->handlePreservationTime();
                break;
            case 'dokumentetsDato':
                $this->handleDocumentDate();
                break;
            case 'dokumentmedium':
                $this->handleDocumentMedium();
                break;
            case 'dokumentnummer':
                $this->handleDocumentNumber();
                break;
            case 'dokumentstatus':
                $this->handleDocumentStatus();
                break;
            case 'dokumenttype':
                $this->handleDocumentType();
                break;
            case 'elektroniskSignaturSikkerhetsnivaa':
                $this->handleElectronicSignatureSecurityLevel();
                break;
            case 'elektroniskSignaturVerifisert':
                $this->handleElectronicSignatureVerified();
                break;
            case 'epostadresse':
                $this->handleEmailAddress();
                break;
            case 'filstoerrelse':
                $this->handleFileSize();
                break;
            case 'flytFra':
                $this->handleWorkflowFrom();
                break;
            case 'flytTil':
                $this->handleWorkflowTo();
                break;
            case 'flytMottattDato':
                $this->handleWorkflowReceivedDate();
                break;
            case 'flytSendtDato':
                $this->handleWorkflowSentDate();
                break;
            case 'flytStatus':
                $this->handleWorkflowStatus();
                break;
            case 'flytMerknad':
                $this->handleWorkflowComment();
                break;
            case 'forfallsdato':
                $this->handleDueDate();
                break;
            case 'forfatter':
                $this->handleAuthor();
                break;
            case 'format':
                $this->handleFormat();
                break;
            case 'formatDetaljer':
                $this->handleFormatDetails();
                break;
            case 'gradertAv':
                $this->handleClassificationBy();
                break;
            case 'graderingsdato':
                $this->handleClassificationDate();
                break;
            case 'journalaar':
                $this->handleRecordYear();
                break;
            case 'journaldato':
                $this->handleRecordDate();
                break;
            case 'journalenhet':
                $this->handleRecordsManagementUnit();
                break;
            case 'journalpostnummer':
                $this->handleRecordNumber();
                break;
            case 'journalposttype':
                $this->handleRecordType();
                break;
            case 'journalsekvensnummer':
                $this->handleRecordSequenceNumber();
                break;
            case 'journalstatus':
                $this->handleRecordStatus();
                break;
            case 'journalStartDato':
                $this->handleRecordStartDate();
                break;
            case 'kassasjonsdato':
                $this->handleDisposalDate();
                break;
            case 'kassasjonshjemmel':
                $this->handleDisposalAuthority();
                break;
            case 'kassasjonsvedtak':
                $this->handleDisposalDecision();
                break;
            case 'kassertAv':
                $this->handleDisposalUndertakenBy();
                break;
            case 'kassertDato':
                $this->handleDisposalUndertakenDate();
                break;
            case 'klasseID':
                $this->handleClassId();
                break;
            case 'klassifikasjonstype':
                $this->handleClassificationType();
                break;
            case 'kontaktperson':
                $this->handleContactPerson();
                break;
            case 'konverteringsverktoey':
                $this->handleConversionTool();
                break;
            case 'konverteringskommentar':
                $this->handleConversionComment();
                break;
            case 'konvertertAv':
                $this->handleConvertedBy();
                break;
            case 'konvertertDato':
                $this->handleConvertedDate();
                break;
            case 'konvertertFraFormat':
                $this->handleConvertedFromFormat();
                break;
            case 'konvertertTilFormat':
                $this->handleConvertedToFormat();
                break;
            case 'korrespondansepartNavn':
                $this->handleCorrespondencePartName();
                break;
            case 'korrespondanseparttype':
                $this->handleCorrespondencePartType();
                break;
            case 'land':
                $this->handleCountry();
                break;
            case 'mappeID':
                $this->handleFileId();
                break;
            case 'merknadRegistrertAv':
                $this->handleCommentRegisteredBy();
                break;
            case 'merknadsdato':
                $this->handleCommentDate();
                break;
            case 'merknadstype':
                $this->handleCommentType();
                break;
            case 'merknadstekst':
                $this->handleCommentText();
                break;
            case 'moetedato':
                $this->handleMeetingDate();
                break;
            case 'moetedeltakerFunksjon':
                $this->handleMeetingParticipantFunction();
                break;
            case 'moetedeltakerNavn':
                $this->handleMeetingParticipantName();
                break;
            case 'moetenummer':
                $this->handleMeetingNumber();
                break;
            case 'moeteregistreringsstatus':
                $this->handleMeetingRecordStatus();
                break;
            case 'moeteregistreringstype':
                $this->handleMeetingRecordType();
                break;
            case 'moetesakstype':
                $this->handleMeetingCaseType();
                break;
            case 'moetested':
                $this->handleMeetingPlace();
                break;
            case 'mottattDato':
                $this->handleReceivedDate();
                break;
            case 'nedgradertAv':
                $this->handleClassificationDowngradedBy();
                break;
            case 'nedgraderingsdato':
                $this->handleClassificationDowngradedDate();
                break;
            case 'noekkelord':
                $this->handleKeyword();
                break;
            case 'offentlighetsvurdertDato':
                $this->handleReviewFOIDate();
                break;
            case 'offentligTittel':
                $this->handleOfficialTitle();
                break;
            case 'oppbevaringssted':
                $this->handleStorageLocation();
                break;
            case 'opprettetAv':
                $this->handleCreatedBy();
                break;
            case 'opprettetDato':
                $this->handleCreatedDate();
                break;
            case 'postadresse':
                $this->handlePostalAddress();
                break;
            case 'postnummer':
                $this->handlePostalNumber();
                break;
            case 'poststed':
                $this->handlePostalTown();
                break;
            case 'presedensDato':
                $this->handlePrecedenceDate();
                break;
            case 'presedensStatus':
                $this->handlePrecedenceStatus();
                break;
            case 'presedensHjemmel':
                $this->handlePrecedenceAuthority();
                break;
            case 'presedensGodkjentDato':
                $this->handlePrecedenceApprovedDate();
                break;
            case 'presedensGodkjentAv':
                $this->handlePrecedenceApprovedBy();
                break;
            case 'referanseArkivdel':
                $this->handleReferenceSeries();
                break;
            case 'referanseArvtaker':
                $this->handleReferenceSuccessor();
                break;
            case 'referanseAvskrivesAvJournalpost':
                $this->handleReferenceSignedOffByRegistryEntry();
                break;
            case 'referanseDokumentfil':
                $this->handleReferenceDocumentFile();
                break;
            case 'referanseFraMoeteregistrering':
                $this->handleReferenceFromMeetingRecord();
                break;
            case 'referanseForloeper':
                $this->handleReferencePrecursor();
                break;
            case 'referanseForrigeMoete':
                $this->handleReferencePreviousMeeting();
                break;
            case 'referanseNesteMoete':
                $this->handleReferenceNextMeeting();
                break;
            case 'referanseSekundaerKlassifikasjon':
                $this->handleSecondaryClassification();
                break;
            case 'referanseTilMoeteregistrering':
                $this->handleReferenceToMeetingRecord();
                break;
            case 'referanseTilKlasse':
                $this->handleReferenceToClass();
                break;
            case 'referanseTilMappe':
                $this->handleReferenceToFile();
                break;
            case 'referanseTilRegistrering':
                $this->handleReferenceToRecord();
                break;
            case 'registreringsID':
                $this->handleRecordId();
                break;
            case 'rettskildefaktor':
                $this->handleSourceOfLaw();
                break;
            case 'saksaar':
                $this->handleCaseYear();
                break;
            case 'saksansvarlig':
                $this->handleCaseResponsible();
                break;
            case 'saksbehandler':
                $this->handleCaseHandler();
                break;
            case 'saksdato':
                $this->handleCaseDate();
                break;
            case 'sakspartID':
                $this->handleCasePartyId();
                break;
            case 'sakspartNavn':
                $this->handleCasePartyName();
                break;
            case 'sakspartRolle':
                $this->handleCasePartyRole();
                break;
            case 'sakssekvensnummer':
                $this->handleCaseSequenceNumber();
                break;
            case 'saksstatus':
                $this->handleCaseStatus();
                break;
            case 'sendtDato':
                $this->handleSentDate();
                break;
            case 'sjekksum':
                $this->handleChecksum();
                break;
            case 'sjekksumAlgoritme':
                $this->handleChecksumAlgorithm();
                break;
            case 'skjermingshjemmel':
                $this->handleScreeningAuthority();
                break;
            case 'skjermingDokument':
                $this->handleScreeningDocument();
                break;
            case 'skjermingMetadata':
                $this->handleScreeningMetadata();
                break;
            case 'skjermingOpphoererDato':
                $this->handleScreeningExpiresDate();
                break;
            case 'skjermingsvarighet':
                $this->handleScreeningDuration();
                break;
            case 'slettetAv':
                $this->handleDeletionBy();
                break;
            case 'slettetDato':
                $this->handleDeletionDate();
                break;
            case 'slettingstype':
                $this->handleDeletionType();
                break;
            case 'systemID':
                $this->handleSystemId();
                break;
            case 'telefonnummer':
                $this->handleTelephoneNumber();
                break;
            case 'tilgangsrestriksjon':
                $this->handleAccessRestriction();
                break;
            case 'tilknyttetAv':
                $this->handleAssociatedBy();
                break;
            case 'tilknyttetDato':
                $this->handleAssociationDate();
                break;
            case 'tilknyttetRegistreringSom':
                $this->handleAssociatedWithRecordAs();
                break;
            case 'tittel':
                $this->handleTitle();
                break;
            case 'utlaantDato':
                $this->handleLoanedDate();
                break;
            case 'utlaantTil':
                $this->handleLoanedTo();
                break;
            case 'utvalg':
                $this->handleCommittee();
                break;
            case 'variantformat':
                $this->handleVariantFormat();
                break;
            case 'versjonsnummer':
                $this->handleVersionNumber();
                break;
            case 'verifisertAv':
                $this->handleVerifiedBy();
                break;
            case 'verifisertDato':
                $this->handleVerifiedDate();
                break;
            default:
                $this->logger->fatal('Unknown Noark 5 tag ' . $tag . '. This has not been handled. This is a serious error');
        }

        $this->currentCdata = "";
    }

    public function cdata($parser, $cdata)
    {
        // If cdata is only whitespace just return
        if (! trim($cdata))
            return;
        $this->currentCdata .= $cdata;
    }

    /**
     * checkObjectClassTypeCorrect checks that the object at the head of the stack is an instance
     * of the correct type (class). It's s quick and dirty way to ensure that the xml file / stack
     * are being processed properly. It is possible that a subclass of this class repositions the
     * stack incorrectly. An exception is thrown if the stack isn't in sync as the code becomes
     * unpredicatable if this occurs.
     *
     * @param Noark5object $className
     * @return true if the object at the head of the stack is an instance of the class specified in $className
     */
    protected function checkObjectClassTypeCorrect($className)
    {
        if (strcmp($className, get_class(end($this->stack))) != 0) {
            $this->logger->fatal('Error processing arkivstruktur.xml. Unsafe to continue Expected (' . $className . ') found (' . get_class(end($this->stack)) . '). Unsafe processing.');
            throw new Exception('Error processing arkivstruktur.xml. Unsafe to continue Expected (' . $className . ') found (' . get_class(end($this->stack)) . '). Unsafe processing.');
        }
        return true;
    }

    /**
     * function handleAccessRestriction()
     * Can be used by: skjerming, gradering
     * n5mdk: M500 tilgangsrestriksjon
     */
    protected function handleAccessRestriction()
    {
        $object = end($this->stack);
        $object->setAccessRestriction($this->currentCdata);
    }

    /**
     * function handleAdministrativeUnit()
     * Can be used by: saksmappe, journalpost, moeteregistrering
     * n5mdk: M305 administrativEnhet
     */
    protected function handleAdministrativeUnit()
    {
        $object = end($this->stack);
        $object->setAdministrativeUnit($this->currentCdata);
    }

    /**
     * function handleArchivedBy()
     * Can be used by: Record
     * n5mdk: M605 arkivertAv
     */
    protected function handleArchivedBy()
    {
        $object = end($this->stack);
        $object->setArchivedBy($this->currentCdata);
    }

    /**
     * function handleArchivedDate()
     * Can be used by: registrering
     * n5mdk: M604 arkivertDato
     */
    protected function handleArchivedDate()
    {
        $object = end($this->stack);
        $object->setArchivedDate($this->currentCdata);
    }

    /**
     * function handleAssociatedBy()
     * Can be used by: dokumentbeskrivelse
     * n5mdk: M621 tilknyttetAv
     */
    protected function handleAssociatedBy()
    {
        $object = end($this->stack);
        $object->setAssociatedBy($this->currentCdata);
    }

    /**
     * function handleAssociatedWithRecordAs()
     * Can be used by: dokumentbeskrivelse
     * n5mdk: M217 tilknyttetRegistreringSom
     */
    protected function handleAssociatedWithRecordAs()
    {
        $object = end($this->stack);
        $object->setAssociatedWithRecordAs($this->currentCdata);
    }

    /**
     * function handleAssociationDate()
     * Can be used by: dokumentbeskrivelse
     * n5mdk: M620 tilknyttetDato
     */
    protected function handleAssociationDate()
    {
        $object = end($this->stack);
        $object->setAssociationDate($this->currentCdata);
    }

    /**
     * function handleAuthor()
     * Can be used by: dokumentbeskrivelse, basisregistrering
     * n5mdk: M024 forfatter
     */
    protected function handleAuthor()
    {
        $object = end($this->stack);
        $object->addAuthor($this->currentCdata);
    }

    /**
     * function handleCaseDate()
     * Can be used by: saksmappe
     * n5mdk: M100 saksdato
     */
    protected function handleCaseDate()
    {
        $object = end($this->stack);
        $object->setCaseDate($this->currentCdata);
    }

    /**
     * function handleCaseHandler()
     * Can be used by: journalpost, moeteregistrering
     * n5mdk: M307 saksbehandler
     */
    protected function handleCaseHandler()
    {
        $object = end($this->stack);
        $object->setCaseHandler($this->currentCdata);
    }

    /**
     * function handleCasePartyId()
     * Can be used by: sakspart
     * n5mdk: M010 sakspartID
     */
    protected function handleCasePartyId()
    {
        $object = end($this->stack);
        $object->setCasePartyId($this->currentCdata);
    }

    /**
     * function handleCasePartyName()
     * Can be used by: sakspart
     * n5mdk: M302 sakspartNavn
     */
    protected function handleCasePartyName()
    {
        $object = end($this->stack);
        $object->setCasePartyName($this->currentCdata);
    }

    /**
     * function handleCasePartyRole()
     * Can be used by: sakspart
     * n5mdk: M303 sakspartRolle
     */
    protected function handleCasePartyRole()
    {
        $object = end($this->stack);
        $object->setCasePartyRole($this->currentCdata);
    }

    /**
     * function handleCaseResponsible()
     * Can be used by: saksmappe
     * n5mdk: M306 saksansvarlig
     */
    protected function handleCaseResponsible()
    {
        $object = end($this->stack);
        $object->setCaseResponsible($this->currentCdata);
    }

    /**
     * function handleCaseSequenceNumber()
     * Can be used by: saksmappe
     * n5mdk: M012 sakssekvensnummer
     */
    protected function handleCaseSequenceNumber()
    {
        $object = end($this->stack);
        $object->setCaseSequenceNumber($this->currentCdata);
    }

    /**
     * function handleCaseStatus()
     * Can be used by: saksmappe
     * n5mdk: M052 saksstatus
     */
    protected function handleCaseStatus()
    {
        $object = end($this->stack);
        $object->setCaseStatus($this->currentCdata);
    }

    /**
     * function handleCaseYear()
     * Can be used by: saksmappe
     * n5mdk: M011 saksaar
     */
    protected function handleCaseYear()
    {
        $object = end($this->stack);
        $object->setCaseYear($this->currentCdata);
    }

    /**
     * function handleChecksum()
     * Can be used by: dokumentobjekt
     * n5mdk: M705 sjekksum
     */
    protected function handleChecksum()
    {
        $object = end($this->stack);
        $object->setChecksum($this->currentCdata);
    }

    /**
     * function handleChecksumAlgorithm()
     * Can be used by: dokumentobjekt
     *  n5mdk: M706 sjekksumAlgoritme
     */
    protected function handleChecksumAlgorithm()
    {
        $object = end($this->stack);
        $object->setChecksumAlgorithm($this->currentCdata);
    }

    /**
     * function handleClassId()
     * Can be used by: klasse
     * n5mdk: M002 klasseID
     */
    protected function handleClassId()
    {
        $object = end($this->stack);
        $object->setClassId($this->currentCdata);
    }

    /**
     * function handleClassification()
     * Can be used by: gradering
     * n5mdk: M506 gradering
     */
    protected function handleClassification()
    {
        $object = end($this->stack);
        $object->setClassification($this->currentCdata);
    }

    /**
     * function handleClassificationBy()
     * Can be used by: gradering
     * n5mdk: M625 gradertAv
     */
    protected function handleClassificationBy()
    {
        $object = end($this->stack);
        $object->setClassificationBy($this->currentCdata);
    }

    /**
     * function handleClassificationDate()
     * Can be used by: gradering
     * n5mdk: M624 graderingsdato
     */
    protected function handleClassificationDate()
    {
        $object = end($this->stack);
        $object->setClassificationDate($this->currentCdata);
    }

    /**
     * function handleClassificationDowngradedDate()
     * Can be used by: gradering
     * n5mdk: M626 nedgraderingsdato
     */
    protected function handleClassificationDowngradedDate()
    {
        $object = end($this->stack);
        $object->setClassificationDowngradedDate($this->currentCdata);
    }

    /**
     * function handleClassificationDowngradedBy()
     * Can be used by: gradering
     * n5mdk: M627 nedgradertAv
     */
    protected function handleClassificationDowngradedBy()
    {
        $object = end($this->stack);
        $object->setClassificationDowngradedBy($this->currentCdata);
    }

    /**
     * function handleClassificationType()
     * Can be used by: klassifikasjonssystem
     * n5mdk: M086 klassifikasjonstype
     */
    protected function handleClassificationType()
    {
        $object = end($this->stack);
        $object->setClassificationType($this->currentCdata);
    }

    /**
     * function handleCommentDate()
     * Can be used by: merknad
     * n5mdk: M611 merknadsdato
     */
    protected function handleCommentDate()
    {
        $object = end($this->stack);
        $object->setCommentDate($this->currentCdata);
    }

    /**
     * function handleCommentRegisteredBy()
     * Can be used by: merknad
     * n5mdk: M612 merknadRegistrertAv
     */

    protected function handleCommentRegisteredBy()
    {
        $object = end($this->stack);
        $object->setCommentRegisteredBy($this->currentCdata);
    }

    /**
     * function handleCommentText()
     * Can be used by: merknad
     * n5mdk: M310 merknadstekst
     */
    protected function handleCommentText()
    {
        $object = end($this->stack);
        $object->setCommentText($this->currentCdata);
    }

    /**
     * function handleCommentType()
     * Can be used by: merknad
     * n5mdk: M084 merknadstype
     */
    protected function handleCommentType()
    {
        $object = end($this->stack);
        $object->setCommentType($this->currentCdata);
    }

    /**
     * function handleCommittee()
     * Can be used by: moetemappe
     * n5mdk: M370 utvalg
     */
    protected function handleCommittee() {
        $object = end($this->stack);
        $object->setCommittee($this->currentCdata);
    }

    /**
     * function handleContactPerson()
     * Can be used by: journalpost, sakspart
     * n5mdk: M412 kontaktperson
     */
    protected function handleContactPerson()
    {
        $object = end($this->stack);
        $object->setContactPerson($this->currentCdata);
    }

    /**
     * function handleConvertedBy()
     * Can be used by: konvertering
     * n5mdk: M616 konvertertAv
     */
    protected function handleConvertedBy()
    {
        $object = end($this->stack);
        $object->setConvertedBy($this->currentCdata);
    }

    /**
     * function handleConversionComment()
     * Can be used by: konvertering
     * n5mdk: M715 konverteringskommentar
     */
    protected function handleConversionComment()
    {
        $object = end($this->stack);
        $object->setConversionComment($this->currentCdata);
    }

    /**
     * function handleConvertedDate()
     * Can be used by: konvertering
     * n5mdk: M615 konvertertDato
     */
    protected function handleConvertedDate()
    {
        $object = end($this->stack);
        $object->setConvertedDate($this->currentCdata);
    }


    /**
     * function handleConvertedFromFormat()
     * Can be used by: konvertering
     * n5mdk: M712 konvertertFraFormat
     */
    protected function handleConvertedFromFormat()
    {
        $object = end($this->stack);
        $object->setConvertedFromFormat($this->currentCdata);
    }

    /**
     * function handleConvertedToFormat()
     * Can be used by: konvertering
     * n5mdk: M713 konvertertTilFormat
     */
    protected function handleConvertedToFormat()
    {
        $object = end($this->stack);
        $object->setConvertedToFormat($this->currentCdata);
    }

    /**
     * function handleConversionTool()
     * Can be used by: konvertering
     * n5mdk: M714 konverteringsverktoey
     */
    protected function handleConversionTool()
    {
        $object = end($this->stack);
        $object->setConversionTool($this->currentCdata);
    }

    /**
     * function handleCountry()
     * Can be used by: journalpost, sakspart
     * n5mdk: M409 land
     */
    protected function handleCountry()
    {
        $object = end($this->stack);
        $object->setCountry($this->currentCdata);
    }

    /**
     * function handleCorrespondencePartType()
     * Can be used by: korrespondansepart
     * n5mdk: M087 korrespondanseparttype
     */
    protected function handleCorrespondencePartType()
    {
        $object = end($this->stack);
        $object->setCorrespondancePartType($this->currentCdata);
    }

    /**
     * function handleCorrespondencePartName()
     * Can be used by: journalpost
     * n5mdk: M400 korrespondansepartNavn
     */
    protected function handleCorrespondencePartName()
    {
        $object = end($this->stack);
        $object->setCorrespondancePartName($this->currentCdata);
    }

    /**
     * function handleCreatedBy()
     * Can be used by: arkiv, arkivdel, klassifikasjonssystem, klasse, mappe, registrering,
     *                  dokumentbeskrivelse, dokumentobjekt, presedens
     * n5mdk: M600 opprettetAv
     */
    protected function handleCreatedBy()
    {
        $object = end($this->stack);
        $object->setCreatedBy($this->currentCdata);
    }

    /**
     * function handleCreatedDate()
     * Can be used by: arkiv, arkivdel, klassifikasjonssystem, klasse, mappe, registrering,
     *                  dokumentbeskrivelse, dokumentobjekt, presedens
     * n5mdk: M600 opprettetDato
     */
    protected function handleCreatedDate()
    {
        $object = end($this->stack);
        $object->setCreatedDate($this->currentCdata);
    }

    /**
     * function handleDeletionBy()
     * Can be used by: sletting
     * n5mdk: M614 slettetAv
     */
    protected function handleDeletionBy()
    {
        $object = end($this->stack);
        $object->setDeletionBy($this->currentCdata);
    }

     /**
     * function handleDeletionType()
     * Can be used by: sletting
     * n5mdk: M613 slettetDato
     */
    protected function handleDeletionDate()
    {
        $object = end($this->stack);
        $object->setDeletionDate($this->currentCdata);
    }

    /**
     * function handleDeletionType()
     * Can be used by: sletting
     * n5mdk: M089 slettingstype
     */
    protected function handleDeletionType()
    {
        $object = end($this->stack);
        $object->setDeletionType($this->currentCdata);
    }

    /**
     * function handleDisposalAuthority()
     * Can be used by: kassasjon
     * n5mdk: M453 kassasjonshjemmel
     */
    protected function handleDisposalAuthority()
    {
        $object = end($this->stack);
        $object->setDisposalAuthority($this->currentCdata);
    }

    /**
     * function handleDisposalDate()
     * Can be used by: kassasjon
     * n5mdk: M452 kassasjonsdato
     */
    protected function handleDisposalDate()
    {
        $object = end($this->stack);
        $object->setDisposalDecision($this->currentCdata);
    }

    /**
     * function handleDisposalDecision()
     * Can be used by: kassasjon
     * n5mdk: M450 kassasjonsvedtak
     */
    protected function handleDisposalDecision()
    {
        $object = end($this->stack);
        $object->setDisposalDecision($this->currentCdata);
    }

    /**
     * function handleDisposalUndertakenBy()
     * Can be used by: utfoertkassasjon
     * n5mdk: M631 kassertAv
     */
    protected function handleDisposalUndertakenBy()
    {
        $object = end($this->stack);
        $object->setDisposalBy($this->currentCdata);
    }

    /**
     * function handleDisposalUndertakenDate()
     * Can be used by: utfoertkassasjon
     * n5mdk: M630 kassertDato
     */
    protected function handleDisposalUndertakenDate()
    {
        $object = end($this->stack);
        $object->setDisposalDate($this->currentCdata);
    }

    /**
     * function handleDocumentDate()
     * Can be used by: journalpost
     * n5mdk: M103 dokumentetsDato
     */
    protected function handleDocumentDate()
    {
        $object = end($this->stack);
        $object->setDocumentDate($this->currentCdata);
    }

    /**
     * function handleDocumentNumber()
     * Can be used by: dokumentbeskrivelse
     * n5mdk: M007 dokumentnummer
     */
    protected function handleDocumentNumber()
    {
        $object = end($this->stack);
        $object->setDocumentNumber($this->currentCdata);
    }

    /**
     * function handleDocumentStatus()
     * Can be used by: dokumentbeskrivelse
     * n5mdk: M054 dokumentstatus
     */
    protected function handleDocumentStatus()
    {
        $object = end($this->stack);
        $object->setDocumentStatus($this->currentCdata);
    }

    /**
     * function handleDocumentType()
     * Can be used by: dokumentbeskrivelse
     * n5mdk: M083 dokumenttype
     */
    protected function handleDocumentType()
    {
        $object = end($this->stack);
        $object->setDocumentType($this->currentCdata);
    }

    /**
     * function handleDueDate()
     * Can be used by: journalpost
     * n5mdk: M109 forfallsdato
     */
    protected function handleDueDate()
    {
        $object = end($this->stack);
        $object->setDueDate($this->currentCdata);
    }

    /**
     * function handleEmailAddress()
     * Can be used by: journalpost, sakspart
     * n5mdk: M410 epostadresse
     */
    protected function handleEmailAddress()
    {
        $object = end($this->stack);
        $object->setEmailAddress($this->currentCdata);
    }

    /**
     * function: handleDescription()
     * Can be used by: arkiv, arkivdel, klassifikasjonssystem, klasse, mappe, basisregistrering,
     * dokumentbeskrivelse, arkivskaper, presedens
     * n5mdk: M021 beskrivelse
     */
    protected function handleDescription()
    {
        $object = end($this->stack);
        $object->setDescription($this->currentCdata);
    }

    /**
     * function handleDocumentMedium()
     * Can be used by: arkiv, arkivdel, mappe, basisregistrering, dokumentbeskrivelse
     * n5mdk: M300 dokumentmedium
     */
    protected function handleDocumentMedium()
    {
        $object = end($this->stack);
        $object->setDocumentMedium($this->currentCdata);
    }

    /**
     * function handleElectronicSignatureSecurityLevel()
     * Can be used by: elektronisksignatur
     * n5mdk:  M507 elektroniskSignaturSikkerhetsnivaa
     */
    protected function handleElectronicSignatureSecurityLevel()
    {
        $object = end($this->stack);
        $object->setElectronicSignatureSecurityLevel($this->currentCdata);
    }
    /**
     * function handleElectronicSignatureVerified()
     * Can be used by: elektronisksignatur
     * n5mdk: M508 - elektroniskSignaturVerifisert
     */
    protected function handleElectronicSignatureVerified()
    {
        $object = end($this->stack);
        $object->setElectronicSignatureVerified($this->currentCdata);
    }

    /**
     * function handleFileId()
     * Can be used by: File
     * n5mdk: M003 mappeID
     */
    protected function handleFileId()
    {
        $object = end($this->stack);
        $object->setFileId($this->currentCdata);
    }

    /**
     * function handleFileSize()
     * Can be used by: dokumentobjekt
     * n5mdk: M707 filstoerrelse
     */
    protected function handleFileSize()
    {
        $object = end($this->stack);
        $object->setFileSize($this->currentCdata);
    }

    /**
     * function handleFinalisedDate()
     * Can be used by: arkiv, arkivdel, klassifikasjonssystem, klasse og mappe
     * n5mdk: M602 avsluttetDato
     */
    protected function handleFinalisedDate()
    {
        $object = end($this->stack);
        $object->setFinalisedDate($this->currentCdata);
    }

    /**
     * function handleFinalisedBy()
     * Can be used by: arkiv, arkivdel, klassifikasjonssystem, klasse og mappe
     * n5mdk: M603 avsluttetAv
     */
    protected function handleFinalisedBy()
    {
        $object = end($this->stack);
        $object->setFinalisedBy($this->currentCdata);
    }

    /**
     * function handleFondsCreatorID()
     * Can be used by: FondsCreator
     * n5mdk: M006 arkivskaperID
     */
    protected function handleFondsCreatorID()
    {
        $object = end($this->stack);
        $object->setFondsCreatorID($this->currentCdata);
    }

    /**
     * function handleFondsCreatorName()
     * Can be used by: arkivskaper
     * n5mdk: M023 arkivskaperNavn
     */
    protected function handleFondsCreatorName()
    {
        $object = end($this->stack);
        $object->setFondsCreatorName($this->currentCdata);
    }

    /**
     * function: handleFondsStatus()
     * Can be used by: arkiv
     * n5mdk: M050 arkivstatus
     */
    protected function handleFondsStatus()
    {
        $object = end($this->stack);
        $object->setFondsStatus($this->currentCdata);
    }

    /**
     * function handleFormat()
     * Can be used by: dokumentobjekt
     * n5mdk: M701 format
     */
    protected function handleFormat()
    {
        $object = end($this->stack);
        $object->setFormat($this->currentCdata);
    }

    /**
     * function: handleFormatDetails
     * Can be used by: dokumentobjekt
     * n5mdk: M702 formatDetaljer
     */
    protected function handleFormatDetails()
    {
        $object = end($this->stack);
        $object->setFormatDetails($this->currentCdata);
    }

    /**
     * function handleKeyword()
     * Can be used by: klasse, mappe, basisregistrering
     * n5mdk: M022 noekkelord
     */
    protected function handleKeyword()
    {
        $object = end($this->stack);
        $object->addKeyword($this->currentCdata);
    }

    /**
     * function handleLoanedDate()
     * Can be used by: saksmappe, journalpost
     * n5mdk: M106 utlaantDato
     */
    protected function handleLoanedDate()
    {
        $object = end($this->stack);
        $object->setLoanedDate($this->currentCdata);
    }

    /**
     * function handleLoanedTo()
     * Can be used by: saksmappe, journalpost
     * n5mdk: M309 utlaantTil
     */
    protected function handleLoanedTo()
    {
        $object = end($this->stack);
        $object->setLoanedTo($this->currentCdata);
    }

    /**
     * function handleMeetingCaseType()
     * Can be used by: moeteregistrering
     * n5mdk: M088 moetesakstype
     */
    protected function handleMeetingCaseType()
    {
        $object = end($this->stack);
        $object->setMeetingCaseType($this->currentCdata);
    }

    /**
     * function handleMeetingDate()
     * Can be used by: moetemappe
     * n5mdk: M102 moetedato
     */
    protected function handleMeetingDate()
    {
        $object = end($this->stack);
        $object->setMeetingDate($this->currentCdata);
    }

    /**
     * function handleMeetingParticipantFunction()
     * Can be used by: moetemappe
     * n5mdk: M373 moetedeltakerFunksjon
     */
    protected function handleMeetingParticipantFunction()
    {
        $object = end($this->stack);
        $object->setMeetingParticipantFunction($this->currentCdata);
    }

    /**
     * function handleMeetingParticipantName()
     * Can be used by: moetemappe
     * n5mdk: M372 moetedeltakerNavn
     */
    protected function handleMeetingParticipantName()
    {
        $object = end($this->stack);
        $object->setMeetingParticipantName($this->currentCdata);
    }

    /**
     * function handleMeetingPlace()
     * Can be used by: moetemappe
     * n5mdk: M371 moetested
     */
    protected function handleMeetingPlace()
    {
        $object = end($this->stack);
        $object->setMeetingPlace($this->currentCdata);
    }

    /**
     * function handleMeetingNumber()
     * Can be used by: moetemappe
     * n5mdk: M008 moetenummer
     */
    protected function handleMeetingNumber()
    {
        $object = end($this->stack);
        $object->setMeetingNumber($this->currentCdata);
    }

    /**
     * function handleMeetingRecordStatus()
     * Can be used by: moeteregistrering
     * n5mdk: M055 moeteregistreringsstatus
     */
    protected function handleMeetingRecordStatus()
    {
        $object = end($this->stack);
        $object->setMeetingRecordStatus($this->currentCdata);
    }

    /**
     * function handleMeetingRecordType()
     * Can be used by: moeteregistrering
     * n5mdk: M085 moeteregistreringstype
     */
    protected function handleMeetingRecordType()
    {
        $object = end($this->stack);
        $object->setMeetingRecordType($this->currentCdata);
    }

    /**
     * function handleNumberOfAttachments()
     * Can be used by: journalpost
     * n5mdk: M304 antallVedlegg
     */
    protected function handleNumberOfAttachments()
    {
        $object = end($this->stack);
        $object->setNumberOfAttachments($this->currentCdata);
    }

    /**
     * function handleOfficialTitle()
     * Can be used by: mappe, basisregistrering
     * n5mdk: M025 offentligTittel
     */
    protected function handleOfficialTitle()
    {
        $object = end($this->stack);
        $object->setOfficialTitle($this->currentCdata);
    }

    /**
     * function handlePostalAddress()
     * Can be used by: journalpost, sakspart
     * n5mdk: M406 postadresse
     */
    protected function handlePostalAddress()
    {
        $object = end($this->stack);
        $object->setPostalAddress($this->currentCdata);
    }

    /**
     * function handlePostalNumber()
     * Can be used by: journalpost, sakspart
     * n5mdk: M407 postnummer
     */
    protected function handlePostalNumber()
    {
        $object = end($this->stack);
        $object->setPostCode($this->currentCdata);
    }

    /**
     * function handlePostalTown()
     * Can be used by: journalpost, sakspart
     * n5mdk: M408 poststed
     */
    protected function handlePostalTown()
    {
        $object = end($this->stack);
        $object->setPostalTown($this->currentCdata);
    }

    /**
     * function handlePrecedenceApprovedBy()
     * Can be used by: presedens
     * n5mdk: M629 presedensGodkjentAv
     */
    protected function handlePrecedenceApprovedBy()
    {
        $object = end($this->stack);
        $object->setPrecedenceApprovedBy($this->currentCdata);
    }

    /**
     * function handlePrecedenceApprovedDate()
     * Can be used by: presedens
     * n5mdk: M628 presedensGodkjentDato
     */
    protected function handlePrecedenceApprovedDate()
    {
        $object = end($this->stack);
        $object->setPrecedenceApprovedDate($this->currentCdata);
    }

    /**
     * function handlePrecedenceAuthority()
     * Can be used by: presedens
     * n5mdk: M311 presedensHjemmel
     */
    protected function handlePrecedenceAuthority()
    {
        $object = end($this->stack);
        $object->setPrecedenceAuthority($this->currentCdata);
    }

    /**
     * function handlePrecedenceDate()
     * Can be used by: presedens
     * n5mdk: M111 presedensdato
     */
    protected function handlePrecedenceDate()
    {
        $object = end($this->stack);
        $object->setPrecedenceDate($this->currentCdata);
    }

    /**
     * function handlePrecedenceStatus()
     * Can be used by: presedens
     * n5mdk: M056 presedensstatus
     */
    protected function handlePrecedenceStatus()
    {
        $object = end($this->stack);
        $object->setPrecedenceStatus($this->currentCdata);
    }

    /**
     * function handlePreservationTime()
     * Can be used by: kassasjon
     * n5mdk: M451 bevaringstid
     */
    protected function handlePreservationTime()
    {
        $object = end($this->stack);
        $object->setPreservationTime($this->currentCdata);
    }

    /**
     * function handleReceivedDate()
     * Can be used by: journalpost
     * n5mdk: M104 mottattDato
     */
    protected function handleReceivedDate()
    {
        $object = end($this->stack);
        $object->setReceivedDate($this->currentCdata);
    }

    /**
     * function handleRecordEndDate()
     * Can be used by: journalpost
     * n5mdk: M113 journalSluttDato
     */
    protected function handleRecordEndDate()
    {
        $object = end($this->stack);
        $object->setRecordEndDate($this->currentCdata);
    }

    /**
     * function handleRecordDate()
     * Can be used by: journalpost
     * n5mdk: M101 journaldato
     */
    protected function handleRecordDate()
    {
        $object = end($this->stack);
        $object->setRecordDate($this->currentCdata);
    }

    /**
     * function handleRecordId()
     * Can be used by: BasicRecord
     * n5mdk: M004 registreringsID
     */
    protected function handleRecordId()
    {
        $object = end($this->stack);
        $object->setRecordId($this->currentCdata);
    }

    /**
     * function handleRecordNumber()
     * Can be used by: journalpost
     * n5mdk: M015 journalpostnummer
     */
    protected function handleRecordNumber()
    {
        $object = end($this->stack);
        $object->setRegistryEntryNumber($this->currentCdata);
    }

    /**
     * function handleRecordSequenceNumber()
     * Can be used by: journalpost
     * n5mdk: M014 journalsekvensnummer
     */
    protected function handleRecordSequenceNumber()
    {
        $object = end($this->stack);
        $object->setRecordSequenceNumber($this->currentCdata);
    }

    /**
     * function handleRecordsManagementUnit()
     * Can be used by: saksmappe, journalpost
     * n5mdk: M308 journalenhet
     */
    protected function handleRecordsManagementUnit()
    {
        $object = end($this->stack);
        $object->setRecordsManagementUnit($this->currentCdata);
    }

    /**
     * function handleRecordStatus()
     * Can be used by: journalpost
     * n5mdk: M053 journalstatus
     */
    protected function handleRecordStatus()
    {
        $object = end($this->stack);
        $object->setRecordStatus($this->currentCdata);
    }

    /**
     * function handleRecordStartDate()
     * Can be used by: journalpost
     * n5mdk: M112 journalStartDato
     */
    protected function handleRecordStartDate()
    {
        $object = end($this->stack);
        $object->setRecordStartDate($this->currentCdata);
    }

    /**
     * function handleRecordType()
     * Can be used by: journalpost
     * n5mdk: M082 journalposttype
     */
    protected function handleRecordType()
    {
        $object = end($this->stack);
        $object->setRegistryEntryType($this->currentCdata);
    }

    /**
     * function handleRecordYear()
     * Can be used by: journalpost
     * n5mdk: M013 journalaar
     */
    protected function handleRecordYear()
    {
        $object = end($this->stack);
        $object->setRecordYear($this->currentCdata);
    }

    /**
     * function handleReferenceSeries()
     * Can be used by: mappe, registrering, dokumentbeskrivelse
     * n5mdk: M208 referanseArkivdel
     */
    protected function handleReferenceSeries()
    {
        $object = end($this->stack);
        $object->addReferenceSeries($this->currentCdata);
    }

    /**
     * function referanseAvskrivesAvJournalpost()
     * Can be used by: journalpost
     * n5mdk: M215 referanseAvskrivesAvJournalpost
     */
    protected function handleReferenceSignedOffByRegistryEntry()
    {
        $object = end($this->stack);
        $object->setReferenceSignedOffByRegistryEntry($this->currentCdata);
    }

    /**
     * function handleReferenceDocumentFile()
     * Can be used by: dokumentobjekt
     * n5mdk: M218 referanseDokumentfil
     */
    protected function handleReferenceDocumentFile()
    {
        $object = end($this->stack);
        $object->setReferenceDocumentFile($this->currentCdata);
    }

    /**
     * function handleReferencePrecursor()
     * Can be used by: arkivdel
     * n5mdk: M202 referanseForloeper
     */
    protected function handleReferencePrecursor()
    {
        $object = end($this->stack);
        $object->setReferencePrecursor($this->currentCdata);
    }

    /**
     * function handleReferencePreviousMeeting()
     * Can be used by: moetemappe
     * n5mdk: M221 referanseForrigeMoete
     */
    protected function handleReferencePreviousMeeting()
    {
        $object = end($this->stack);
        $object->setReferencePreviousMeeting($this->currentCdata);
    }

    /**
     * function handleReferenceNextMeeting()
     * Can be used by: moetemappe
     * n5mdk: M222 referanseNesteMoete
     */
    protected function handleReferenceNextMeeting()
    {
        $object = end($this->stack);
        $object->setReferenceNextMeeting($this->currentCdata);
    }

    /**
     * function handleReferenceSuccessor()
     * Can be used by: arkivdel
     * n5mdk: M203 referanseArvtaker
     */
    protected function handleReferenceSuccessor()
    {
        $object = end($this->stack);
        $object->setReferenceSuccessor($this->currentCdata);
    }

    /**
     * function handleReviewFOIDate()
     * Can be used by: journalpost
     * n5mdk: M110 offentlighetsvurdertDato
     */
    protected function handleReviewFOIDate()
    {
        $object = end($this->stack);
        $object->setReviewFOIDate($this->currentCdata);
    }

    /**
     * function handleReferenceToClass()
     * Can be used by: klasse
     * n5mdk: M219 referanseTilKlasse
     */
    protected function handleReferenceToClass()
    {
        $object = end($this->stack);
        $object->setReferenceToClass($this->currentCdata);
    }

    /**
     * function handleReferenceToFile()
     * Can be used by: mappe, basisregistrering
     * n5mdk: M210 referanseTilMappe
     */
    protected function handleReferenceToFile()
    {
        $object = end($this->stack);
        $object->setReferenceToFile($this->currentCdata);
    }

    /**
     * function handleReferenceFromMeetingRecord()
     * Can be used by: moeteregistrering
     * n5mdk: M224 referanseFraMoeteregistrering
     */
    protected function handleReferenceFromMeetingRecord()
    {
        $object = end($this->stack);
        $object->setReferenceFromMeetingRegistration($this->currentCdata);
    }

    /**
     * function handleReferenceToMeetingRecord()
     * Can be used by: moeteregistrering
     * n5mdk: M223 referanseTilMoeteregistrering
     */
    protected function handleReferenceToMeetingRecord()
    {
        $object = end($this->stack);
        $object->setReferenceToMeetingRegistration($this->currentCdata);
    }

    /**
     * function handleReferenceToRecord()
     * Can be used by: mappe, basisregistrering
     * n5mdk: M212 referanseTilMappe
     */
    protected function handleReferenceToRecord()
    {
        $object = end($this->stack);
        $object->setReferenceToRecord($this->currentCdata);
    }

   /**
     * function handleScreeningAuthority()
     * Can be used by: skjerming
     * n5mdk: M501 skjermingshjemmel
     */
    protected function handleScreeningAuthority()
    {
        $object = end($this->stack);
        $object->setScreeningAuthority($this->currentCdata);
    }

    /**
     * function handleScreeningExpiresDate()
     * Can be used by: skjerming
     * n5mdk: M505 skjermingOpphoererDato
     */
    protected function handleScreeningExpiresDate()
    {
        $object = end($this->stack);
        $object->setScreeningExpiresDate($this->currentCdata);
    }

    /**
     * function handleScreeningDocument()
     * Can be used by: skjerming
     * n5mdk: M503 skjermingDokument
     */
    protected function handleScreeningDocument()
    {
        $object = end($this->stack);
        $object->setScreeningDocument($this->currentCdata);
    }

    /**
     * function handleScreeningDuration()
     * Can be used by: skjerming
     * n5mdk: M504 skjermingsvarighet
     */
    protected function handleScreeningDuration()
    {
        $object = end($this->stack);
        $object->setScreeningDuration($this->currentCdata);
    }

    /**
     * function handleScreeningMetadata()
     * Can be used by: skjerming
     * n5mdk: M502 skjermingsMetadata
     */
    protected function handleScreeningMetadata() {
        $object = end($this->stack);
        $object->setScreeningMetadata($this->currentCdata);
    }

    /**
     * function handleSecondaryClassification()
     * Can be used by: arkivdel
     * n5mdk: M209 referanseSekundaerKlassifikasjon
     */
    protected function handleSecondaryClassification()
    {
        $object = end($this->stack);
        $object->setSecondaryClassification($this->currentCdata);
    }

    /**
     * function handleSentDate()
     * Can be used by: journalpost
     * n5mdk: M105 sendtDato
     */
    protected function handleSentDate()
    {
        $object = end($this->stack);
        $object->setSentDate($this->currentCdata);
    }

    /**
     * function handleSeriesEndDate()
     * Can be used by: arkivdel
     * n5mdk: M108 arkivperiodeSluttDato
     */
    protected function handleSeriesEndDate()
    {
        $object = end($this->stack);
        $object->setSeriesEndDate($this->currentCdata);
    }

    /**
     * function handleSeriesStatus()
     * Can be used by: arkivdel
     * n5mdk: M051 arkivdelstatus
     */
    protected function handleSeriesStatus()
    {
        $object = end($this->stack);
        $object->setSeriesStatus($this->currentCdata);
    }

    /**
     * function handleSeriesStartDate()
     * Can be used by: arkivdel
     * n5mdk: M107 arkivperiodeStartDato
     */
    protected function handleSeriesStartDate()
    {
        $object = end($this->stack);
        $object->setSeriesStartDate($this->currentCdata);
    }

    /**
     * function handleSignOffDate()
     * Can be used by: journalpost
     * n5mdk: M617 avskrivningsdato
     */
    protected function handleSignOffDate()
    {
        $object = end($this->stack);
        $object->setSignOffDate($this->currentCdata);
    }

    /**
     * function handleSignOffBy()
     * Can be used by: journalpost
     * n5mdk: M618 avskrevetAv
     */
    protected function handleSignOffBy()
    {
        $object = end($this->stack);
        $object->setSignOffBy($this->currentCdata);
    }

    /**
     * function handleSignOffMethod()
     * Can be used by: journalpost
     * n5mdk: M619 avskrivningsmaate
     */
    protected function handleSignOffMethod()
    {
        $object = end($this->stack);
        $object->setSignOffMethod($this->currentCdata);
    }

    /**
     * function handleStorageLocation()
     * Can be used by: arkiv, arkivdel, mappe, basisregistrering, dokumentbeskrivelse
     * n5mdk: M301 oppbevaringssted
     */
    protected function handleStorageLocation()
    {
        $object = end($this->stack);
        $object->addReferenceStorageLocation($this->currentCdata);
    }

    /**
     * function handleSourceOfLaw()
     * Can be used by: presedens
     * n5mdk: M312 rettskildefaktor
     */
    protected function handleSourceOfLaw()
    {
        $object = end($this->stack);
        $object->setSourceOfLaw($this->currentCdata);
    }
    /**
     * function handleSystemId()
     * Can be used by: Fonds, Series, ClassificationSystem, Class, File, Record, DocumentDescription, DocumentObject, Author,
     * n5mdk: M001 systemID
     */
    protected function handleSystemId()
    {
        $object = end($this->stack);
        $object->setSystemId($this->currentCdata);
    }

    /**
     * function handleTelephoneNumber()
     * Can be used by: telefonnummer
     * n5mdk: M411 telefonnummer
     */
    protected function handleTelephoneNumber()
    {
        $object = end($this->stack);
        $object->setTelephoneNumber($this->currentCdata);
    }

    /**
     * function: handleTitle()
     * Can be used by: arkiv, arkivdel, klassifikasjonssystem, klasse, mappe, basisregistrering,
     *                 dokumentbeskrivelse, presedens
     * n5mdk: M020 Tittel
     */
    protected function handleTitle()
    {
        $object = end($this->stack);
        $object->setTitle($this->currentCdata);
    }

    /**
     * function: handleVariantFormat()
     * Can be used by: dokumentobjekt
     * n5mdk: M700 variantformat
     */
    protected function handleVariantFormat()
    {
        $object = end($this->stack);
        $object->setVariantFormat($this->currentCdata);
    }

    /**
     * function handleVersionNumber()
     * Can be used by :
     * n5mdk: M005 versjonsnummer
     */
    protected function handleVersionNumber()
    {
        $object = end($this->stack);
        $object->setVersionNumber($this->currentCdata);
    }

    /**
     * function handleVerifiedBy()
     * Can be used by: elektronisksignatur
     * n5mdk: M623 verifisertAv
     */
    protected function handleVerifiedBy()
    {
        $object = end($this->stack);
        $object->setVerifiedBy($this->currentCdata);
    }

    /**
     * function handleVerifiedDate()
     * Can be used by: elektronisksignatur
     * n5mdk: M622 verifisertDato
     */
    protected function handleVerifiedDate()
    {
        $object = end($this->stack);
        $object->setVerifiedDate($this->currentCdata);
    }


    /**
     * function handleWorkflow()
     * Can be used by: dokumentflyt
     * n5mdk: M665 flytFra
     */
    protected function handleWorkflowFrom()
    {
        $object = end($this->stack);
        $object->setWorkflowFrom($this->currentCdata);
    }

    /**
     * function handleWorkflowTo()
     * Can be used by: dokumentflyt
     * n5mdk: M660 flytTil
     */
    protected function handleWorkflowTo()
    {
        $object = end($this->stack);
        $object->setWorkflowTo($this->currentCdata);
    }

    /**
     * function handleWorkflowReceivedDate()
     * Can be used by: dokumentflyt
     * n5mdk: M661 flytMottattDato
     */
    protected function handleWorkflowReceivedDate()
    {
        $object = end($this->stack);
        $object->setWorkflowReceivedDate($this->currentCdata);
    }

    /**
     * function handleWorkflowSentDate()
     * Can be used by: dokumentflyt
     * n5mdk: M662 flytSendtDato
     */
    protected function handleWorkflowSentDate()
    {
        $object = end($this->stack);
        $object->setWorkflowSentDate($this->currentCdata);
    }

    /**
     * function handleWorkflowStatus()
     * Can be used by: dokumentflyt
     * n5mdk: M663 flytStatus
     */
    protected function handleWorkflowStatus()
    {
        $object = end($this->stack);
        $object->setWorkflowStatus($this->currentCdata);
    }

    /**
     * function handleWorkflowComment()
     * Can be used by: dokumentflyt
     * n5mdk: M664 flytMerknad
     */
    protected function handleWorkflowComment()
    {
        $object = end($this->stack);
        $object->setWorkflowComment($this->currentCdata);
    }

    /**
     * The following functions are provided to subclasses so that
     * they can be overridden.
     */
    public function preProcessCaseParty() {}
    public function preProcessClass() {}
    public function preProcessClassificationSystem() {}
    public function preProcessClassified() {}
    public function preProcessComment() {}
    public function preProcessCorrespondencePart() {}
    public function preProcessConversion() {}
    public function preProcessCrossReference() {}
    public function preProcessDeletion() {}
    public function preProcessDisposal() {}
    public function preProcessDisposalUndertaken() {}
    public function preProcessDocumentDescription() {}
    public function preProcessDocumentObject() {}
    public function preProcessElectornicSignature() {}
    public function preProcessElectronicSignature() {}
    public function preProcessFile() {}
    public function preProcessFonds() {}
    public function preProcessFondsCreator() {}
    public function preProcessMeetingParticipant() {}
    public function preProcessPrecedence() {}
    public function preProcessRecord() {}
    public function preProcessScreening() {}
    public function preProcessSeries() {}
    public function preProcessSignOff() {}
    public function preProcessWorkflow() {}

    public function postProcessCaseParty() {}
    public function postProcessClass() {}
    public function postProcessClassificationSystem() {}
    public function postProcessClassified() {}
    public function postProcessComment() {}
    public function postProcessCorrespondencePart() {}
    public function postProcessConversion() {}
    public function postProcessCrossReference() {}
    public function postProcessDocumentDescription() {}
    public function postProcessDocumentObject() {}
    public function postProcessDeletion() {}
    public function postProcessDisposal() {}
    public function postProcessDisposalUndertaken() {}
    public function postProcessElectronicSignature() {}
    public function postProcessFile() {}
    public function postProcessFonds() {}
    public function postProcessFondsCreator() {}
    public function postProcessMeetingParticipant() {}
    public function postProcessPrecedence() {}
    public function postProcessRecord() {}
    public function postProcessSeries() {}
    public function postProcessScreening() {}
    public function postProcessSignOff() {}
    public function postProcessWorkflow() {}

    /**
     *
     * @return the $numberOfFondsProcessed
     */
    public function getNumberOfFondsProcessed()
    {
        return $this->numberOfFondsProcessed;
    }

    /**
     *
     * @return the $numberOfFondsCreatorProcessed
     */
    public function getNumberOfFondsCreatorProcessed()
    {
        return $this->numberOfFondsCreatorProcessed;
    }

    /**
     *
     * @return the $numberOfSeriesProcessed
     */
    public function getNumberOfSeriesProcessed()
    {
        return $this->numberOfSeriesProcessed;
    }

    /**
     *
     * @return the $numberOfClassificationSystemProcessed
     */
    public function getNumberOfClassificationSystemProcessed()
    {
        return $this->numberOfClassificationSystemProcessed;
    }

    /**
     *
     * @return the $numberOfClassProcessed
     */
    public function getNumberOfClassProcessed()
    {
        return $this->numberOfClassProcessed;
    }

    /**
     *
     * @return the $numberOfFileProcessed
     */
    public function getNumberOfFileProcessed()
    {
        return $this->numberOfFileProcessed;
    }

    /**
     *
     * @return the $numberOfCaseFileProcessed
     */
    public function getNumberOfCaseFileProcessed()
    {
        return $this->numberOfCaseFileProcessed;
    }

    /**
     *
     * @return the $numberOfRecordProcessed
     */
    public function getNumberOfRecordProcessed()
    {
        return $this->numberOfRecordProcessed;
    }

    /**
     *
     * @return the $numberOfBasicRecordProcessed
     */
    public function getNumberOfBasicRecordProcessed()
    {
        return $this->numberOfBasicRecordProcessed;
    }

    /**
     *
     * @return the $numberOfRegistryEntryProcessed
     */
    public function getNumberOfRegistryEntryProcessed()
    {
        return $this->numberOfRegistryEntryProcessed;
    }

    /**
     *
     * @return the $numberOfDocumentDescriptionProcessed
     */
    public function getNumberOfDocumentDescriptionProcessed()
    {
        return $this->numberOfDocumentDescriptionProcessed;
    }

    /**
     *
     * @return the $numberOfDocumentObjectProcessed
     */
    public function getNumberOfDocumentObjectProcessed()
    {
        return $this->numberOfDocumentObjectProcessed;
    }

    /**
     *
     * @return the $numberOfSignOffProcessed
     */
    public function getNumberOfSignOffProcessed()
    {
        return $this->numberOfSignOffProcessed;
    }

    /**
     *
     * @return the $numberOfCorrespondancePartProcessed
     */
    public function getNumberOfCorrespondancePartProcessed()
    {
        return $this->numberOfCorrespondancePartProcessed;
    }

    /**
     * @return the $stack
     */
    public function getStack()
    {
        return $this->stack;
    }

    /**
     * @return the $currentCdata
     */
    public function getCurrentCdata()
    {
        return $this->currentCdata;
    }

    /**
     * @return the $numberOfMeetingFileProcessed
     */
    public function getNumberOfMeetingFileProcessed()
    {
        return $this->numberOfMeetingFileProcessed;
    }

    /**
     * @return the $numberOfMeetingRecordProcessed
     */
    public function getNumberOfMeetingRecordProcessed()
    {
        return $this->numberOfMeetingRecordProcessed;
    }

    /**
     * @return the $numberOfClassificationProcessed
     */
    public function getNumberOfClassificationProcessed()
    {
        return $this->numberOfClassificationProcessed;
    }

    /**
     * @return the $numberOfDeletionProcessed
     */
    public function getNumberOfDeletionProcessed()
    {
        return $this->numberOfDeletionProcessed;
    }

    /**
     * @return the $numberOfDisposalProcessed
     */
    public function getNumberOfDisposalProcessed()
    {
        return $this->numberOfDisposalProcessed;
    }

    /**
     * @return the $numberOfDisposalUndertakenProcessed
     */
    public function getNumberOfDisposalUndertakenProcessed()
    {
        return $this->numberOfDisposalUndertakenProcessed;
    }

    /**
     * @return the $numberOfPrecedenceProcessed
     */
    public function getNumberOfPrecedenceProcessed()
    {
        return $this->numberOfPrecedenceProcessed;
    }

    /**
     * @return the $numberOfCrossReferenceProcessed
     */
    public function getNumberOfCrossReferenceProcessed()
    {
        return $this->numberOfCrossReferenceProcessed;
    }

    /**
     * @return the $numberOfElectronicSignatureProcessed
     */
    public function getNumberOfElectronicSignatureProcessed()
    {
        return $this->numberOfElectronicSignatureProcessed;
    }

    /**
     * @return the $numberOfScreeningProcessed
     */
    public function getNumberOfScreeningProcessed()
    {
        return $this->numberOfScreeningProcessed;
    }

    /**
     * @return the $numberOfCommentProcessed
     */
    public function getNumberOfCommentProcessed()
    {
        return $this->numberOfCommentProcessed;
    }

    /**
     * @return the $numberOfConversionProcessed
     */
    public function getNumberOfConversionProcessed()
    {
        return $this->numberOfConversionProcessed;
    }

    /**
     * @return the $numberOfCasePartyProcessed
     */
    public function getNumberOfCasePartyProcessed()
    {
        return $this->numberOfCasePartyProcessed;
    }

    /**
     * @return the $numberOfWorkflowProcessed
     */
    public function getNumberOfWorkflowProcessed()
    {
        return $this->numberOfWorkflowProcessed;
    }

    /**
     * @return the $graderingIsSimpleType
     */
    public function getGraderingIsSimpleType()
    {
        return $this->graderingIsSimpleType;
    }

    /**
     * @return the $logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    public function getStatistics()
    {
        return $this->statistics;
    }

    public function getErrorsEncountered()
    {
        return $this->errorsEncountered;
    }

    public function getNumberErrorsEncountered()
    {
        return $this->numberErrorsEncountered;
    }
}

?>

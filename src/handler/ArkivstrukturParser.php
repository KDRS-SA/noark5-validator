<?php
require_once ('models/noark5/v31/Fonds.php');
require_once ('models/noark5/v31/FondsCreator.php');
require_once ('models/noark5/v31/Series.php');
require_once ('models/noark5/v31/File.php');
require_once ('models/noark5/v31/CaseFile.php');
require_once ('models/noark5/v31/Record.php');
require_once ('models/noark5/v31/BasicRecord.php');
require_once ('models/noark5/v31/RegistryEntry.php');
require_once ('models/noark5/v31/MeetingRecord.php');
require_once ('models/noark5/v31/MeetingFile.php');
require_once ('models/noark5/v31/CorrespondencePart.php');
require_once ('models/noark5/v31/DocumentDescription.php');
require_once ('models/noark5/v31/DocumentObject.php');
require_once ('models/noark5/v31/SignOff.php');
require_once ('tests/file/ChecksumTest.php');
require_once ('vendor/apache/log4php/src/main/php/Logger.php');

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
 * Further work on this file should be to document the functions that handle the simpleTypes
 * and add the Noark 5 metadata numbers
 *
 *
 * NOTE!!! Not all noark simpleTypes are handled here. We have only implemented the ones that
 * we actually have seen in an extraction.
 */


class ArkivstrukturParser
{

    protected $stack;
    protected $directory;
    protected $currentCdata;
    protected $numberOfDocumentsProcessed = 0;
    protected $numberOfFileProcessed = 0;
    protected $numberOfRegistryEntryProcessed = 0;

    protected $documentListHandler;
    protected $testResultsHandler;
    protected $logger;
    protected $onlyParse;

    function __construct($directory, $documentListHandler, $testResultsHandler, $onlyParse)
    {
        $this->directory = $directory;
        $this->documentListHandler = $documentListHandler;
        $this->stack = array();
        $this->currentCdata = "";
        $this->testResultsHandler = $testResultsHandler;
        $this->onlyParse = $onlyParse;
        Logger::configure('../resources/logging/log4php.xml');
        $this->logger = Logger::getLogger(basename(__FILE__));
        $this->logger->warn('Testing');

    }

    function startElement($parser, $tag, $attributes)
    {
        switch ($tag) {
            case 'arkiv':
                // print_r(end($this->stack));
                $this->stack[] = new Fonds();
                $this->preProcessFonds();
                break;
            case 'arkivdel':
                // print_r(end($this->stack));
                $this->stack[] = new Series();
                $this->preProcessSeries();
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
                        } else if (strcmp($attributes['xsi:type'], 'journalpost') == 0) {
                                $this->stack[] = new RegistryEntry();
                                $classType = 'RegistryEntry';
                        } else if (strcmp($attributes['xsi:type'], 'moteregistrering') == 0) {
                                $this->stack[] = new MeetingRecord();
                                $classType = 'MeetingRecord';
                        }
                        else {
                               throw new Exception(Constants::EXCEPTION_UNKNOWN_NOARK5_OBJECT . ' Cannot handle registrering xsi:type = ' . $attributes['xsi:type']);
                        }
                    }
                } else {
                    $this->stack[] = new Record();
                }
                $this->preProcessRecord($classType);
                break;
            case 'dokumentbeskrivelse':
                // print_r(end($this->stack));
                $this->stack[] = new DocumentDescription();
                $this->preProcessDocumentDescription();
                break;
            case 'dokumentobjekt':
                // print_r(end($this->stack));
                $this->stack[] = new DocumentObject();
                $this->preProcessDocumentObject();
                break;
            case 'arkivskaper':
                //print_r(end($this->stack));
                $this->stack[] = new FondsCreator();
                $this->preProcessFondsCreator();
                break;
            case 'korrespondansepart':
                //print_r(end($this->stack));
                $this->stack[] = new CorrespondencePart();
                $this->preProcessCorrespondencePart();
                break;
            case 'klasse':
                //print_r(end($this->stack));
                $this->stack[] = new Klass();
                $this->preProcessClass();
                break;
            case 'klassifikasjonssystem':
                //print_r(end($this->stack));
                $this->stack[] = new ClassificationSystem();
                $this->preProcessClassificationSystem();
                break;
            case 'avskrivning':
                $this->stack[] = new SignOff();
                $this->preProcessSignOff();
                break;
            case 'moeteregistrering':
                $this->stack[] = new MeetingRecord();
                $this->preProcessRecord();
                break;
            case 'moetemappe';
                $this->stack[] = new MeetingFile();
                $this->preProcessFile();
                break;
        }
    }

    // invoked on each closing tag
    function endElement($parser, $tag)
    {
        switch ($tag) {
            case 'arkiv':
                // print_r(end($this->stack));
                $this->checkObjectClassTypeCorrect('Fonds');
                $this->postProcessFonds();
                array_pop($this->stack);
                break;
            case 'arkivdel':
                // print_r(end($this->stack));
                $this->checkObjectClassTypeCorrect('Series');
                $this->postProcessSeries();
                array_pop($this->stack);
                break;
            case 'mappe':
                // print_r(end($this->stack));
                $classType = get_class(end($this->stack));

                if (strcasecmp($classType, 'CaseFile') == 0) {
                    $this->checkObjectClassTypeCorrect('CaseFile');
                }
                elseif (strcasecmp($classType, 'File') == 0) {
                    $this->checkObjectClassTypeCorrect('File');
                }
                else {
                    $message = 'Unable to process a specific mappe type. Type identified as (' . $classType . ')';
                    print $message . PHP_EOL;
                    throw new Exception($message);
                }
                $this->numberOfFileProcessed++;
                $this->postProcessFile($classType);
                array_pop($this->stack);
                break;
            case 'registrering':
                // print_r(end($this->stack));
                $classType = get_class(end($this->stack));

                if (strcasecmp($classType, 'Record') == 0) {
                    $this->checkObjectClassTypeCorrect('Record');
                }
                elseif (strcasecmp($classType, 'BasicRecord') == 0) {
                    $this->checkObjectClassTypeCorrect('BasicRecord');
                }
                elseif (strcasecmp($classType, 'RegistryEntry') == 0) {
                    $this->checkObjectClassTypeCorrect('RegistryEntry');
                }
                else {
                    $message = 'Unable to process a specific registrering type. Type identified as (' . $classType . ')';
                    print $message . PHP_EOL;
                    throw new Exception($message);
                }
                $this->numberOfRegistryEntryProcessed++;
                $this->postProcessRecord($classType);
                array_pop($this->stack);
                break;
            case 'dokumentbeskrivelse':
                // print_r(end($this->stack));
                $this->checkObjectClassTypeCorrect('DocumentDescription');
                $this->postProcessDocumentDescription();
                array_pop($this->stack);
                break;
            case 'dokumentobjekt':
                $this->checkObjectClassTypeCorrect('DocumentObject');
                $this->numberOfDocumentsProcessed ++;
                $this->postProcessDocumentObject();
                if ($this->onlyParse == false) {
                    $this->documentFileTestChecksum();
                    $this->documentFileTestFormatValidity();
                    $this->checkFileInDocumentList();
                }
                array_pop($this->stack);
                break;
            case 'arkivskaper':
                // print_r(end($this->stack));
                $this->checkObjectClassTypeCorrect('FondsCreator');
                $this->postProcessFondsCreator();
                array_pop($this->stack);
                break;
            case 'korrespondansepart':
                $this->checkObjectClassTypeCorrect('CorrespondencePart');
                $this->postProcessCorrespondencePart();
                array_pop($this->stack);
                break;
            case 'klasse':
                $this->checkObjectClassTypeCorrect('Klass');
                $this->postProcessClass();
                array_pop($this->stack);
                break;
            case 'klassifikasjonssystem':
                $this->checkObjectClassTypeCorrect('ClassificationSystem');
                $this->postProcessClassificationSystem();
                array_pop($this->stack);
                break;
            case 'avskrivning':
                $this->checkObjectClassTypeCorrect('SignOff');
                $this->postProcessSignOff();
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
            case 'epostadresse':
                $this->handleEmailAddress();
                break;
            case 'filstoerrelse':
                $this->handleFileSize();
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
            case 'klasseID':
                $this->handleClassId();
                break;
            case 'klassifikasjonsType':
                $this->handleClassificationType();
                break;
            case 'kontaktperson':
                $this->handleContactPerson();
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
            case 'mottattDato':
                $this->handleReceivedDate();
                break;
            case 'noekkelord':
                $this->handleKeyword();
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
            case 'referanseArkivdel':
                $this->handleReferenceSeries();
                break;
            case 'referanseAvskrivesAvJournalpost':
                $this->handleReferenceSignedOffByRegistryEntry();
                break;
            case 'referanseDokumentfil':
                $this->handleReferenceDocumentFile();
                break;
            case 'registreringsID':
                $this->handleRecordId();
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
            case 'sakssekvensnummer':
                $this->handleCaseSequenceNumber();
                break;
            case 'tittel':
                $this->handleTitle();
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
            case 'systemID':
                $this->handleSystemId();
                break;
            case 'telefonnummer':
                $this->handleTelephoneNumber();
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
            case 'versjonsnummer':
                $this->handleVersionNumber();
                break;
            case 'variantformat':
                $this->handleVariantFormat();
                break;

            default:
                print '**** Unhandled tag ' . $tag . PHP_EOL;
        }

        $this->currentCdata = "";
    }

    /*
     * function handleSystemId()
     * Can be used by : Fonds, Series, ClassificationSystem, Class, File, Record, DocumentDescription, DocumentObject, Author,
     *
     */
    protected function handleSystemId()
    {
        $object = end($this->stack);
        $object->setSystemId($this->currentCdata);
    }

    protected function handleTitle()
    {
        $object = end($this->stack);
        $object->setTitle($this->currentCdata);
    }

    protected function handleDescription()
    {
        $object = end($this->stack);
        $object->setDescription($this->currentCdata);
    }

    protected function handleFondsStatus()
    {
        $object = end($this->stack);
        $object->setFondsStatus($this->currentCdata);
    }

    protected function handleFormatDetails()
    {
        $object = end($this->stack);
        $object->setFormatDetails($this->currentCdata);
    }

    protected function handleDocumentMedium()
    {
        $object = end($this->stack);
        $object->setDocumentMedium($this->currentCdata);
    }

    protected function handleCreatedDate()
    {
        $object = end($this->stack);
        $object->setCreatedDate($this->currentCdata);
    }

    protected function handleCreatedBy()
    {
        $object = end($this->stack);
        $object->setCreatedBy($this->currentCdata);
    }

    protected function handleFinalisedDate()
    {
        $object = end($this->stack);
        $object->setFinalisedDate($this->currentCdata);
    }

    protected function handleFinalisedBy()
    {
        $object = end($this->stack);
        $object->setFinalisedBy($this->currentCdata);
    }

    protected function handleSeriesStatus()
    {
        $object = end($this->stack);
        $object->setSeriesStatus($this->currentCdata);
    }

    protected function handleSeriesStartDate()
    {
        $object = end($this->stack);
        $object->setSeriesStartDate($this->currentCdata);
    }

    protected function handleSeriesEndDate()
    {
        $object = end($this->stack);
        $object->setSeriesEndDate($this->currentCdata);
    }

    protected function handleOfficialTitle()
    {
        $object = end($this->stack);
        $object->setOfficialTitle($this->currentCdata);
    }

    /*
     * function handleFileId()
     * Can be used by : File
     *
     */
    protected function handleFileId()
    {
        $object = end($this->stack);
        $object->setFileId($this->currentCdata);
    }

    /*
     * function handleAuthor()
     * Can be used by : DocumentDescription, BasicRecord
     *
     */
    protected function handleAuthor()
    {
        $object = end($this->stack);
        $object->addAuthor($this->currentCdata);
    }

    /*
     * function handleRecordId()
     * Can be used by : BasicRecord
     *
     */
    protected function handleRecordId()
    {
        $object = end($this->stack);
        $object->setRecordId($this->currentCdata);
    }

    /*
     * function handleArchivedBy()
     * Can be used by : Record
     *
     */
    protected function handleArchivedBy()
    {
        $object = end($this->stack);
        $object->setArchivedBy($this->currentCdata);
    }

    /*
     * function handleArchivedDate()
     * Can be used by : Record
     *
     */
    protected function handleArchivedDate()
    {
        $object = end($this->stack);
        $object->setArchivedDate($this->currentCdata);
    }

    /*
     * function handleAssociatedBy()
     * Can be used by : DocumentDescription
     *
     */
    protected function handleAssociatedBy()
    {
        $object = end($this->stack);
        $object->setAssociatedBy($this->currentCdata);
    }

    /*
     * function handleAssociatedWithRecordAs()
     * Can be used by : DocumentDescription
     *
     */
    protected function handleAssociatedWithRecordAs()
    {
        $object = end($this->stack);
        $object->setAssociatedWithRecordAs($this->currentCdata);
    }

    /*
     * function handleAssociationDate()
     * Can be used by : DocumentDescription
     *
     */
    protected function handleAssociationDate()
    {
        $object = end($this->stack);
        $object->setAssociationDate($this->currentCdata);
    }

    /*
     * function handleCaseDate()
     * Can be used by :
     *
     */
    protected function handleCaseDate()
    {
        $object = end($this->stack);
        $object->setCaseDate($this->currentCdata);
    }

    /*
     * function handleCaseResponsible()
     * Can be used by :
     *
     */
    protected function handleCaseResponsible()
    {
        $object = end($this->stack);
        $object->setCaseResponsible($this->currentCdata);
    }

    /*
     * function handleCaseSequenceNumber()
     * Can be used by :
     *
     */
    protected function handleCaseSequenceNumber()
    {
        $object = end($this->stack);
        $object->setCaseSequenceNumber($this->currentCdata);
    }

    /*
     * function handleCaseStatus()
     * Can be used by :
     *
     */
    protected function handleCaseStatus()
    {
        $object = end($this->stack);
        $object->setCaseStatus($this->currentCdata);
    }

    /*
     * function handleCaseYear()
     * Can be used by :
     *
     */
    protected function handleCaseYear()
    {
        $object = end($this->stack);
        $object->setCaseYear($this->currentCdata);
    }

    /*
     * function handleChecksum()
     * Can be used by :
     *
     */
    protected function handleChecksum()
    {
        $object = end($this->stack);
        $object->setChecksum($this->currentCdata);
    }

    /*
     * function handleChecksumAlgorithm()
     * Can be used by :
     *
     */
    protected function handleChecksumAlgorithm()
    {
        $object = end($this->stack);
        $object->setChecksumAlgorithm($this->currentCdata);
    }

    /*
     * function handleClassId()
     * Can be used by :
     *
     */
    protected function handleClassId()
    {
        $object = end($this->stack);
        $object->setClassId($this->currentCdata);
    }

    /*
     * function handleClassificationType()
     * Can be used by :
     *
     */
    protected function handleClassificationType()
    {
        $object = end($this->stack);
        $object->setClassificationType($this->currentCdata);
    }

    /*
     * function handleDocumentDate()
     * Can be used by :
     *
     */
    protected function handleDocumentDate()
    {
        $object = end($this->stack);
        $object->setDocumentDate($this->currentCdata);
    }

    /*
     * function handleDocumentNumber()
     * Can be used by :
     *
     */
    protected function handleDocumentNumber()
    {
        $object = end($this->stack);
        $object->setDocumentNumber($this->currentCdata);
    }

    /*
     * function handleDocumentStatus()
     * Can be used by :
     *
     */
    protected function handleDocumentStatus()
    {
        $object = end($this->stack);
        $object->setDocumentStatus($this->currentCdata);
    }

    /*
     * function handleDocumentType()
     * Can be used by :
     *
     */
    protected function handleDocumentType()
    {
        $object = end($this->stack);
        $object->setDocumentType($this->currentCdata);
    }

    /*
     * function handleDueDate()
     * Can be used by :
     *
     */
    protected function handleDueDate()
    {
        $object = end($this->stack);
        $object->setDueDate($this->currentCdata);
    }

    /*
     * function handle()
     * Can be used by :
     *
     */
    protected function handleFileSize()
    {
        $object = end($this->stack);
        $object->setFileSize($this->currentCdata);
    }

    /*
     * function handle()
     * Can be used by :
     *
     */
    protected function handleVersionNumber()
    {
        $object = end($this->stack);
        $object->setVersionNumber($this->currentCdata);
    }

    /*
     * function handleVariantFormat()
     * Can be used by :
     *
     */
    protected function handleVariantFormat()
    {
        $object = end($this->stack);
        $object->setVariantFormat($this->currentCdata);
    }

    /*
     * function handle()
     * Can be used by :
     *
     */
    protected function handleFormat()
    {
        $object = end($this->stack);
        $object->setFormat($this->currentCdata);
    }

    /*
     * function handle()
     * Can be used by :
     *
     */
    protected function handleReferenceDocumentFile()
    {
        $object = end($this->stack);
        $object->setReferenceDocumentFile($this->currentCdata);
    }

    /*
     * function handleFondsCreatorName()
     * Can be used by :
     *
     */
    protected function handleFondsCreatorName()
    {
        $object = end($this->stack);
        $object->setFondsCreatorName($this->currentCdata);
    }

    /*
     * function handleFondsCreatorID()
     * Can be used by :
     *
     */
    protected function handleFondsCreatorID()
    {
        $object = end($this->stack);
        $object->setFondsCreatorID($this->currentCdata);
    }

    /*
     * function handleRecordYear()
     * Can be used by :
     *
     */
    protected function handleRecordYear()
    {
        $object = end($this->stack);
        $object->setRecordYear($this->currentCdata);
    }

    /*
     * function handleReceivedDate()
     * Can be used by :
     *
     */
    protected function handleReceivedDate()
    {
        $object = end($this->stack);
        $object->setReceivedDate($this->currentCdata);
    }

    /*
     * function handleCountry()
     * Can be used by :
     *
     */
    protected function handleCountry()
    {
        $object = end($this->stack);
        $object->setCountry($this->currentCdata);
    }

    /*
     * function handleCorrespondencePartType()
     * Can be used by :
     *
     */
    protected function handleCorrespondencePartType()
    {
        $object = end($this->stack);
        $object->setCorrespondancePartType($this->currentCdata);
    }

    /*
     * function handleCorrespondencePartName()
     * Can be used by :
     *
     */
    protected function handleCorrespondencePartName()
    {
        $object = end($this->stack);
        $object->setCorrespondancePartName($this->currentCdata);
    }

    /*
     * function handleContactPerson()
     * Can be used by :
     *
     */
    protected function handleContactPerson()
    {
        $object = end($this->stack);
        $object->setContactPerson($this->currentCdata);
    }

    /*
     * function handleRecordStatus()
     * Can be used by :
     *
     */
    protected function handleRecordStatus()
    {
        $object = end($this->stack);
        $object->setRecordStatus($this->currentCdata);
    }

    /*
     * function handleRecordSequenceNumber()
     * Can be used by :
     *
     */
    protected function handleRecordSequenceNumber()
    {
        $object = end($this->stack);
        $object->setRecordSequenceNumber($this->currentCdata);
    }

    /*
     * function handleRecordType()
     * Can be used by :
     *
     */
    //TODO: Check this
    protected function handleRecordType()
    {
        $object = end($this->stack);
        $object->setRegistryEntryType($this->currentCdata);
    }

    /*
     * function handleRecordNumber()
     * Can be used by :
     *
     */
    // TODO: Check this
    protected function handleRecordNumber()
    {
        $object = end($this->stack);
        $object->setRecordId($this->currentCdata);
    }

    /*
     * function handleRecordDate()
     * Can be used by :
     *
     */
    protected function handleRecordDate()
    {
        $object = end($this->stack);
        $object->setRecordDate($this->currentCdata);
    }

    /*
     * function handleEmailAddress()
     * Can be used by :
     *
     */
    protected function handleEmailAddress()
    {
        $object = end($this->stack);
        $object->setEmailAddress($this->currentCdata);
    }

    /*
     * function handleAdministrativeUnit()
     * Can be used by :
     *
     */
    protected function handleAdministrativeUnit()
    {
        $object = end($this->stack);
        $object->setAdministrativeUnit($this->currentCdata);
    }

    /*
     * function handleNumberOfAttachments()
     * Can be used by :
     *
     */
    protected function handleNumberOfAttachments()
    {
        $object = end($this->stack);
        $object->setNumberOfAttachments($this->currentCdata);
    }

    /*
     * function handlePostalAddress()
     * Can be used by :
     *
     */
    protected function handlePostalAddress()
    {
        $object = end($this->stack);
        $object->setPostalAddress($this->currentCdata);
    }

    /*
     * function handlePostalNumber()
     * Can be used by :
     *
     */
    protected function handlePostalNumber()
    {
        $object = end($this->stack);
        $object->setPostCode($this->currentCdata);
    }

    /*
     * function handle()
     * Can be used by :
     *
     */
    protected function handle()
    {
        $object = end($this->stack);
        $object->set($this->currentCdata);
    }

    /*
     * function handlePostalTown()
     * Can be used by :
     *
     */
    protected function handlePostalTown()
    {
        $object = end($this->stack);
        $object->setPostalTown($this->currentCdata);
    }

    /*
     * function handleReferenceSeries()
     * Can be used by :
     *
     */
    protected function handleReferenceSeries()
    {
        $object = end($this->stack);
        $object->addReferenceSeries($this->currentCdata);
    }

    /*
     * function handleCaseHandler()
     * Can be used by :
     *
     */
    protected function handleCaseHandler()
    {
        $object = end($this->stack);
        $object->setCaseHandler($this->currentCdata);
    }

    /*
     * function handle()
     * Can be used by :
     *
     */
    protected function handleTelephoneNumber()
    {
        $object = end($this->stack);
        $object->setTelephoneNumber($this->currentCdata);
    }

    protected function checkObjectClassTypeCorrect($className)
    {
        if (strcmp($className, get_class(end($this->stack))) != 0) {
            throw new Exception('Error processing arkivstruktur.xml. Expected (' . $className . ') found (' . get_class(end($this->stack)) . '). Unsafe processing.');
        }
        return true;
    }

    protected function checkFileInDocumentList()
    {
        $currentDocumentObject = end($this->stack);
        $this->documentListHandler->remove($currentDocumentObject->getReferenceDocumentFile());
    }
    protected function documentFileTestChecksum()
    {
        $currentDocumentObject = end($this->stack);
        $testProperty = new FileChecksumTestProperty(Constants::TEST_CHECKSUM);
        $checksumTest = new ChecksumTest(Constants::TEST_CHECKSUM, $currentDocumentObject->getReferenceDocumentFile(), $this->directory, $currentDocumentObject->getChecksumAlgorithm(), $currentDocumentObject->getChecksum(), $testProperty);
        $checksumTest->runTest();


        // In the final report we only want to show documents that have failed checksum
        // All checksums will be reported in the log file
        if ($testProperty->getTestResult() == false) {
            $this->testResultsHandler->addResult($testProperty, Constants::TEST_TYPE_A2);

        }
        print $testProperty . PHP_EOL;
        $checksumTest = null;
    }

    protected function handleReferenceSignedOffByRegistryEntry(){
        $object = end($this->stack);
        $object->setSignOffDate($this->currentCdata);
    }

    protected function handleSignOffDate() {
        $object = end($this->stack);
        $object->setSignOffDate($this->currentCdata);
    }

    protected function handleSignOffBy() {
        $object = end($this->stack);
        $object->setSignOffBy($this->currentCdata);
    }

    protected function handleSignOffMethod() {
        $object = end($this->stack);
        $object->setSignOffMethod($this->currentCdata);
    }


    protected function handleKeyword() {
        $object = end($this->stack);
        $object->setKeyword($this->currentCdata);
    }

    protected function handleRecordsManagementUnit() {
        $object = end($this->stack);
        $object->setRecordsManagementUnit($this->currentCdata);
    }

    protected function handleSentDate() {
        $object = end($this->stack);
        $object->setSentDate($this->currentCdata);
    }

    protected function handleStorageLocation() {
        $object = end($this->stack);
        $object->addReferenceStorageLocation($this->currentCdata);
    }

    protected function documentFileTestFormatValidity()
    {}

    public function result()
    {
        //print 'End ... ' . PHP_EOL;
        //print_r($this->stack);
    }

    public function getNumberOfFileProcessed() {
        return $this->numberOfFileProcessed;
    }

    public function getNumberOfDocumentsProcessed()
    {
        return $this->numberOfDocumentsProcessed;
    }

    public function cdata($parser, $cdata)
    {
        // If cdata is only whitespace just return
        if (! trim($cdata))
            return;
        $this->currentCdata .= $cdata;
    }

    public function getNumberOfRegistryEntryProcessed()
    {
        return $this->numberOfRegistryEntryProcessed;
    }



    public function postProcessSignOff() {

    }

    public function postProcessClassificationSystem() {

    }

    public function postProcessClass() {

    }

    public function postProcessCorrespondencePart() {

    }

    public function postProcessDocumentObject() {

    }

    public function postProcessDocumentDescription() {

    }

    public function postProcessFile() {

    }

    public function postProcessFonds() {

    }

    public function postProcessFondsCreator() {

    }

    public function postProcessRecord() {

    }

    public function postProcessSeries() {

    }


    public function preProcessSignOff() {

    }

    public function preProcessClassificationSystem() {

    }

    public function preProcessClass() {

    }

    public function preProcessCorrespondencePart() {

    }

    public function preProcessDocumentObject() {

    }

    public function preProcessDocumentDescription() {

    }

    public function preProcessFile() {

    }

    public function preProcessFonds() {

    }

    public function preProcessFondsCreator() {

    }

    public function preProcessRecord() {

    }

    public function preProcessSeries() {

    }
}

?>

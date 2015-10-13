<?php
require_once ('tests/xml/XMLTestValidation.php');
require_once ('tests/xml/XMLTestWellFormed.php');
require_once ('testProperties/XMLWellFormedTestProperty.php');
require_once ('handler/ArkivuttrekkHandler.php');
require_once ('tests/file/DocumentDirectoryTest.php');
require_once ('tests/file/FileExistsTest.php');
require_once ('tests/noark5/v31/CheckNumberObjectsArkivutrekk.php');
require_once ('handler/DokumenterFolderHandler.php');
require_once ('handler/DocumentListHandler.php');
require_once ('handler/DokumenterFolderCounter.php');
require_once ('handler/tests/ArkivstrukturDocumentChecksumTest.php');
require_once ('handler/tests/AllIncomingRegistryEntrySignedOff.php');
require_once ('testProperties/DocumentDirectoryTestProperty.php');
require_once ('tests/Test.php');

/**
 * A set of standard tests that should be undertaken on a Noark 5 extraction.
 *
 * None of the tests should be memory intensive, but the test that crosschecks
 * the DocumentObjects against all the files in the document directory, creates
 * a list of all document names.
 *
 * If this proves to be a problem, we could replace the code and just test for the
 * existence of each file, but we would not find out if there are any extra documents
 * in the extraction.
 *
 * Some tests that could be undertaken are
 * Look at presedens / kassasjon etc and check what makes sense
 * Check the kryssreferanse actually points to something and of the correct type
 */

class StandardTest extends Test
{

    protected $directory;

    /**
     *
     * @var XML-Content $standardExtractionContents: Contents of an XML under resources that contains
     *                                               a list of files we expect to find in the extraction
     *                                               directory as well as which files to validate
     */
    protected $standardExtractionContents;

    /**
     *
     * @var string $arkivstrukturFilename: Path and filename of the file arkivstruktur.xml. In theory
     *                                     this file could actually have a different name that is
     *                                     defined in arkivutrekk.xml
     */
    protected $arkivstrukturFilename;

    /**
     *
     * @var TestResultsHandler $testResultsHandler: An object that collects the results of tests that
     *                                              are used in the final report when creating a
     *                                              odt/docx document
     */
    protected $testResultsHandler;

    /**
     *
     * @var InfoFileDetails $infoFileDetails: All the values from the file info.xml
     */
    protected $infoFileDetails;

    /**
     *
     * @var ArkivUttrekkDetails $arkivUttrekkDetails: All the values from the file arkivutrrekk.xml
     */
    protected $arkivUttrekkDetails;

    /**
     *
     * @var string $arkivstrukturFilename: Path to where the document directory is
     */
    protected $dokumenterDirectory;

    /**
     *
     * @var ArkivstrukturStatistics $statistics: counts of the various noark5 complextypes that have been processed
     */
    protected $statistics = null;

    /**
     *
     * @var array $testsToRun: List of tests to run
     */
    protected $testsToRun;

    public function __construct($testName, $directory, $testResultsHandler, $infoFilename, $noark5StructureFile, $testsToRun, $testProperty)
    {
        parent::__construct($testName, $testProperty);
        $this->directory = $directory;
        $this->testResultsHandler = $testResultsHandler;

        // A list of files we expect to see in the directory and a list of xml-files to validate
        if ($noark5StructureFile !== null) {
            $this->standardExtractionContents = simplexml_load_file($noark5StructureFile);
        } else {
            $this->standardExtractionContents = simplexml_load_file(Constants::LOCATION_OF_NOARK5_V31_STRUCTURE_FILE);
        }

        if ($this->testsToRun === null) {
            $this->testsToRun = array();
            $this->setAllTestsRunnable();
        }
        $this->processArkivUttrekk();
        $this->processInfoFile($infoFilename);

        $this->arkivstrukturFilename = $this->arkivUttrekkDetails->getArkivstruktur()->getFilename();
    }

    /*
     * This function processes the file arkivuttrekk.xml and populates $this->arkivUttrekkDetails
     * with all the various values in arkivuttrekk.xml. This function is called from the constructor
     * and is a kind of prerequirement to running the standard tests
     */
    public function processArkivUttrekk()
    {
        $arkivUttrekkHandler = new ArkivuttrekkHandler(join(DIRECTORY_SEPARATOR, array(
            $this->directory,
            Constants::NAME_ARKIVUTTREKK_XML
        )));
        $arkivUttrekkHandler->processArkivuttrekk();
        $this->arkivUttrekkDetails = $arkivUttrekkHandler->getArkivUttrekkDetails();
        $arkivUttrekkHandler = null;
    }

    /*
     * This function processes the file info.xml and populates $this->infoFileDetails
     * with all the various values in info.xml. This function is called from the constructor
     * and is a kind of prerequirement to running the standard tests. The most important
     * value in this file is the checksum covering the file arkivuttrekk.xml.
     */
    public function processInfoFile($infoFilename)
    {
        $infoFileHandler = new InfoFileHandler($infoFilename);
        $infoFileHandler->processInfofile();
        $this->infoFileDetails = $infoFileHandler->getInfoFileDetails();
        $infoFileHandler = null;
    }

    /*
     * Runs all the various tests.
     *
     */
    public function runTest()
    {
        // File existence and readable test
        if ($this->testsToRun[Constants::TEST_TYPE_A0] === true) {
            $this->testA0();
        }

        // All XML-files well-formed and readable
        if ($this->testsToRun[Constants::TEST_TYPE_A1] === true) {
            $this->testA1();
        }

        // Check checksums of all documents in document folder
        if ($this->testsToRun[Constants::TEST_TYPE_A2] === true) {
            $this->testA2();
        }

        // All documents are in an archive approved format
        if ($this->testsToRun[Constants::TEST_TYPE_A3] === true) {
            $this->testA3();
        }

        // Crosscheck endringslogg.xml against arkivstruktur.xml
        if ($this->testsToRun[Constants::TEST_TYPE_A4] === true) {
            $this->testA4();
        }

        // Count of douments crossreference arkivstruktur.xml /arkivuttrekk.xml
        if ($this->testsToRun[Constants::TEST_TYPE_A5] === true) {
            $this->testA5();
        }

        // Crossreference DocumentObjects against dokumenter directory, find missing and extra
        if ($this->testsToRun[Constants::TEST_TYPE_A6] === true) {
            $this->testA6();
        }

        // Crossreference number of <mappe> against value in arkivuttrekk.xml
        if ($this->testsToRun[Constants::TEST_TYPE_A7] === true) {
            $this->testA7();
        }

        // Crossreference number of <registrering> against value in arkivuttrekk.xml
        if ($this->testsToRun[Constants::TEST_TYPE_A8] === true) {
            $this->testA8();
        }

        // Calculate and check the checksum value of arkivuttrekk.xml
        // against the value specified in info.xml
        if ($this->testsToRun[Constants::TEST_TYPE_A9] === true) {
            $this->testA9();
        }

        // Test if number of documents specified in arkivuttrekk.xml is
        // correct with count of documents in the dokumenter folder
        if ($this->testsToRun[Constants::TEST_TYPE_A10] === true) {
            $this->testA10();
        }

        // Check all incoming RegistryEntry are signedOff
        if ($this->testsToRun[Constants::TEST_TYPE_C1] === true) {
            $this->testC1();
        }

        //
        if ($this->testsToRun[Constants::TEST_TYPE_C2] === true) {
            // $this->testC2();
        }

        //
        if ($this->testsToRun[Constants::TEST_TYPE_C3] === true) {
            // $this->testC3();
        }

        //
        if ($this->testsToRun[Constants::TEST_TYPE_C4] === true) {
            // $this->testC4();
        }

        //
        if ($this->testsToRun[Constants::TEST_TYPE_C5] === true) {
            // $this->testC5();
        }

        //
        if ($this->testsToRun[Constants::TEST_TYPE_C6] === true) {
            // $this->testC6();
        }

        //
        if ($this->testsToRun[Constants::TEST_TYPE_C7] === true) {
            // $this->testC7();
        }

        //
        if ($this->testsToRun[Constants::TEST_TYPE_C8] === true) {
            // $this->testC8();
        }

        //
        if ($this->testsToRun[Constants::TEST_TYPE_C9] === true) {
            // $this->testC9();
        }

        //
        if ($this->testsToRun[Constants::TEST_TYPE_C10] === true) {
            // $this->testC10();
        }

        //
        if ($this->testsToRun[Constants::TEST_TYPE_C11] === true) {
            // $this->testC11();
        }

        //
        if ($this->testsToRun[Constants::TEST_TYPE_C12] === true) {
            // $this->testC12();
        }

        //
        if ($this->testsToRun[Constants::TEST_TYPE_C13] === true) {
            // $this->testC13();
        }

        //
        if ($this->testsToRun[Constants::TEST_TYPE_C14] === true) {
            // $this->testC14();
        }

        //
        if ($this->testsToRun[Constants::TEST_TYPE_C15] === true) {
            // $this->testC15();
        }

        //
        if ($this->testsToRun[Constants::TEST_TYPE_C16] === true) {
            // $this->testC16();
        }
    }

    /*
     * Test A0 : File existence and readable
     *
     * Checks if all the files that we expect in the directory are present and readable
     *
     */
    public function testA0()
    {
        $this->logger->info('');
        $this->logger->info('START test A0 : Check files exist and are readable');
        try {
            foreach ($this->standardExtractionContents->directoryContents->file as $file) {
                $testProperty = new TestProperty(Constants::TEST_FILE_EXISTS_AND_READABLE);
                $fileExistsTest = new FileExistsTest(Constants::TEST_FILE_EXISTS_AND_READABLE, $file->filename, $this->directory, $testProperty);
                $fileExistsTest->runTest();

                if ($testProperty->getTestResult() == true) {
                    $this->logger->info('  RESULT There are no problems reading the file [' . $file->filename . '].');
                } else {
                    $this->logger->error('  RESULT There are problems reading the file [' . $file->filename . ']. See log file for details.');
                    // This test is only reported in the reportfile, in the event of a failure
                    $this->testResultsHandler->addResult($testProperty, Constants::TEST_TYPE_A0);
                    $this->testProperty->setTestResult(false);
                }
            }
        } catch (Exception $e) {
            $this->logger->error("Error when attempting test " . $testProperty->getDescription() . ". The following exception occured " . $e);
        }
        $this->logger->info('END test A0');
        $this->logger->info('');
    }

    /*
     * Test A1
     *
     * Checks if all the xml files in the directory are well-formed and valid
     * The test checks whether the XML files are wellformed according to the XML 1.0 standard, and if
     * the files validates against their respective Noark 5 XSD schema.
     *
     */
    public function testA1()
    {
        $this->logger->info('START test A1 : XML files are wellformed and valid');
        try {
            $this->logger->info('  Testing all XML/XSD files for well-formedness');

            foreach ($this->standardExtractionContents->directoryContents->file as $file) {
                $xmlValidationTestProperty = new XMLWellFormedTestProperty(Constants::TEST_XMLTEST_VALIDATION_WELLFORMED);
                $xmlTestWellFormed = new XMLTestWellFormed(Constants::TEST_XMLTEST_VALIDATION_WELLFORMED, $this->directory, $file->filename, $xmlValidationTestProperty);
                $xmlTestWellFormed->runTest();
                if ($xmlValidationTestProperty->getTestResult() == false) {
                    $this->testProperty->setTestResult(false);
                }
                $this->testResultsHandler->addResult($xmlValidationTestProperty, Constants::TEST_TYPE_A1);
                $xmlTestWellFormed = null;
            }

            $this->logger->info('  Testing all XML files for validity');

            foreach ($this->standardExtractionContents->filesToValidate->file as $file) {
                $testProperty = new XMLValidationTestProperty(Constants::TEST_XMLTEST_VALIDATION_VALID);
                $xmlTestValidation = new XMLTestValidation(Constants::TEST_XMLTEST_VALIDATION_VALID, $this->directory, $file->filename, $file->validatedBy, $testProperty);
                $xmlTestValidation->runTest();
                if ($testProperty->getTestResult() == false) {
                    $this->testProperty->setTestResult(false);
                }
                $this->testResultsHandler->addResult($testProperty, Constants::TEST_TYPE_A1);
                $xmlTestValidation = null;
            }
        } catch (Exception $e) {
            $this->logger->error("Error when attempting test A1. The following exception occured " . $e);
        }
        $this->logger->info('END test A1');
        $this->logger->info('');
    }

    /*
     * Test A2 : Checksum of documents
     * Calculate checksums of all documents listed under a DocumentObject and report if it is correct or not
     *
     */
    public function testA2()
    {
        $this->logger->info('START test A2 : Checksum test of all documents');
        $testProperty = new TestProperty(Constants::TEST_CHECKSUM_ALL_DOCUMENTS);
        try {
            $allDocumentChecksumTest = new Test(Constants::TEST_CHECKSUM_ALL_DOCUMENTS, $testProperty);

            // We don't call $allDocumentChecksumTest->runTest because the test is handled
            // in ArkivstrukturDocumentChecksumTest durnig parsing of the file
            $akivstrukturParser = new ArkivstrukturDocumentChecksumTest($this->directory);
            $this->parseFile($akivstrukturParser, $this->arkivstrukturFilename);

            if ($akivstrukturParser->getErrorsEncountered() == true) {
                $testProperty->addTestResultReportDescription('Det var funnet feil med sjeksumm av filer i dokument mappen. Antall feil funnet er ' . $akivstrukturParser->getNumberErrorsEncountered() . ' logfilen inneholder en liste av filer som ble sjekket');
                $this->testProperty->setTestResult(false);
                $this->logger->error(' RESULT Some files failed checksum test');
            }
            else {
                $this->logger->info(' RESULT All files passed checksum test');
            }
            $this->testResultsHandler->addResult($testProperty, Constants::TEST_TYPE_A2);
        } catch (Exception $e) {
            $this->logger->error("Error when attempting test " . $testProperty->getDescription() . ". The following exception occured " . $e);
        }
        $this->logger->info('END test A2');
        $this->logger->info('');
    }

    /*
     * Test A3: All documents are in an archive approved format
     *
     */
    public function testA3()
    {
        $this->logger->info('START test A3 : All documents in archive format');
        try {
            $this->logger->warn(' This test has not been implemented yet');
        } catch (Exception $e) {
            $this->logger->error("Error when attempting test " . $testProperty->getDescription() . ". The following exception occured " . $e);
        }
        $this->logger->info('END test A3');
        $this->logger->info('');
    }

    /*
     * Test A4 : Crosscheck endringslogg.xml against arkivstruktur.xml
     *
     */
    public function testA4()
    {
        $this->logger->info('START test A4 : Crosscheck endringslogg.xml against arkivstruktur.xml');
        try {
            $this->logger->warn(' This test has not been implemented yet');
        } catch (Exception $e) {
            $this->logger->error("Error when attempting test " . $testProperty->getDescription() . ". The following exception occured " . $e);
        }
        $this->logger->info('END test A4');
        $this->logger->info('');
    }

    /*
     * Test A5 : Count of douments crossreference arkivstruktur.xml /arkivuttrekk.xml
     *
     * Tester om antall dokumenter som oppgis i arkivstruktur.xml validerer mot antall dokumenter
     * som oppgis i «antallDokumentfiler» i arkivuttrekk.xml – dvs om antall dokumenter hentet ut
     * i uttrekket stemmer overens med faktiske dokumenter i arkivdelen.';
     *
     */
    public function testA5()
    {
        $this->logger->info('START test A5 : Crosscheck arkivuttrekk.xml (antallDokumentfiler) equal to number of DocumentObjects processed');
        $testProperty = new TestProperty(Constants::TEST_COUNT_DOCUMENTS_ARKIVUTTREKK);

        try {
            if ($this->statistics === null) {
                $this->parseArkivstruktur();
            }

            $extractionInfo = $this->arkivUttrekkDetails->getExtractionInfo();
            $numberOfDocumentsReportedInArkivUttrekk = $extractionInfo->getAntallDokumentfiler();

            $documentDirectoryTest = new CheckNumberObjectsArkivutrekk(Constants::TEST_COUNT_DOCUMENTS_ARKIVUTTREKK, $numberOfDocumentsReportedInArkivUttrekk, $this->statistics->getNumberOfDocumentObjectProcessed(), Constants::NAME_ARKIVSTRUKTUR_XML, 'dokument', $testProperty);
            $documentDirectoryTest->runTest();

            if ($testProperty->getTestResult() == true) {
                $this->logger->info(' RESULT Number of DocumentObjects (' . $this->statistics->getNumberOfDocumentObjectProcessed() . ') equals number <antallDokumentfiler> ('. $numberOfDocumentsReportedInArkivUttrekk .')');
            }
            else {
                $this->logger->error('RESULT Number of DocumentObjects (' . $this->statistics->getNumberOfDocumentObjectProcessed() . ') does not equal number <antallDokumentfiler> ('. $numberOfDocumentsReportedInArkivUttrekk .')');
            }
            $this->testResultsHandler->addResult($testProperty, Constants::TEST_TYPE_A5);
        } catch (Exception $e) {
            $this->logger->error("Error when attempting test " . $testProperty->getDescription() . ". The following exception occured " . $e);
        }
        $this->logger->info('END test A5');
        $this->logger->info('');
    }

    /*
     * Test A6 Crossreference DocumentObjects against dokumenter directory, find missing and extra
     *
     * Existence test of documents in document folder against list of DocumentObjects in arkivstruktur.xml
     *
     * NOTE: If this test crashes the script, you should consider increasing the amount of memory available to the script
     *
     */
    public function testA6()
    {
        $this->logger->info('START test A6 :');
        $testProperty = new DocumentDirectoryTestProperty(Constants::TEST_DOCUMENT_DIRECTORY);

        try {
            $this->documentListOverview();

            if ($this->statistics === null) {
                $this->parseArkivstruktur();
            }

            $numberOfDocumentsProcessed = $this->statistics->getNumberOfDocumentObjectProcessed();

            $documentDirectoryTest = new DocumentDirectoryTest(Constants::TEST_DOCUMENT_DIRECTORY, $this->dokumenterDirectory, $this->documentListHandler, $testProperty);
            $documentDirectoryTest->runTest();

            if ($testProperty->getTestResult() == true) {
                $this->logger->info(' RESULT No Errors found when processing dokumenter directory.');
            }
            else {
                $this->logger->error(' RESULT Errors found when processing dokumenter directory. See log file for details.');
            }

            $this->testResultsHandler->addResult($testProperty, Constants::TEST_TYPE_A7);

        } catch (Exception $e) {
            $this->logger->error('Error when attempting test' . $testProperty->getDescription() . ". The following exception occured " . $e);
        }
        $this->logger->info('END test A6');
        $this->logger->info('');
    }

    /*
     * Test A7: Crossreference number of <mappe> against value in arkivuttrekk.xml
     * Tester om antall elementer av type «mappe» stemmer overens med antall «mappe numerOfOccurrences»
     * i arkivuttrekk.xml – altså om antall mapper som blir med ut i uttrekket stemmer overens med faktisk
     * antall mapper i arkivdelen.
     *
     */
    public function testA7()
    {
        $this->logger->info('START test A7: Crossreference number of <mappe> against value in arkivuttrekk.xml');
        $testProperty = new TestProperty(Constants::TEST_COUNT_MAPPE_ARKIVUTTREKK);

        try {

            if ($this->statistics === null) {
                $this->parseArkivstruktur();
            }

            $arkivstrukturInfo = $this->arkivUttrekkDetails->getArkivstruktur();
            $numberOfFileReportedInArkivUttrekk = $arkivstrukturInfo->getNumberMappe();

            $documentDirectoryTest = new CheckNumberObjectsArkivutrekk(Constants::TEST_COUNT_MAPPE_ARKIVUTTREKK, $numberOfFileReportedInArkivUttrekk, $this->statistics->getNumberOfFileProcessed(), Constants::NAME_ARKIVSTRUKTUR_XML, 'mappe', $testProperty);
            $documentDirectoryTest->runTest();

            if ($testProperty->getTestResult() == true) {
                $this->logger->info(' RESULT The number of mappe in arkivstruktur.xml (' . $this->statistics->getNumberOfFileProcessed() . ') equals the number in arkivuttrekk.xml (' . $numberOfFileReportedInArkivUttrekk . ')');
            }
            else {
                $this->logger->error(' RESULT The number of mappe in arkivstruktur.xml (' . $this->statistics->getNumberOfFileProcessed() . ') does not equal the number in arkivuttrekk.xml (' . $numberOfFileReportedInArkivUttrekk . ')');
            }

            $this->testResultsHandler->addResult($testProperty, Constants::TEST_TYPE_A7);
        } catch (Exception $e) {
            $this->logger->error('Error when attempting test ' . $testProperty->getDescription() . ". The following exception occured " . $e);
        }
        $this->logger->info('END test A7');
        $this->logger->info('');
    }

    /*
     * Test A8: Crossreference number of <registrering> against value in arkivuttrekk.xml
     *
     * Tester om antall elementer av type «registrering» stemmer overens med antall «registrering numerOfOccurrences»
     * i arkivuttrekk.xml – altså om antall registrering som blir med ut i uttrekket stemmer overens med faktisk
     * antall registrering i arkivdelen.
     *
     */
    public function testA8()
    {
        $this->logger->info('START test A8 : Crossreference number of <registrering> against value in arkivuttrekk.xml');
        $testProperty = new TestProperty(Constants::TEST_COUNT_MAPPE_ARKIVUTTREKK);

        try {
            if ($this->statistics === null) {
                $this->parseArkivstruktur();
            }

            $arkivstrukturInfo = $this->arkivUttrekkDetails->getArkivstruktur();
            $numberOfRegistrationReportedInArkivUttrekk = $arkivstrukturInfo->getNumberRegistrering();

            $documentDirectoryTest = new CheckNumberObjectsArkivutrekk(Constants::TEST_COUNT_MAPPE_ARKIVUTTREKK, $numberOfRegistrationReportedInArkivUttrekk, $this->statistics->getNumberOfRecordProcessed(), Constants::NAME_ARKIVSTRUKTUR_XML, 'registrering', $testProperty);
            $documentDirectoryTest->runTest();

            if ($testProperty->getTestResult() == true) {
                $this->logger->info(' RESULT The number of registrering in arkivstruktur.xml (' . $this->statistics->getNumberOfRecordProcessed()  . ') equals the number in arkivuttrekk.xml (' . $numberOfRegistrationReportedInArkivUttrekk . ')');
            }
            else {
                $this->logger->error(' RESULT The number of registrering in arkivstruktur.xml (' . $this->statistics->getNumberOfRecordProcessed()  . ') does not equal the number in arkivuttrekk.xml (' . $numberOfRegistrationReportedInArkivUttrekk . ')');
            }

            $this->testResultsHandler->addResult($testProperty, Constants::TEST_TYPE_A8);
        } catch (Exception $e) {
            $this->logger->error("Error when attempting test " . $testProperty->getDescription() . ". The following exception occured " . $e);
        }
        $this->logger->info('END test A8');
        $this->logger->info('');
    }

    /*
     * Test A9: Calculate and check the checksum value of arkivuttrekk.xml against the value specified in info.xml
     */
    public function testA9()
    {
        $this->logger->info('START test A9: Calculate and check the checksum value of arkivuttrekk.xml against the value specified in info.xml');
        try {
            $checksumValue = $this->infoFileDetails->getChecksumInfo()->getChecksumValue();
            $checksumAlgorithm = $this->infoFileDetails->getChecksumInfo()->getChecksumAlgorithm();

            $testProperty = new TestProperty(Constants::TEST_CHECKSUM);
            $checksumTest = new ChecksumTest(Constants::TEST_CHECKSUM, Constants::NAME_ARKIVUTTREKK_XML, $this->directory, $checksumAlgorithm, $checksumValue, $testProperty);
            $checksumTest->runTest();

            if ($testProperty->getTestResult() == true) {
                $this->logger->info(' RESULT The checksum for arkivuttrekk.xml, specified in info.xml has been checked and found to be correct');
            } else {
                $this->logger->error(' RESULT The checksum for arkivuttrekk.xml, specified in info.xml has been checked and found to be incorrect!. See logfile for details');
            }
            $this->testResultsHandler->addResult($testProperty, Constants::TEST_TYPE_A9);

            $checksumTest = null;
        } catch (Exception $e) {
            $this->logger->error("Error when attempting test " . $testProperty->getDescription() . ". The following exception occured " . $e);
        }
        $this->logger->info('END test A9');
        $this->logger->info('');
    }

    /*
     * Test 10 : Test if number of documents specified in arkivuttrekk.xml is correct with count of
     * documents in the dokumenter folder
     *
     */
    public function testA10()
    {
        $this->logger->info('START test A10: number of documents specified in arkivuttrekk.xml is correct with count of documents in the dokumenter folder');
        try {
            $testProperty = new TestProperty(Constants::TEST_COUNT_DOCUMENTS_ARKIVUTTREKK);
            $extractionInfo = $this->arkivUttrekkDetails->getExtractionInfo();

            $documentDirectory = join(DIRECTORY_SEPARATOR, array(
                                                        $this->directory,
                                                        Constants::NAME_DOCUMENT_DIRECTORY));

            $dokumenterFolderCounter = new DokumenterFolderCounter($documentDirectory, Constants::MAX_RECURSIVE_DEPTH);
            $dokumenterFolderCounter->process();
            $numberOfDocumentsInDirectory = $dokumenterFolderCounter->getNumberOfFiles();

            if ($numberOfDocumentsInDirectory == $extractionInfo->getAntallDokumentfiler()) {
                $testProperty->addTestResult(true);
                $testProperty->addTestResultDescription('Number of documents identified in ' . Constants::NAME_ARKIVUTTREKK_XML . ' is correct. Number identified is ' . $extractionInfo->getAntallDokumentfiler());
                $this->logger->info(' RESULT Number of documents identified in ' . Constants::NAME_ARKIVUTTREKK_XML . ' is correct. Number identified is (' . $extractionInfo->getAntallDokumentfiler() . '), while number of documents found is (' . $numberOfDocumentsInDirectory . ')');

            } else {
                $this->testProperty->addTestResult(false);
                $this->testProperty->addTestResultDescription('Number of documents identified in ' . Constants::NAME_ARKIVUTTREKK_XML . ' is incorrect. Number identified is (' . $extractionInfo->getAntallDokumentfiler() . '), while number of documents found is (' . $numberOfDocumentsInDirectory . ')');
                $this->logger->error(' RESULT Number of documents identified in ' . Constants::NAME_ARKIVUTTREKK_XML . ' is incorrect. Number identified is (' . $extractionInfo->getAntallDokumentfiler() . '), while number of documents found is (' . $numberOfDocumentsInDirectory . ')');
            }

            $this->testResultsHandler->addResult($testProperty, Constants::TEST_TYPE_A6);
        } catch (Exception $e) {
            $this->logger->error("Error when attempting test " . $testProperty->getDescription() . ". The following exception occured " . $e);
        }
        $this->logger->info('END test A10');
        $this->logger->info('');
    }

    /*
     * Test C1 : Check all incoming RegistryEntry are signedOff
     *
     *
     */
    public function testC1()
    {
        $this->logger->info('START test C1  :');
        $testProperty = new TestProperty(Constants::TEST_INCOMING_REGISTRYENTRY_SIGNEDOFF);
        try {
            $allDocumentChecksumTest = new Test(Constants::TEST_INCOMING_REGISTRYENTRY_SIGNEDOFF, $testProperty);

            // We don't call $allDocumentChecksumTest->runTest because the test is handled
            // in ArkivstrukturDocumentChecksumTest durnig parsing of the file
            $akivstrukturParser = new AllIncomingRegistryEntrySignedOff();
            $this->parseFile($akivstrukturParser, $this->arkivstrukturFilename);

            $akivstrukturParser->testOver();
            $numberIncomingRegistryEntryfound = $akivstrukturParser->getNumberIncomingRegistryEntryfound();

            if ($akivstrukturParser->getErrorsEncountered() == true) {
                $numberRegistryEntryWithError = $akivstrukturParser->getNumberErrorsEncountered();
                $this->logger->error(' RESULT (' . $numberIncomingRegistryEntryfound . ') incoming registryentry processed. (' . $numberRegistryEntryWithError . ') missing a signoff/avskrivning');
                $testProperty->addTestResultReportDescription('Det var funnet feil med sjeksumm av filer i dokument mappen. Antall feil funnet er ' . $akivstrukturParser->getNumberErrorsEncountered() . ' logfilen inneholder en liste av filer som ble sjekket');
                $this->testProperty->setTestResult(false);
            }
            else {
                $this->logger->info(' RESULT All incoming registryentry, count (' . $numberIncomingRegistryEntryfound . ') are processed without error');
            }
            $this->testResultsHandler->addResult($testProperty, Constants::TEST_TYPE_C1);
        } catch (Exception $e) {
            $this->logger->error("Error when attempting test " . $testProperty->getDescription() . ". The following exception occured " . $e);
        }
        $this->logger->info('END test C1');
        $this->logger->info('');
    }

    protected function documentListOverview()
    {
        $this->documentListHandler = new DocumentListHandler($this->directory);

        $this->dokumenterDirectory = join(DIRECTORY_SEPARATOR, array(
            $this->directory,
            'dokumenter'
        ));
        $this->logger->info('  Building up a list of files in dokumenter folder. Dir is (' . $this->dokumenterDirectory . ') ');

        $dokumenterFolderHandler = new DokumenterFolderHandler($this->dokumenterDirectory, Constants::FILE_PROCESSING_MAX_RECURSIVE_DEPTH, $this->documentListHandler);
        $dokumenterFolderHandler->process();
        $numberOfDocumentsInDirectory = $dokumenterFolderHandler->getNumberOfFiles();
        $numberOfUniqueDocumentsInDirectory = $dokumenterFolderHandler->getNumberOfUniqueFiles();

        $this->logger->info('   Number of documents found ' . $numberOfDocumentsInDirectory);

        if ($numberOfUniqueDocumentsInDirectory != $numberOfDocumentsInDirectory) {
            $this->logger->info('   Number of unique documents in document directory is ' . $numberOfUniqueDocumentsInDirectory);
            $this->logger->info('   Number of documents in document directory is ' . $numberOfDocumentsInDirectory);
            $this->logger->info('   Potential duplicate of documents that may cause trouble. Warning!');
        }
        return $numberOfDocumentsInDirectory;
    }

    /**
     * This function is provided as a little cheat.
     * If you need to run single test e.g A5, where
     * you need to parse Arkivstruktur to get a value, then you can just call this to parse it.
     */
    protected function parseArkivstruktur()
    {
        $this->parseFile(new ArkivstrukturParser(), $this->arkivstrukturFilename);
    }

    /**
     *
     * @param unknown $parserElementController
     * @param unknown $filename
     */
    protected function parseFile($parserElementController, $filename)
    {
        $parser = xml_parser_create('UTF-8');

        xml_set_object($parser, $parserElementController);
        // XML_OPTION_CASE_FOLDING Do not fold element names to upper case
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, false);

        // set function names to handle start / end tags of elements and to parse content between tags
        xml_set_element_handler($parser, "startElement", "endElement");
        xml_set_character_data_handler($parser, "cdata");

        $xmlFile = fopen(join(DIRECTORY_SEPARATOR, array(
            $this->directory,
            $filename
        )), 'r');

        while ($data = fread($xmlFile, 4096)) {
            xml_parse($parser, $data, feof($xmlFile));
            flush();
        }
        fclose($xmlFile);
        xml_parser_free($parser);

        if ($this->statistics === null && get_parent_class($parserElementController) === 'ArkivstrukturParser') {
            $this->statistics = $parserElementController->getStatistics();
        }
    }

    private function setAllTestsRunnable()
    {
        $this->testsToRun[Constants::TEST_TYPE_A0] = true;
        $this->testsToRun[Constants::TEST_TYPE_A1] = true;
        $this->testsToRun[Constants::TEST_TYPE_A2] = true;
        $this->testsToRun[Constants::TEST_TYPE_A3] = true;
        $this->testsToRun[Constants::TEST_TYPE_A4] = true;
        $this->testsToRun[Constants::TEST_TYPE_A5] = true;
        $this->testsToRun[Constants::TEST_TYPE_A6] = true;
        $this->testsToRun[Constants::TEST_TYPE_A7] = true;
        $this->testsToRun[Constants::TEST_TYPE_A8] = true;
        $this->testsToRun[Constants::TEST_TYPE_A9] = true;
        $this->testsToRun[Constants::TEST_TYPE_A10] = true;
        $this->testsToRun[Constants::TEST_TYPE_C1] = true;
        $this->testsToRun[Constants::TEST_TYPE_C2] = true;
        $this->testsToRun[Constants::TEST_TYPE_C3] = true;
        $this->testsToRun[Constants::TEST_TYPE_C4] = true;
        $this->testsToRun[Constants::TEST_TYPE_C5] = true;
        $this->testsToRun[Constants::TEST_TYPE_C6] = true;
        $this->testsToRun[Constants::TEST_TYPE_C7] = true;
        $this->testsToRun[Constants::TEST_TYPE_C8] = true;
        $this->testsToRun[Constants::TEST_TYPE_C9] = true;
        $this->testsToRun[Constants::TEST_TYPE_C10] = true;
        $this->testsToRun[Constants::TEST_TYPE_C11] = true;
        $this->testsToRun[Constants::TEST_TYPE_C12] = true;
        $this->testsToRun[Constants::TEST_TYPE_C13] = true;
        $this->testsToRun[Constants::TEST_TYPE_C14] = true;
        $this->testsToRun[Constants::TEST_TYPE_C15] = true;
        $this->testsToRun[Constants::TEST_TYPE_C16] = true;
    }
}

?>
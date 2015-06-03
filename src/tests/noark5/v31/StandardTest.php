<?php
require_once ('tests/xml/XMLTestValidation.php');
require_once ('tests/xml/XMLTestWellFormed.php');
require_once ('tests/file/DocumentDirectoryTest.php');
require_once ('tests/noark5/v31/CheckNumberObjectsArkivutrekk.php');
require_once ('handler/DokumenterFolderHandler.php');
require_once ('handler/DocumentListHandler.php');
require_once ('testProperties/XMLWellFormedTestProperty.php');
require_once ('testProperties/FileChecksumTestProperty.php');
require_once ('testProperties/DocumentDirectoryTestProperty.php');
require_once ('tests/Test.php');


class StandardTest extends Test
{

    protected $directory;

    protected $standardExtractionContents;

    protected $arkivUtrekk;

    protected $arkivstrukturFilename;

    protected $testResultsHandler;

    protected $numberOfDocumentsProcessed;

    protected $numberOfFileProcessed;

    protected $numberOfDocumentsInDirectory = -1;

    protected $numberOfRegistryEntryProcessed;

    protected $infoFileHandler;

    protected $dokumenterDirectory;

    public function __construct($testName, $directory, $runDirectory, $noark5StructureFile, $testResultsHandler, $arkivUtrekk, $infoFileHandler, $testProperty)
    {
        parent::__construct($testName, $testProperty);
        $this->directory = $directory;
        $this->testResultsHandler = $testResultsHandler;
        $this->arkivUtrekk = $arkivUtrekk;
        $this->infoFileHandler = $infoFileHandler;

        if (is_null($noark5StructureFile) == false) {
            $this->standardExtractionContents = simplexml_load_file($noark5StructureFile);
        } else {
            $this->standardExtractionContents = simplexml_load_file($runDirectory . DIRECTORY_SEPARATOR . Constants::LOCATION_OF_NOARK5_V31_STRUCTURE_FILE);
        }

        $this->arkivstrukturFilename = 'arkivstruktur.xml';
        $this->numberOfDocumentsProcessed = - 1;
    }

    public function runTest()
    {
        $this->preTestProcessing();
        $this->test1();
        $this->test2();
        $this->test3();
        $this->test4();
        $this->test5();
        $this->test6();
        $this->test9();
    }

    public function preTestProcessing()
    {
        $this->documentListOverview();
    }

    protected function documentListOverview()
    {
        $this->documentListHandler = new DocumentListHandler($this->directory);

        $this->dokumenterDirectory = join(DIRECTORY_SEPARATOR, array(
            $this->directory,
            'dokumenter'
        ));
        print 'Building up a list of files in dokumenter folder. Dir is (' . $this->dokumenterDirectory . ') ' . PHP_EOL;

        $dokumenterFolderHandler = new DokumenterFolderHandler($this->dokumenterDirectory, Constants::FILE_PROCESSING_MAX_RECURSIVE_DEPTH, $this->documentListHandler);
        $dokumenterFolderHandler->process();
        $this->numberOfDocumentsInDirectory = $dokumenterFolderHandler->getNumberOfFiles();
        $numberOfUniqueDocumentsInDirectory = $dokumenterFolderHandler->getNumberOfUniqueFiles();

        print 'Number of documents found ' . $this->numberOfDocumentsInDirectory . PHP_EOL;

        if ($numberOfUniqueDocumentsInDirectory != $this->numberOfDocumentsInDirectory) {
            print 'Number of unique documents in document directory is ' . $numberOfUniqueDocumentsInDirectory;
            print '. Number of documents in document directory is ' . $this->numberOfDocumentsInDirectory;
            print 'Potential duplicate of documents that may cause trouble. Warning!';
        }

    }

    /*
     * test1 checks if all the files that we expect in the directory are present and readable
     *
     */
    public function test1()
    {}

    /*
     * test2 checks if all the files in the directory are well-formed and valid
     *
     */
    public function test2()
    {
        print 'Testing all XML/XSD files for well-formedness ' . PHP_EOL;
        foreach ($this->standardExtractionContents->directoryContents->file as $file) {
            $xmlValidationTestProperty = new XMLWellFormedTestProperty(Constants::TEST_XMLTEST_VALIDATION_WELLFORMED);
            $xmlTestWellFormed = new XMLTestWellFormed(Constants::TEST_XMLTEST_VALIDATION_WELLFORMED, $this->directory, $file->filename, $xmlValidationTestProperty);
            $xmlTestWellFormed->runTest();
            $this->testResultsHandler->addResult($xmlValidationTestProperty, Constants::TEST_TYPE_A1);
            print $xmlValidationTestProperty . PHP_EOL;
            $xmlTestWellFormed = null;
        }

        print 'Testing all XML files for validity ' . PHP_EOL;
        foreach ($this->standardExtractionContents->filesToValidate->file as $file) {

            $testProperty = new XMLValidationTestProperty(Constants::TEST_XMLTEST_VALIDATION_VALID);
            $xmlTestValidation = new XMLTestValidation(Constants::TEST_XMLTEST_VALIDATION_VALID, $this->directory, $file->filename, $file->validatedBy, $testProperty);
            $xmlTestValidation->runTest();
            $this->testResultsHandler->addResult($testProperty, Constants::TEST_TYPE_A1);
            print $testProperty . PHP_EOL;
            $xmlTestValidation = null;
        }
    }

    /*
     * test3 process arkivstruktur.xml and check following :
     * - all checksums are correct
     * - all documents are valid (Note this is not implemented yet! Coded for but no plug to actual validation)
     *
     * Note this test also finds the number of mappe and registryEntry that are handled
     */
    public function test3()
    {
        print 'Testing documents in arkivstruktur.xml ' . PHP_EOL;

        $akivstrukturParser = new ArkivstrukturParser($this->directory, $this->documentListHandler, $this->testResultsHandler, false);
        $parser = xml_parser_create('UTF-8');

        xml_set_object($parser, $akivstrukturParser);
        // XML_OPTION_CASE_FOLDING Do not fold element names to upper case
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, false);

        // set function names to handle start / end tags of elements and to parse content between tags
        xml_set_element_handler($parser, "startElement", "endElement");
        xml_set_character_data_handler($parser, "cdata");

        $arkivstrukturFile = fopen(join(DIRECTORY_SEPARATOR, array(
            $this->directory,
            $this->arkivstrukturFilename
        )), 'r');

        while ($data = fread($arkivstrukturFile, 4096)) {
            xml_parse($parser, $data, feof($arkivstrukturFile));
            flush();
        }
        fclose($arkivstrukturFile);
        xml_parser_free($parser);

        $this->numberOfDocumentsProcessed = $akivstrukturParser->getNumberOfDocumentsProcessed();
        $this->numberOfFileProcessed = $akivstrukturParser->getNumberOfFileProcessed();
        $this->numberOfRegistryEntryProcessed = $akivstrukturParser->getNumberOfRegistryEntryProcessed();

        $arkivstrukturData = $this->arkivUtrekk->getArkivstruktur();
        $numberOfFileReportedInArkivUttrekk = $arkivstrukturData->getNumberMappe();
        $numberOfRegistryEntryReportedInArkivUttrekk = $arkivstrukturData->getNumberRegistrering();
        $numberOfDocumentsReportedInArkivUttrekk = $this->arkivUtrekk->getExtractionInfo()->getAntallDokumentfiler();


        $testProperty = new TestProperty(Constants::TEST_COUNT_MAPPE);
        $documentDirectoryTest = new CheckNumberObjectsArkivutrekk(Constants::TEST_COUNT_MAPPE, $numberOfFileReportedInArkivUttrekk, $this->numberOfFileProcessed, Constants::NAME_ARKIVSTRUKTUR_XML, 'mappe', $testProperty);
        $documentDirectoryTest->runTest();
        $this->testResultsHandler->addResult($testProperty, Constants::TEST_TYPE_A7);
        print $testProperty . PHP_EOL;

        $testProperty = new TestProperty(Constants::TEST_COUNT_REGISTRERING);
        $documentDirectoryTest = new CheckNumberObjectsArkivutrekk(Constants::TEST_COUNT_REGISTRERING, $numberOfRegistryEntryReportedInArkivUttrekk, $this->numberOfRegistryEntryProcessed, Constants::NAME_ARKIVSTRUKTUR_XML, 'registrering', $testProperty);
        $documentDirectoryTest->runTest();
        $this->testResultsHandler->addResult($testProperty, Constants::TEST_TYPE_A8);
        print $testProperty . PHP_EOL;

        $testProperty = new TestProperty(Constants::TEST_COUNT_DOCUMENTS_ACTUAL);
        $documentDirectoryTest = new CheckNumberObjectsArkivutrekk(Constants::TEST_COUNT_DOCUMENTS_ACTUAL, $numberOfDocumentsReportedInArkivUttrekk, $this->numberOfDocumentsProcessed, Constants::NAME_ARKIVSTRUKTUR_XML, 'dokument', $testProperty);
        $documentDirectoryTest->runTest();
        $this->testResultsHandler->addResult($testProperty, Constants::TEST_TYPE_A5);
        print $testProperty . PHP_EOL;
    }

    /*
     * test4 checks documents folder and reports missing or extra documents
     *
     * Note: test3 must be run first!
     */
    public function test4()
    {
        if ($this->numberOfDocumentsProcessed == - 1) {
            print 'Cannot run a test on documents unless test (Document test) has been run' . PHP_EOL;
            return;
        }

        print 'Testing for known problems in documents directory cross-referenced with arkivstruktur.xml' . PHP_EOL;

        $testProperty = new DocumentDirectoryTestProperty(Constants::TEST_DOCUMENT_DIRECTORY);
        $documentDirectoryTest = new DocumentDirectoryTest(Constants::TEST_DOCUMENT_DIRECTORY, $this->dokumenterDirectory, $this->documentListHandler, $testProperty);
        $documentDirectoryTest->runTest();
        $this->testResultsHandler->addResult($testProperty, Constants::TEST_TYPE_A7);
        print $testProperty . PHP_EOL;
    }

    /*
     * test5
     *
     * Note: test3 must be run first!
     */
    public function test5()
    {}


    /*
     * test6: Test if number of documents specified in arkivuttrell.xml is correct with count of
     *        documents in the dokumenter folder
     *
     * Note: $this->preTestProcessing must be run first!
     */
    public function test6()
    {

        $testProperty = new TestProperty(Constants::TEST_COUNT_DOCUMENTS);
        $extractionInfo = $this->arkivUtrekk->getExtractionInfo();

        if ($this->numberOfDocumentsInDirectory != -1 && ($this->numberOfDocumentsInDirectory == $extractionInfo->getAntallDokumentfiler())) {
            $testProperty->addTestResult(true);
            $testProperty->addTestResultDescription('Number of documents identified in ' . Constants::NAME_ARKIVUTTREKK_XML . 'is correct. Number identified is ' . $extractionInfo->getAntallDokumentfiler());
        }
        else {
            $this->testProperty->addTestResult(false);
            $this->testProperty->addTestResultDescription('Number of documents identified in ' . Constants::NAME_ARKIVUTTREKK_XML . 'is in correct. Number identified is '
                            . $extractionInfo->getAntallDokumentfiler() . ' while number of documents found is ' .  $this->numberOfDocumentsInDirectory);
        }

        $this->testResultsHandler->addResult($testProperty, Constants::TEST_TYPE_A6);
        print $testProperty . PHP_EOL;
    }


    /*
     * test9: Check checksum value of arkivuttrekk.xml
     *
     * Note: IfoFileHandler must have be run first and checksumValue extracted
     */
    public function test9()
    {
        $checksumValue = $this->infoFileHandler->getChecksumInfo()->getChecksumValue();
        $checksumAlgorithm = $this->infoFileHandler->getChecksumInfo()->getChecksumAlgorithm();

        $testProperty = new FileChecksumTestProperty(Constants::TEST_CHECKSUM);
        $checksumTest = new ChecksumTest(Constants::TEST_CHECKSUM, Constants::NAME_ARKIVUTTREKK_XML, $this->directory, $checksumAlgorithm, $checksumValue, $testProperty);
        $checksumTest->runTest();

        $this->testResultsHandler->addResult($testProperty, Constants::TEST_TYPE_A9);

        print $testProperty . PHP_EOL;
        $checksumTest = null;
    }
}

?>
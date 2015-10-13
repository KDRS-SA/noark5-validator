<?php
require_once ('handler/ArkivstrukturParser.php');

class ArkivstrukturDocumentChecksumTest extends ArkivstrukturParser
{

    protected $documentListHandler;
    protected $numberOfFileProcessed;
    protected $numberOfDocumentsProcessed;

    public function __construct($directory, $documentListHandler)
    {
        parent::__construct($directory, null, null);
        $this->documentListHandler = $documentListHandler;
        $this->directory = $directory;

    }

    // Override
    public function postProcessDocumentObject() {
        $this->documentFileTestChecksum();
        $this->checkFileInDocumentList();
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
        $checksumTest = null;
    }

    protected function documentFileTestFormatValidity()
    {
    }

    public function result()
    {
    }

    public function getNumberOfFileProcessed() {
        return $this->numberOfFileProcessed;
    }

    public function getNumberOfDocumentsProcessed()
    {
        return $this->numberOfDocumentsProcessed;
    }


}

?>
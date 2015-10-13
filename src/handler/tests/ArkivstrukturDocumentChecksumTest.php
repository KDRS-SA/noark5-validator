<?php
require_once ('handler/ArkivstrukturParser.php');

class ArkivstrukturDocumentChecksumTest extends ArkivstrukturParser
{
    /**
     *
     * @var boolean $errorsEncountered : Whether or not errors were encountered during processing
     */
    protected $errorsEncountered = false;

    /**
     *
     * @var boolean $numberErrorsEncountered : The numer of errors that were encountered during processing
     */
    protected $numberErrorsEncountered = 0;

    public function __construct($directory)
    {
        parent::__construct();
        $this->directory = $directory;
        $this->logger->trace('Creating an instance of [' . get_class($this) . '] with the following values');
        $this->logger->trace('directory [' . $this->directory . ']');
    }

    // Override
    public function postProcessDocumentObject() {
        $currentDocumentObject = end($this->stack);
        $this->logger->trace('In ' . __METHOD__ . ' current object is ' . $currentDocumentObject);
        $testProperty = new TestProperty(Constants::TEST_CHECKSUM);
        $checksumTest = new ChecksumTest(Constants::TEST_CHECKSUM, $currentDocumentObject->getReferenceDocumentFile(),
                                         $this->directory, $currentDocumentObject->getChecksumAlgorithm(),
                                         $currentDocumentObject->getChecksum(), $testProperty);

        $testResult = $checksumTest->runTest();

        // The results of this test are only displayed in the logfile
        if ($testResult == false) {
            $this->errorsEncountered = true;
            $this->numberErrorsEncountered++;
        }
        $checksumTest = null;
        $testProperty = null;
    }

    public function getErrorsEncountered()
    {
        return $this->errorsEncountered;
    }

    public function getNumberErrorsEncountered() {
        return $this->numberErrorsEncountered;
    }
}

?>
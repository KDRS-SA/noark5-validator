<?php
require_once ('tests/Test.php');

/*
 * Carry out a checksum test of a named file. The expected checksumvalue is passed in.
 * We don't use $testProperty in this test. This test will typically be run many thousands
 * of time so we simply log the result of the test. Failing the test results in a log with
 * error, while passing the test will result in log with info
 *
 */

class ChecksumTest extends Test {

    protected $fileName;
    protected $directory;
    protected $checksumAlgorithm;
    protected $checksumValue;

    function __construct($testName, $fileName, $directory,
                            $checksumAlgorithm, $checksumValue, $testProperty) {
        parent::__construct($testName, $testProperty);
        $this->fileName = $fileName;
        $this->directory = $directory;
        $this->checksumAlgorithm = $checksumAlgorithm;
        $this->checksumValue = $checksumValue;
        $this->logger->trace('Creating an instance of [' . get_class($this) . '] with the following values');
        $this->logger->trace('fileName [' . $this->fileName . ']');
        $this->logger->trace('directory [' . $this->directory . ']');
        $this->logger->trace('checksumAlgorithm [' . $this->checksumAlgorithm . ']');
        $this->logger->trace('checksumValue [' .$this->checksumValue . ']');

    }

    public function runTest () {
        $testResult = false;
        $this->logger->trace('Testing ' . join(DIRECTORY_SEPARATOR, array($this->directory, $this->fileName)));
        $filename = $this->fileName;

        if ($this->directory != null) {
            $filename = join(DIRECTORY_SEPARATOR, array($this->directory, $this->fileName));
        }

        if (file_exists($filename) && is_readable($filename)) {
            $hashOfFile = hash_file(strtolower(str_replace('-', '', $this->checksumAlgorithm)), $filename);

            $this->logger->trace('  Checksum algorithm specified is '. $this->checksumAlgorithm);
            $this->logger->trace('  The checksum value for the file ('. $this->fileName . ') is [' . $hashOfFile . ']');
            $testResult = true;
            if (strcasecmp($this->checksumValue , $hashOfFile) == 0) {
                $this->logger->info('  The checksum for the file ('. $this->fileName . ') is correct');
                $this->logger->trace('  The checksum value for the file ('. $this->fileName . ') is [' . $hashOfFile . ']');
            }
            else {
                $this->logger->warn('  The file ('. $this->fileName . ') checksum is not correct. ' .
                                                                'Original checksum is (' .
                                                                     $this->checksumValue .
                                                                        '). Computed checksum is ('
                                                                          . $hashOfFile .')');
            }
        }
        else {
            $this->logger->warn('  The File [' . $this->fileName . '] is not present and therefore the checksum test fails');
        }
        $this->testProperty->setTestResult($testResult);
        return $testResult;
    }
}

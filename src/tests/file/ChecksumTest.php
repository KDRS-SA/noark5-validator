<?php
require_once ('tests/Test.php');

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
    }

    public function runTest () {

        print 'Testing ' . join(DIRECTORY_SEPARATOR, array($this->directory, $this->fileName));
        $filename = $this->fileName;

        if ($this->directory != null) {
            $filename = join(DIRECTORY_SEPARATOR, array($this->directory, $this->fileName));
        }

        if (file_exists($filename) && is_readable($filename)) {
            $hashOfFile =  hash_file(strtolower(str_replace('-', '', $this->checksumAlgorithm)), $filename);

            if (strcasecmp($this->checksumValue , $hashOfFile) == 0) {
                $this->testProperty->addTestResult(true);
                $this->testProperty->addTestResultDescription('The file ('. $this->fileName . ') checksum is correct');
            }
            else {
                $this->testProperty->addTestResult(false);
                $this->testProperty->addTestResultDescription('The file ('. $this->fileName . ') checksum is not correct. ' .
                                                                'Original checksum is (' .
                                                                     $this->checksumValue .
                                                                        '). Computed checksum is ('
                                                                          . $hashOfFile .')');

                $this->testProperty->addTestResultReportDescription('The file ('. $this->fileName . ') checksum is not correct. ' .
                                                                'Original checksum is (' .
                                                                     $this->checksumValue .
                                                                        '). Computed checksum is ('
                                                                          . $hashOfFile .')');
            }
        }
        else {
            $this->testProperty->addTestResult(false);
            $this->testProperty->addTestResultDescription('The file ('. $this->fileName . ') can not be found in the documents folder');
            $this->testProperty->addTestResultReportDescription('Filen ' . $this->fileName . ' finnes ikke i dokumenter og feiler derfor sjekksum test');
        }
    }
}

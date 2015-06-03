<?php
require_once ('tests/Test.php');

class FileExistsTest extends Test {

    protected $fileName;
    protected $directory;

    function __construct($testName, $fileName, $directory, $testProperty) {
        parent::__construct($testName, $testProperty);
        $this->fileName = $fileName;
        $this->directory = $directory;
    }

    public function runTest () {

        if (is_readable (join(DIRECTORY_SEPARATOR, array($this->directory, $this->fileName))) == true) {
            $this->testProperty->addTestResult(true);
            $this->testProperty->addTestResultDescription('The file '. $this->fileName . ' exists and is readable');
        }
        elseif (file_exists(join(DIRECTORY_SEPARATOR, array($this->directory, $this->fileName))) == true) {
            $this->testProperty->addTestResult(false);
            $this->testProperty->addTestResultDescription('The file '. $this->fileName . ' exists but is not readable');
        }
        else {
            $this->testProperty->addTestResult(false);
            $this->testProperty->addTestResultDescription('The file '. $this->fileName . ' does not exist');
        }
    }
}

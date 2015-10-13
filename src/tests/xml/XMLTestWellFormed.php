<?php

require_once 'XMLTest.php';
require_once 'utils/Constants.php';

/*
 * This class can be used to validate en XML file
 * against an XSD. Later should include ability for
 * DTD.
 */

class XMLTestWellFormed extends XMLTest {

	function __construct($testName, $directory, $fileName, $testProperty) {
		parent::__construct($testName, $directory, $fileName, $testProperty);
		$this->fileName = $fileName;
	}

	public function runTest () {
        $testResult = true;

	    $this->logger->trace('Entering ' . __METHOD__);
	    $this->logger->info('  Testing the file [' . $this->fileName . '] for well-formedness');

		$xmlParser = xml_parser_create();

		$file = join(DIRECTORY_SEPARATOR, array($this->directory, $this->fileName));
		if (!($fp = fopen($file, "r"))) {
		    $this->logger->error('  Could not open XML-file for input [' . $file . ']');
		    throw new Exception ('Could not open XML input : ' . $file);
		}

		$errorFound = false;
		while (($data = fread($fp, Constants::XML_PARSE_BUFFER_SIZE)) && $errorFound == false) {
		    if (!xml_parse($xmlParser, $data, feof($fp))) {
				$testResult = false;
				$errorFound = true;
				$this->logger->error(xml_get_current_line_number($xmlParser) . ' ' .
				                        xml_error_string(xml_get_error_code($xmlParser)));
	        }
		}

		xml_parser_free($xmlParser);
		if ($testResult == true) {
			$this->testProperty->addTestResult(true);
		    $this->logger->info(' RESULT The file ' . $this->fileName . ' is well-formed.');
			$this->testProperty->addTestResultDescription('The file ' . $this->fileName . ' is well-formed.');
			$this->testProperty->addTestResultReportDescription('Filen ' . $this->fileName . ' er korrektstrukturert.');
		}
		else {
			$this->testProperty->addTestResult(false);
			$this->testProperty->addTestResultDescription('The file ' . $this->fileName . ' is not well-formed.');
			$this->logger->error(' RESULT The file ' . $this->fileName . ' is not well-formed.');
			$this->testProperty->addTestResultReportDescription('Filen ' . $this->fileName . ' er ikke korrektstrukturert.');
		}
	}
}

?>
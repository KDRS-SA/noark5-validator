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
		// Assumption is that test will be true, need to prove false
		$this->testResult = true;
	}

	public function runTest () {

		$xmlParser = xml_parser_create();

		if (!($fp = fopen(join(DIRECTORY_SEPARATOR, array($this->directory, $this->fileName)), "r"))) {
		    die("could not open XML input");
		}

		$errorFound = false;

		while (($data = fread($fp, Constants::XML_PARSE_BUFFER_SIZE)) && $errorFound == false) {

		    if (!xml_parse($xmlParser, $data, feof($fp))) {
				$this->testResult = false;
				$errorFound = true;
		    	echo 'Error ' . xml_error_string(xml_get_error_code($xmlParser)) . PHP_EOL;
		    	echo ' Line number' . xml_get_current_line_number($xmlParser) . PHP_EOL;

//		        $errorInformation[] = new WellFormedErrorInformation(
	//	        						xml_error_string(xml_get_error_code($xml_parser)),
		//        						xml_get_current_line_number($xml_parser));
		        }
		}


		xml_parser_free($xmlParser);

		if ($this->testResult == true) {
			$this->testProperty->addTestResult(true);
			$this->testProperty->addTestResultDescription('The file ' . $this->fileName . ' is well-formed.');
			$this->testProperty->addTestResultReportDescription('Filen ' . $this->fileName . ' er korrektstrukturert.');
		}
		else {
			$this->testProperty->addTestResult(false);
			$this->testProperty->addTestResultDescription('The file ' . $this->fileName . ' is not well-formed.');
			$this->testProperty->addTestResultReportDescription('Filen ' . $this->fileName . ' er ikke korrektstrukturert.');
		}

	}
}

?>
<?php

require_once 'XMLTest.php';
require_once 'utils/Constants.php';
require_once 'testProperties/XMLValidationTestProperty.php';

/*
 * This class can be used to validate en XML file
 * against an XSD. Later should include ability for
 * DTD.
 *
 */

class XMLTestValidation extends XMLTest {

	protected $xsdFilename;

	function __construct($testName, $directory, $fileName, $xsdFilename, $testProperty) {
		parent::__construct($testName, $directory, $fileName, $testProperty);
		$this->fileName = $fileName;
		$this->xsdFilename = $xsdFilename;
		$description = 'Testing validity of '. $this->fileName  . ' against ' . $this->xsdFilename;
		$this->testProperty->addDescription($description);
	}


	/*
	 * In this test, we associate an XSD file with the XML file and start parsing
	 * the file. The result of this parsing is a validation of the XML file. Normally
	 * the entire file has to be parsed before the result is known.
	 *
	 * For large files, it is a waste of time to wait until the entire file has been
	 * processed and as such we
	 *
	 * NOTE:
	 * If XML_PROCESSESING_CHECK_ERROR_COUNT = 1000 and XML_PARSE_BUFFER_SIZE = 4096
  	 * then a check for validation error will occur every 4 MB. Either way there
	 * is minimal effect on proccessing.
	 */

	public function runTest () {

		libxml_use_internal_errors(true);

		$xml = XMLReader::open(join(DIRECTORY_SEPARATOR, array($this->directory, $this->fileName)));
		$xml->setSchema(join(DIRECTORY_SEPARATOR, array($this->directory, $this->xsdFilename)));

		$this->logger->trace(__METHOD__);
		$this->logger->info('  XML file to test validity is ' . $this->fileName . 'using XSD file ' . $this->xsdFilename);

		// You have to parse the XML-file if you want it to be validated
		$currentReadCount = 1;
		$validationFailed = false;

		while ($xml->read() && $validationFailed == false) {
			// I want to break as soon as file is shown not to be valid
			// We could allow it to collect a few messages, but I think it's best
			// to do a manual check once we have discovered the file is not
			// correct. Speed is really what we want here!
			if ($currentReadCount++ % Constants::XML_PROCESSESING_CHECK_ERROR_COUNT == 0) {
				if (count(libxml_get_errors ()) > 0) {
					$validationFailed = true;
				}
			}
		}

		if (count(libxml_get_errors()) == 0) {

			$this->testProperty->addTestResult(true);
			$this->logger->info(' RESULT Validation of ['. $this->fileName . '] against [' . $this->xsdFilename . '] succeeded');
			$this->testProperty->addTestResultDescription('Validation of ['. $this->fileName . '] against [' . $this->xsdFilename . '] succeeded');
			$this->testProperty->addTestResultReportDescription('Filen ' . $this->fileName . ' validerer mot filen' . $this->xsdFilename);
		} else {

			$this->testProperty->addTestResult(false);
			$this->logger->error(' RESULT Validation of ['. $this->fileName . '] against [' . $this->xsdFilename . '] failed' );
			$this->testProperty->addTestResultDescription('Validation of ['. $this->fileName . '] against [' . $this->xsdFilename . '] failed' );
			$this->testProperty->addTestResultReportDescription('Filen ' . $this->fileName . ' validerer ikke mot filen' . $this->xsdFilename);
		}
		libxml_clear_errors();
	}
}

?>
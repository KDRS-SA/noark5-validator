<?php
require_once 'testProperties/TestProperty.php';

class XMLValidationTestProperty extends TestProperty {

	protected $validationResults = array();

	function __construct($testDescription) {
		parent::__construct($testDescription);
	}

	public function addErrorDetails() {

	}

	public function getDescriptionReport () {
	    return $this->testResultReportDescription;
	}
}

?>
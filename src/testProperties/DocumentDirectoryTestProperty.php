<?php
require_once 'testProperties/TestProperty.php';

class DocumentDirectoryTestProperty extends TestProperty {

 	function __construct($testDescription) {
	    parent::__construct($testDescription);

	}

	public function getDescriptionReport () {
	    return $this->testResultDescription;
	}
}

?>
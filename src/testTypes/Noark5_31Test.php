<?php
require_once '../resources/src/testTypes/Noark5Test.php';
require_once ('utils/Constants.php');

/*
 * Here we do the actual linking to Noark 5 3.1 
 * resources
 */

class Noark5_31Test extends Noark5Test {
	
	protected $fileName;
	// $testName Constants::TEST_XMLTEST
	function __construct($testName, $fileName) {
		parent::__construct($testName);
		print "In XMLTest constructor " . PHP_EOL;
		$this->fileName = $fileName;
	}	
}

?>
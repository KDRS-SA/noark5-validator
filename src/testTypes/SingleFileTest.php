<?php
require_once '../resources/src/testTypes/TestType.php';
require_once ('utils/Constants.php');

/*
 * This class contains the code to handle a basic Noark 5
 * extraction. All versions of Noark 5 will subclass this 
 * class as long as there are no major changes to an 
 * extraction 
 */

class Noark5Test extends TestType {
	
	protected $directory;
	
	function __construct($directory) {
		parent::__construct($testName);
		print "In Noark5Test constructor " . PHP_EOL;
		$this->directory = $directory;
	}

}

?>
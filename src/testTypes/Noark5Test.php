<?php
require_once '../resources/src/testTypes/TestType.php';
require_once ('utils/Constants.php');


class Noark5Test extends TestType {

	protected $directory;

	function __construct($directory) {
		parent::__construct($testName);
		print "In Noark5Test constructor " . PHP_EOL;
		$this->directory = $directory;
	}


	// One of the first things to do is check that the directory
	// actually looks like a Noark 5 directory. The follwing files
	// must be located inte the directory

	public function checkDirectoryContents() {

// 		file_exists ( string $filename )

	}

}

?>
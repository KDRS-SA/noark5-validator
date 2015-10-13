<?php

require_once ('tests/Test.php');
require_once ('utils/Constants.php');

/*
 *
 * This class currently does not implement anything.
 * It simply acts as a base class for XML tests.
 * Later we might find a use for it. If not, then
 * the subclasses should simply extend Test
 */

class XMLTest extends Test {

	protected $fileName;
	protected $directory;

	// $testName Constants::TEST_XMLTEST
	function __construct($testName, $directory, $fileName, $testProperty) {
		parent::__construct($testName, $testProperty);
		$this->fileName = $fileName;
		$this->directory = $directory;
	}

}

?>
<?php

class Test {

	protected $testProperty;
	protected $testName;
	/**
	 *
	 * @var $logger: The Log4Php logger object
	 */
	protected $logger;

	function __construct($testName, $testProperty) {
		$this->$testName = $testName;
		$this->testProperty = $testProperty;
		$this->logger = Logger::getLogger( $GLOBALS['toolboxLogger']);
	}

	public function runTest () {

	}

	public function getTestResult () {
		return $this->testProperty->__toString();
	}

	public function __toString()
    {
        return "toString in Test Class";
    }
}

?>
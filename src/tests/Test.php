<?php

class Test {

	protected $testProperty;
	protected $testName;

	function __construct($testName, $testProperty) {
		$this->$testName = $testName;
		$this->testProperty = $testProperty;
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
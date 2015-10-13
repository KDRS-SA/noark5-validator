<?php

class TestProperty {

	protected $testResult = false;
	protected $testResultDescription = '';
	protected $testResultReportDescription = '';
	protected $testDescription = '';

	function __construct($testDescription) {
	    $this->testDescription = $testDescription;
	}

	public function addDescription($testDescription) {
		$this->testDescription = $testDescription;
	}

	public function addTestResult($testResult) {
		$this->testResult= $testResult;
	}

	public function addTestResultDescription($testResultDescription) {
		$this->testResultDescription = $testResultDescription;
	}

	public function addTestResultReportDescription($testResultReportDescription) {
	    $this->testResultReportDescription = $testResultReportDescription;
	}
	// probably should be set by subclasses
	public function getDescriptionReport () {
	    $description = $this->testResultReportDescription . 'Resultat ';
	    if ($this->testResult == 1) {
	        $description .= '(positiv)';
	    }
	    else {
	        $description .= '(negativ) ';
	    }
	    return $description;
	}

	public function getDescription() {
	    return $this->testDescription;
	}

	public function __toString() {
        return $this->getDescription();
	}

    public function getTestResult()
    {
        return $this->testResult;
    }

    public function setTestResult($testResult)
    {
        $this->testResult = $testResult;
    }
}

?>
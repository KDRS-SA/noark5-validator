<?php

class TestResultsHandler
{
    protected $allTestResults;

    function __construct() {
        $this->allTestResults = array();
    }

    function addResult($testResult, $testType) {
        $this->allTestResults[$testType][] = $testResult;
    }

    function getResults($testType) {
        if (isset ($this->allTestResults[$testType]))
            return $this->allTestResults[$testType];
        return null;
    }

}

?>
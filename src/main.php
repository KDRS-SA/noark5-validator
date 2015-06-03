<?php

	require_once 'tests/noark5/v31/StandardTest.php';
    require_once 'tests/xml/XMLTestValidation.php';
	require_once 'tests/xml/XMLTestWellFormed.php';
	require_once 'testProperties/XMLWellFormedTestProperty.php';
	require_once 'handler/ArkivstrukturParser.php';
	require_once 'handler/TestResultsHandler.php';
	require_once 'handler/ArkivuttrekkHandler.php';
	require_once 'handler/InfoFileHandler.php';
	require_once 'reports/ReportBuilder.php';
	require_once "vendor/autoload.php";

	// start
	$options = getopt("d:t:v:i:");

	if (isset($options["d"]) == false) {
		print "Path to directory to test. Example usage -d/home/user/noark5 " . PHP_EOL;
		exit;
	}

	if (isset($options["t"]) == false) {
		print "Type test not specified. Example usage -tnoark5" . PHP_EOL;
		exit;
	}

	if (isset($options["v"]) == false) {
		print "Ingen version specified. Example usage -v31" . PHP_EOL;
		exit;
	}


	if (isset($options["i"]) == false && isset($options["t"]) == true && strcasecmp($options["t"], Constants::TEST_NOARK5) == 0) {
	    print "Ingen info.xml fil angitt for Noark 5 test. Example usage -tnoark5 -i/home/user/info.xml" . PHP_EOL;
	    exit;
	}

	ini_set('memory_limit', '1024M');

	$directory = $options["d"];
	$testType = $options["t"];
	$testTypeVersion = $options["v"];
	$infoFile = $options["i"];

	$infoFileHandler = new InfoFileHandler($infoFile);
	$infoFileHandler->processInfofile();

	$testResultsHandler = new TestResultsHandler();

	if (strcasecmp ($testType, Constants::TEST_TYPE_NOARK5) == 0 &&
			strcasecmp ($testTypeVersion, Constants::TEST_TYPE_NOARK5_VERSION_31) == 0) {
			    $arkivUttrekk = new ArkivUttrekk();
			    $arkivUttrekkFile = join(DIRECTORY_SEPARATOR, array($directory, Constants::NAME_ARKIVUTTREKK_XML));
			    $arkivUttrekkHandler = new ArkivuttrekkHandler($arkivUttrekkFile, $arkivUttrekk);
			    $arkivUttrekkHandler->processArkivuttrekk();
			    runNoark531Test($directory, $testResultsHandler, $arkivUttrekk, $infoFileHandler);
	}



	/*
	 *
	 * TODO!!!!!StandardTest9 is filaing. Need reference to arkivuttrekk.xml file
	 *  Need to find the cde that maps to the different files and reuse the link to the
	 *  file from there
	 *
	 *
	 *
	 */


	$reportBuilder = new ReportBuilder($testResultsHandler);
	$reportBuilder->createDocument();


	exit;

	function runNoark531Test($directory, $testResultsHandler, $arkivUttrekk, $infoFileHandler) {

	    $runDirectory = dirname( dirname(__FILE__) );
	    $testProperty = new TestProperty(Constants::TEST_STANDARD_NOARK5_TEST);
		$standardTest = new StandardTest(Constants::TEST_STANDARD_NOARK5_TEST, $directory, $runDirectory, null, $testResultsHandler, $arkivUttrekk, $infoFileHandler, $testProperty);
		$standardTest->runTest();
		print $testProperty . PHP_EOL;
		print 'Amount of memory used is ' . memory_get_usage (false) . PHP_EOL;
		print 'Amount of real memory used is ' . memory_get_usage (true) . PHP_EOL;
	}



?>
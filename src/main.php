<?php

require_once 'tests/noark5/v31/StandardTest.php';
require_once 'tests/xml/XMLTestValidation.php';
require_once 'tests/xml/XMLTestWellFormed.php';
require_once 'handler/ArkivstrukturParser.php';
require_once 'handler/TestResultsHandler.php';
require_once 'handler/ArkivuttrekkHandler.php';
require_once 'handler/InfoFileHandler.php';
require_once 'reports/ReportBuilder.php';
require_once "vendor/autoload.php";

	// start
	$options = getopt("d:t:v:i:l:s:");

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

	if (isset($options["l"]) == true && strcasecmp($options["t"], Constants::LOG_CONSOLE) == 0) {
	    $GLOBALS['toolboxLogger'] = 'log2Console';
	} elseif (isset($options["l"]) == true && strcasecmp($options["t"], Constants::LOG_FILE) == 0) {
	    $GLOBALS['toolboxLogger'] = 'log2file';
	}elseif (isset($options["l"]) == true && strcasecmp($options["t"], Constants::LOG_BOTH) == 0) {
	    $GLOBALS['toolboxLogger'] = 'log2fileAndConsole';
	}else {
	    $GLOBALS['toolboxLogger'] = 'log2file';
	}

	$GLOBALS['tabSpaces'] = '\t';
	/**
	 * Contains a given structure with filenames that this extraction should conform to
	 * and a list of files to validate
	*/
 	$noark5StructureFile = null;
	if (isset($options["s"]) == true) {
	    $noark5StructureFile = $options["s"];
	}

	// TODO: Defo have commandline option to allow user to use own logfileconfig
	Logger::configure('resources/logging/logconfig.xml');
	$logger = Logger::getLogger($GLOBALS['toolboxLogger']);

	$defaultMemoryLimit = '1024M';

	if (isset($options["m"]) == true) {
	    $defaultMemoryLimit = $options["m"];
	}

	$logger->info('Starting up ' . Constants::TOOL_NAME);
	$logger->info('Setting memory_limit to ' . $defaultMemoryLimit);

	ini_set('memory_limit', $defaultMemoryLimit);

	$directory = $options["d"];
	$testType = $options["t"];
	$testTypeVersion = $options["v"];
	$infoFile = $options["i"];

	$testResultsHandler = new TestResultsHandler();

	if (strcasecmp ($testType, Constants::TEST_TYPE_NOARK5) == 0 &&
			strcasecmp ($testTypeVersion, Constants::TEST_TYPE_NOARK5_VERSION_31) == 0) {

		    $logger->trace('Before starting the amount of memory used is ' . memory_get_usage (false));
		    $logger->trace('Before starting the amount of real memory used is ' . memory_get_usage (true));

		    $testProperty = new TestProperty(Constants::TEST_STANDARD_NOARK5_TEST);
		    $testProperty->setTestResult(true); // Assume test will be true until otherwise proven wrong
		    $standardTest = new StandardTest(Constants::TEST_STANDARD_NOARK5_TEST, $directory,
		                                      $testResultsHandler, $infoFile, $noark5StructureFile,
		                                      // null here is temporary, need a mechanism that allows
		                                      // for specification of individual tests to run
		                                      null, $testProperty);
		    $standardTest->runTest();

		    if ($testProperty->getTestResult() == true) {
		      $logger->info('Standardtest completed without any errors');
		    }
            else {
                $logger->info('Standardtest completed with errors. ' . $testProperty->getDescription());
            }

		    $logger->trace('After standardTest, the amount of memory used is ' . memory_get_usage (false));
		    $logger->trace('After standardTest, the amount of real memory used is ' . memory_get_usage (true));
	}

	$reportBuilder = new ReportBuilder($testResultsHandler);
	$reportBuilder->createDocument();

	exit;


?>
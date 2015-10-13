<?php

require_once ('tests/Test.php');
require_once ('handler/tests/AllIncomingRegistryEntrySignedOff.php');


class AllIncomingRegistryEntrySignedOffTest extends Test
{
    protected $arkivstrukturFilename;
    protected $directory;
    protected $testResultsHandler;

    public function __construct($testName, $directory, $arkivstrukturFilename, $testResultsHandler, $testProperty)
    {
        parent::__construct($testName, $testProperty);
        $this->directory = $directory;
        $this->testResultsHandler = $testResultsHandler;
        $this->arkivstrukturFilename = $arkivstrukturFilename;
    }

    public function runTest()
    {
        $arkivstrukturParser = new AllIncomingRegistryEntrySignedOff($this->directory, $this->testResultsHandler, false);
        $parser = xml_parser_create('UTF-8');

        xml_set_object($parser, $arkivstrukturParser);
        // XML_OPTION_CASE_FOLDING Do not fold element names to upper case
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, false);

        // set function names to handle start / end tags of elements and to parse content between tags
        xml_set_element_handler($parser, "startElement", "endElement");
        xml_set_character_data_handler($parser, "cdata");

        $arkivstrukturFile = fopen(join(DIRECTORY_SEPARATOR, array(
            $this->directory,
            $this->arkivstrukturFilename
        )), 'r');

        while ($data = fread($arkivstrukturFile, 4096)) {
            xml_parse($parser, $data, feof($arkivstrukturFile));
            flush();
        }
        fclose($arkivstrukturFile);
        xml_parser_free($parser);
    }
}

?>
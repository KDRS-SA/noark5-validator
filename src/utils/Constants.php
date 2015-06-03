<?php

class Constants
{
  const TEST_XMLTEST = "XML Test";
  const TEST_XMLTEST_VALIDATION_WELLFORMED = "XML Test well-formed";
  const TEST_XMLTEST_VALIDATION_VALID = "XML Test valid";
  const TEST_NOARK5 = "Noark5";
  const TEST_CHECKSUM = "Checksum";
  const TEST_DOCUMENT_DIRECTORY = "Document Directory";
  const TEST_COUNT_MAPPE = "Mappe Count";
  const TEST_COUNT_REGISTRERING = "Registrering Count";
  const TEST_COUNT_DOCUMENTS = "Document Count";
  const TEST_COUNT_DOCUMENTS_ACTUAL = "Document Count in directory";
  const TEST_ARKIVUTTREKK_INFO_XML_CHECKSUM = "Check checksum in info.xml against arkivuttrekk.xml";
  const STACK_ERROR = "XML Processing error in stack, misplaced object ";

  const TEST_STANDARD_NOARK5_TEST = "Standard Noark 5 test";

  const TEST_TYPE_NOARK5 = "noark5";
  const TEST_TYPE_NOARK5_VERSION_31 = "31";

  const XML_PROCESSESING_CHECK_ERROR_COUNT = 1000;
  const XML_PARSE_BUFFER_SIZE = 4096;

  const XSD_DATETIME_FORMAT = 'Y-m-d\TH:i:s';
  const XSD_DATE_FORMAT = 'Y-m-d';

  //does this cause problems on windows?????
  const LOCATION_OF_NOARK5_V31_STRUCTURE_FILE = 'resources/noark5/v3_1/Noark5_31_Structure.xml';
  const NAME_ARKIVUTTREKK_XML = 'arkivuttrekk.xml';
  const NAME_ARKIVSTRUKTUR_XML = 'arkivstruktur.xml';
  const NAME_ENDRINGSLOGG_XML = 'endringslogg.xml';

  const NAME_ARKIVUTTREKK = 'arkivuttrekk';
  const NAME_ARKIVSTRUKTUR = 'arkivstruktur';
  const NAME_ENDRINGSLOGG = 'endringslogg';
  const NAME_LOEPENDEJOURNAL = 'loependeJournal';
  const NAME_OFFENTLIGJOURNAL  = 'offentligJournal';

  const NAME_ARKIVUTTREKK_FILE  = 'file';
  const NAME_ARKIVUTTREKK_NAME  = 'name';
  const NAME_ARKIVUTTREKK_CHECKSUM  = 'checksum';
  const NAME_ARKIVUTTREKK_ALGORITHM  = 'algorithm';
  const NAME_ARKIVUTTREKK_VALUE  = 'value';
  const NAME_ARKIVUTTREKK_INFO  = 'info';
  const NAME_ARKIVUTTREKK_NUMBEROFOCCURRENCES  = 'numberOfOccurrences';
  const NAME_ARKIVUTTREKK_DATATYPE  = 'dataType';
  const NAME_ARKIVUTTREKK_INTEGER  = 'integer';
  const NAME_ARKIVUTTREKK_MAPPE  = 'mappe';
  const NAME_ARKIVUTTREKK_REGISTRERING = 'registrering';
  const NAME_ARKIVUTTREKK_ATTRIB_NAME  = 'name';

  const EXCEPTION_ACCESS_FUNCTION_YOU_SHOULDNT = 'Access to function forbidden.';
  const EXCEPTION_UNKNOWN_NOARK5_OBJECT = 'Unknown Noark 5 Object ';

  // Set to an arbitrary value of 10
  const FILE_PROCESSING_MAX_RECURSIVE_DEPTH = 10;
  const TEST_TYPE_A1 = 'a1';
  const TEST_TYPE_A2 = 'a2';
  const TEST_TYPE_A3 = 'a3';
  const TEST_TYPE_A4 = 'a4';
  const TEST_TYPE_A5 = 'a5';
  const TEST_TYPE_A6 = 'a6';
  const TEST_TYPE_A7 = 'a7';
  const TEST_TYPE_A8 = 'a8';
  const TEST_TYPE_A9 = 'a9';


  const XSDFILE = 'XSD';
}

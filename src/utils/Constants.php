<?php

class Constants
{
  const TOOL_NAME = "KDRS-toolbox-validator-noark5";
  const TEST_XMLTEST = "XML Test";
  const TEST_XMLTEST_VALIDATION_WELLFORMED = "XML Test well-formed";
  const TEST_XMLTEST_VALIDATION_VALID = "XML Test valid";
  const TEST_NOARK5 = "Noark5";
  const TEST_CHECKSUM = "Checksum";
  const TEST_CHECKSUM_ALL_DOCUMENTS = "Checksum of all documents";
  const TEST_DOCUMENT_DIRECTORY = "Document Directory";
  const TEST_COUNT_MAPPE_ARKIVUTTREKK = "Mappe Count";
  const TEST_COUNT_REGISTRERING_ARKIVUTTREKK = "Registrering Count";
  const TEST_COUNT_DOCUMENTS_ARKIVUTTREKK = "Document Count";
  const TEST_COUNT_DOCUMENTS_ACTUAL = "Document Count in directory";
  const TEST_ARKIVUTTREKK_INFO_XML_CHECKSUM = "Check checksum in info.xml against arkivuttrekk.xml";
  const TEST_FILE_EXISTS_AND_READABLE = "Check that the file exists and is readable";
  const TEST_INCOMING_REGISTRYENTRY_SIGNEDOFF = 'Check that all incoming registry entry are signed off';
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

  const LOG_CONSOLE = 'console';
  const LOG_FILE = 'file';
  const LOG_BOTH = 'both';

  const REGISTRY_ENTRY_TYPE_INCOMING = "Inngående dokument";
  // Set to an arbitrary value of 10
  const FILE_PROCESSING_MAX_RECURSIVE_DEPTH = 10;
  const TEST_TYPE_A0 = 'a0'; // Checks if all the files that we expect in the directory are present and readable
  const TEST_TYPE_A1 = 'a1'; // Test correct-structured and wellformedness
  const TEST_TYPE_A2 = 'a2'; // All checksums of documents in document folder are correct
  const TEST_TYPE_A3 = 'a3';
  const TEST_TYPE_A4 = 'a4';
  const TEST_TYPE_A5 = 'a5';
  const TEST_TYPE_A6 = 'a6';
  const TEST_TYPE_A7 = 'a7';
  const TEST_TYPE_A8 = 'a8';
  const TEST_TYPE_A9 = 'a9';
  const TEST_TYPE_A10 = 'a9';
  const TEST_TYPE_C1 = 'c1';
  const TEST_TYPE_C2 = 'c2';
  const TEST_TYPE_C3 = 'c3';
  const TEST_TYPE_C4 = 'c4';
  const TEST_TYPE_C5 = 'c5';
  const TEST_TYPE_C6 = 'c6';
  const TEST_TYPE_C7 = 'c7';
  const TEST_TYPE_C8 = 'c8';
  const TEST_TYPE_C9 = 'c9';
  const TEST_TYPE_C10 = 'c10';
  const TEST_TYPE_C11 = 'c11';
  const TEST_TYPE_C12 = 'c12';
  const TEST_TYPE_C13 = 'c13';
  const TEST_TYPE_C14 = 'c14';
  const TEST_TYPE_C15 = 'c15';
  const TEST_TYPE_C16 = 'c16';



  const XSDFILE = 'XSD';

  const MAX_RECURSIVE_DEPTH = 10;
  const NAME_DOCUMENT_DIRECTORY = 'dokumenter';
}

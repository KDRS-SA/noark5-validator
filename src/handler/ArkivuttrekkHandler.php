<?php
require_once ('models/noark5/v31/arkivuttrekkXML/ArkivUttrekkDetails.php');
require_once ('models/noark5/v31/arkivuttrekkXML/ArkivuttrekkExtractionInfo.php');
require_once ('models/noark5/v31/arkivuttrekkXML/ArkivUttrekkNoark5File.php');

/*
 * I admit this is ugly code! The XML strucutre we have to deal with does not lend itself
 * to giving good variable names. In many ways this is code that just works, you can figure
 * it out but it is confusing, but the original XML is confusing. Maybe we should try and rewrite
 * it, and make it more clear but it's only around 25 lines of code to get the data out.
 *
 * Some things are declared as constants. This is to allow for change in ADDML standard without having to
 * delve into code. Just change in Constants.php
 *
 */
class ArkivuttrekkHandler
{

    /**
     *
     * @var ArkivUttrekkDetails $arkivUttrekkDetails : Object populated with the values from arkivuttrekk.xml
     */
    protected $arkivUttrekkDetails;

    /**
     *
     * @var string $arkivUttrekkFilename : Path and name of the file arkivuttrekk.xml
     */
    protected $arkivUttrekkFilename;

    function __construct($arkivUttrekkFilename)
    {
        $this->arkivUttrekkFilename = $arkivUttrekkFilename;
        $this->arkivUttrekkDetails = new ArkivUttrekkDetails();
    }

    public function processArkivuttrekk()
    {
        $arkivUttrekk = simplexml_load_file($this->arkivUttrekkFilename);

        // get details from info section
        $this->getInfoDetails($arkivUttrekk->dataset->dataObjects->dataObject);
        $startNode = $arkivUttrekk->dataset->dataObjects->dataObject->dataObjects->dataObject;
        /*
         * $startNode = $arkivUttrekk->dataset->dataObjects->dataObject->dataObjects->dataObject;
         * <addml>
         * <dataset>
         * <dataObjects>
         * <dataObject>
         * <dataObjects>
         * <dataObject>
         * This is where the $startNode will start
         */
        foreach ($startNode as $dataObject) {
            foreach ($dataObject->attributes() as $dataObjectAttrib => $dataObjectAttribValue) {
                // Looking for this : <dataObject name="arkivstruktur">
                if (strcasecmp($dataObjectAttribValue, Constants::NAME_ARKIVSTRUKTUR) == 0) {
                    $this->arkivUttrekkDetails->setArkivstruktur($this->getFileDetails($dataObject, Constants::NAME_ARKIVSTRUKTUR));
                }
                // Looking for this : <dataObject name="endringslogg">
                elseif (strcasecmp($dataObjectAttribValue, Constants::NAME_ENDRINGSLOGG) == 0) {
                        $this->arkivUttrekkDetails->setEndringslogg($this->getFileDetails($dataObject, Constants::NAME_ENDRINGSLOGG));
                }
                // Looking for this : <dataObject name="loependeJournal">
                elseif (strcasecmp($dataObjectAttribValue, Constants::NAME_LOEPENDEJOURNAL) == 0) {
                            $this->arkivUttrekkDetails->setLoependeJournal($this->getFileDetails($dataObject, Constants::NAME_LOEPENDEJOURNAL));
                }
                // Looking for this : <dataObject name="offentligJournal">
                elseif (strcasecmp($dataObjectAttribValue, Constants::NAME_OFFENTLIGJOURNAL) == 0) {
                                $this->arkivUttrekkDetails->setOffentligJournal($this->getFileDetails($dataObject, Constants::NAME_OFFENTLIGJOURNAL));
                }
            } // foreach ($dataObject->attributes() as $dataObjectAttrib => $dataObjectAttribValue)
        } // foreach ($startNode as $dataObject)
    } // protected function processArkivutrekk () {


    protected function getFileDetails($dataObject, $noark5File)
    {
        $arkivUttrekkObject = new ArkivUttrekkNoark5File($noark5File);
        $startSection = $dataObject->properties->property;
        // This portion of the XML file contains
        // <properties>
        // <property>
        // Then you get 'sections' where information about the files are marked up. Typically
        // we are out after e.g. filename:
        // <property name="name">
        // <value>loependeJournal.xml</value>
        // </property>
        // or checksum algorithm and checksum value

        // <property name="checksum">
        // <properties>
        // <property name="algorithm">
        // <value>SHA256</value>
        // </property>
        // <property name="value">
        // <value>542104c40d46696033911399ae599ff12a470e3ed939383723c40eec1ead2b5e</value>
        // </property>
        // </properties>
        // </property>
        foreach ($startSection as $fileObject) {
            foreach ($fileObject->attributes() as $fileAttrib => $fileAttribValue) {
                if (strcasecmp($fileAttrib, Constants::NAME_ARKIVUTTREKK_ATTRIB_NAME) == 0 && strcasecmp($fileAttribValue, Constants::NAME_ARKIVUTTREKK_FILE) == 0) {
                    $fileDetailsSection = $startSection->properties->property;
                    foreach ($fileDetailsSection as $fileDetailsObject) {
                        foreach ($fileDetailsObject->attributes() as $attrib => $value) {
                            if (strcasecmp($attrib, Constants::NAME_ARKIVUTTREKK_ATTRIB_NAME) == 0 && strcasecmp($value, Constants::NAME_ARKIVUTTREKK_NAME) == 0) {
                                $arkivUttrekkObject->setFilename($fileDetailsSection->value);
                                // print 'Found file with following name ' . $fileDetailsSection->value . PHP_EOL;
                            } // if
                            elseif (strcasecmp($attrib, Constants::NAME_ARKIVUTTREKK_ATTRIB_NAME) == 0 && strcasecmp($value, Constants::NAME_ARKIVUTTREKK_CHECKSUM) == 0) {
                                $fileChecksumSection = $fileDetailsObject->properties->property;
                                foreach ($fileChecksumSection as $fileChecksumObject) {
                                    foreach ($fileChecksumObject->attributes() as $checksumAttrib => $checksumValue) {
                                        if (strcasecmp($checksumAttrib, Constants::NAME_ARKIVUTTREKK_ATTRIB_NAME) == 0 && strcasecmp($checksumValue, Constants::NAME_ARKIVUTTREKK_ALGORITHM) == 0) {
                                            $arkivUttrekkObject->setChecksumAlgorithm($fileChecksumObject->value);
                                            // print 'Found algorithm with following name ' . $fileChecksumObject->value . PHP_EOL;
                                        } // if
                                        elseif (strcasecmp($checksumAttrib, Constants::NAME_ARKIVUTTREKK_ATTRIB_NAME) == 0 && strcasecmp($checksumValue, Constants::NAME_ARKIVUTTREKK_CHECKSUM) == 0) {
                                            $arkivUttrekkObject->setChecksumValue($fileChecksumObject->value);
                                            // print 'Found checksum with following value ' . $fileChecksumObject->value . PHP_EOL;
                                        } // elseif (strcasecmp($checksumAttrib, Constants::NAME_ARKIVUTTREKK_ATTRIB_NAME) == 0 && strcasecmp($checksumValue, 'value') == 0) {
                                    } // foreach ($fileChecksumObject->attributes() as $checksumAttrib => $checksumValue) {
                                } // foreach ($fileChecksumSection as $fileChecksumObject) {
                            } // elseif (strcasecmp($attrib, Constants::NAME_ARKIVUTTREKK_ATTRIB_NAME) == 0 && strcasecmp($value, 'checksum') == 0) {
                        } // foreach ($fileDetailsObject->attributes() as $attrib => $value)
                    } // foreach ($fileDetailsSection as $fileDetailsObject) {
                } // if (strcasecmp($fileAttrib, Constants::NAME_ARKIVUTTREKK_ATTRIB_NAME) == 0 && strcasecmp($fileAttribValue, 'file') == 0) {

                // Now we look in what we call the $fileInfoSection that contains the number of occurences
                  // of registrering and mappe elements in the associated file (arkivstruktur.xml, loependeJournal.xml, offentligJournal.xml)
                  // The code is silent about missing values. Nothing will be reported if it's missing for arkivstruktur
                  // An eventual check should be done after the file has been parsed and check that the values are set
                  // This code really assumes that arkivuttrekk.xml is correctly built. addml.xsd doesn't really validate arkivuttrekk.xml
                  // for correctness of content, it only check for correctness of structure.
                elseif (strcasecmp($fileAttrib, Constants::NAME_ARKIVUTTREKK_ATTRIB_NAME) == 0 && strcasecmp($fileAttribValue, Constants::NAME_ARKIVUTTREKK_INFO) == 0) {
                    $fileInfoSection = $fileObject->properties->property;
                    foreach ($fileInfoSection as $infoObject) {
                        foreach ($infoObject->attributes() as $attrib => $val) {
                            if (strcasecmp($attrib, Constants::NAME_ARKIVUTTREKK_ATTRIB_NAME) == 0 && strcasecmp($val, Constants::NAME_ARKIVUTTREKK_NUMBEROFOCCURRENCES) == 0) {
                                if (strcasecmp($infoObject->value, Constants::NAME_ARKIVUTTREKK_MAPPE) == 0) {
                                    $countSection = $infoObject->properties->property;
                                    foreach ($countSection as $countObject) {
                                        foreach ($countObject->attributes() as $countAttrib => $countVal) {
                                            if (strcasecmp($countAttrib, Constants::NAME_ARKIVUTTREKK_DATATYPE) == 0 && strcasecmp($countVal, Constants::NAME_ARKIVUTTREKK_INTEGER) == 0) {
                                                // print 'Number of mappe found is ' . $countObject->value . PHP_EOL;
                                                $arkivUttrekkObject->setNumberMappe($countObject->value);
                                            } // if (strcasecmp($countAttrib, 'dataType') == 0 && strcasecmp($countVal, 'integer') == 0)
                                        } // foreach ($countObject->attributes() as $countAttrib => $countVal) {
                                    } // foreach ($countSection as $countObject) {
                                } // if (strcasecmp($infoObject->value, 'mappe') == 0) {
                                elseif (strcasecmp($infoObject->value, Constants::NAME_ARKIVUTTREKK_REGISTRERING) == 0) {
                                    foreach ($infoObject->properties->property as $countObject) {
                                        foreach ($countObject->attributes() as $countAttrib => $countVal) {
                                            if (strcasecmp($countAttrib, Constants::NAME_ARKIVUTTREKK_DATATYPE) == 0 && strcasecmp($countVal, Constants::NAME_ARKIVUTTREKK_INTEGER) == 0) {
                                                // print 'Number of registrering found is ' . $countObject->value . PHP_EOL;
                                                $arkivUttrekkObject->setNumberRegistrering($countObject->value);
                                            } // if (strcasecmp($countAttrib, 'dataType') == 0 && strcasecmp($countVal, 'integer') == 0) {
                                        } // foreach ($countObject->attributes() as $countAttrib => $countVal) {
                                    } // foreach ($infoObject->properties->property as $countObject) {
                                } // elseif (strcasecmp($infoObject->value, 'registrering') == 0) {
                            } // if (strcasecmp($attrib, Constants::NAME_ARKIVUTTREKK_ATTRIB_NAME) == 0 && strcasecmp($val, 'numberOfOccurrences') == 0) {
                        } // foreach ($infoObject->attributes() as $attrib => $val) {
                    } // foreach ($fileInfoSection as $infoObject) {
                } // elseif (strcasecmp($fileAttrib, Constants::NAME_ARKIVUTTREKK_ATTRIB_NAME) == 0 && strcasecmp($fileAttribValue, 'info') == 0) {
            } // foreach ($fileObject->attributes() as $fileAttrib => $fileAttribValue) {
        } // foreach ($startSection as $fileObject) {
        return $arkivUttrekkObject;
    }

    protected function getInfoDetails($dataObject)
    {
        /*
         * <dataObject name="Noark 5 arkivuttrekk">
         * <properties>
         * <property name="info">
         * <properties>
         * <property name="additionalInfo">
         *
         * ... There are more objects in here that can be stored in ExtractionInfo ...
         * ... Just picking up antalldokumenter at the moment ...
         * <properties>
         * <property dataType="integer" name="antallDokumentfiler">
         * <value>4</value>
         *
         */
        $extractionInfo = new ArkivuttrekkExtractionInfo();

        $attributeObject = $dataObject->attributes();
        if (isset($attributeObject['name']) == true)
            $extractionInfo->setDescription($attributeObject['name']);
        else
            $extractionInfo->setDescription(null);

        foreach ($dataObject->properties->property->properties->property as $property) {
            $propertyAttributes = $property->attributes();

            if (isset($propertyAttributes['name']) == true && strcasecmp($propertyAttributes['name'], 'type') == 0) {
                $extractionInfo->setType($property->value);
                $extractionInfo->setVersion($property->properties->property->value);
            } elseif (isset($propertyAttributes['name']) == true && strcasecmp($propertyAttributes['name'], 'additionalInfo') == 0) {

                foreach ($property->properties->property as $periodeInfo) {
                    $periodeInfoAttributes = $periodeInfo->attributes();
                    if (isset($periodeInfoAttributes['name']) == true && strcasecmp($periodeInfoAttributes['name'], 'periode') == 0) {

                        $periodeInfoInAndOut = $periodeInfo->properties->property;

                        foreach ($periodeInfoInAndOut as $periodeInfoInAndOutProperty) {

                            $periodeInfoInAndOutPropertyAttributes = $periodeInfoInAndOutProperty->attributes();
                            if (isset($periodeInfoInAndOutPropertyAttributes['name']) == true && strcasecmp($periodeInfoInAndOutPropertyAttributes['name'], 'inngaaendeSkille') == 0) {
                                $periodeInfoIngaaende = $periodeInfoInAndOutProperty->value;
                                $extractionInfo->setInngaaendeSkille($periodeInfoIngaaende);
                            } elseif (isset($periodeInfoInAndOutPropertyAttributes['name']) == true && strcasecmp($periodeInfoInAndOutPropertyAttributes['name'], 'utgaaendeSkille') == 0) {
                                $periodeInfoUtgaaende = $periodeInfoInAndOutProperty->value;
                                $extractionInfo->setUtgaaendeSkille($periodeInfoUtgaaende);
                            } // elseif
                        } // foreach ($periodeInfoInAndOut as $periodeInfoInAndOutProperty ) {
                    } // if (isset(
                    elseif (isset($periodeInfoAttributes['name']) == true && strcasecmp($periodeInfoAttributes['name'], 'inneholderSkjermetInformasjon') == 0) {
                        if (strcasecmp($periodeInfo->value, 'true') == 0) {
                            $extractionInfo->setInneholderSkjermetInformasjon(true);
                        } elseif (strcasecmp($periodeInfo->value, 'false') == 0) {
                            $extractionInfo->setInneholderSkjermetInformasjon(false);
                        }
                    } // elseif (isset
                    elseif (isset($periodeInfoAttributes['name']) == true && strcasecmp($periodeInfoAttributes['name'], 'omfatterDokumenterSomErKassert') == 0) {
                        if (strcasecmp($periodeInfo->value, 'true') == 0) {
                            $extractionInfo->setOmfatterDokumenterSomErKassert(true);
                        } elseif (strcasecmp($periodeInfo->value, 'false') == 0) {
                            $extractionInfo->setOmfatterDokumenterSomErKassert(false);
                        }
                    } // elseif (isset
                    elseif (isset($periodeInfoAttributes['name']) == true && strcasecmp($periodeInfoAttributes['name'], 'inneholderDokumenterSomSkalKasseres') == 0) {
                        if (strcasecmp($periodeInfo->value, 'true') == 0) {
                            $extractionInfo->setInneholderDokumenterSomSkalKasseres(true);
                        } elseif (strcasecmp($periodeInfo->value, 'false') == 0) {
                            $extractionInfo->setInneholderDokumenterSomSkalKasseres(false);
                        }
                    } // elseif (isset
                    elseif (isset($periodeInfoAttributes['name']) == true && strcasecmp($periodeInfoAttributes['name'], 'inneholderVirksomhetsspesifikkeMetadata') == 0) {
                        if (strcasecmp($periodeInfo->value, 'true') == 0) {
                            $extractionInfo->setInneholderVirksomhetsspesifikkeMetadata(true);
                        } elseif (strcasecmp($periodeInfo->value, 'false') == 0) {
                            $extractionInfo->setInneholderVirksomhetsspesifikkeMetadata(false);
                        }
                    } // elseif (isset
                    elseif (isset($periodeInfoAttributes['name']) == true && strcasecmp($periodeInfoAttributes['name'], 'antallDokumentfiler') == 0) {
                        $extractionInfo->setAntallDokumentfiler($periodeInfo->value);
                    }
                } // foreach
            } // elseif (isset(
        } // foreach (

        $this->arkivUttrekkDetails->setExtractionInfo($extractionInfo);
    }

    public function getArkivUttrekkDetails()
    {
        return $this->arkivUttrekkDetails;
    }
}

?>
<?php

require_once 'PHPWord-0.12.0/src/PhpWord/PhpWord.php';
require_once 'PHPWord-0.12.0/src/PhpWord/Style/Cell.php';
require_once 'PHPWord-0.12.0/src/PhpWord/Autoloader.php';

use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Cell;

class ReportBuilder {

    protected $testResultsHandler;

    protected $beskrivelseA1 = 'Testen sjekker om XML filene er velformulert i henhold til XML 1.0 standarden, samt om filene validerer mot sine respektive Noark-5 XSD skjema. I praksis sjekkes altså om filene virkelig er XML filer ved å teste de mot krav presentert i XML 1.0 standarden. Deretter sjekkes man om XML filene følger malen for innhold og struktur mot krav i Noark-5 standarden.';
    protected $beskrivelseA2 = 'Testen sjekker om all sjekksummer stemmer ';
    protected $beskrivelseA3 = 'Samtlige medfølgende PDF dokumenter valideres mot PDF/A standarden, både mot versjon 1a og 1b. Dersom testen feiler gjennomføres stikkprøver mot dokumenter som feiler i Adobe Profesjonal X.';
    protected $beskrivelseA4 = 'Testen etterprøver oppføringer i endringslogg.xml mot opplysninger i arkivstruktur.xml. Obigatoriske endringer finnes i filen arkivstruktur.xml, mens endringslogg skal logge kontekstuelle endringer som i etterkant kan vise seg verdifull i forhold til materialets autentisitet. Eksempler på slike endringer er omklassifikasjon av en mappe, flytting av registrering fra en mappe til en annen mappe, endring av saksansvarlig, endring av saksbehandler, reversering av statusverdiger og endringer av metadata etter at et dokument er arkivert.';
    protected $beskrivelseA5 = 'Tester om antall dokumenter som oppgis i arkivstruktur.xml validerer mot antall dokumenter som oppgis i «antallDokumentfiler» i arkivuttrekk.xml – dvs om antall dokumenter hentet ut i uttrekket stemmer overens med faktiske dokumenter i arkivdelen.';
    protected $beskrivelseA6 = 'Tester om antall dokumenter oppgitt i arkivstruktur.xml stemmer overens med faktisk antall dokumenter som ligger ved uttrekket i mappen «dokumenter». ';
    protected $beskrivelseA7 = 'Tester om antall elementer av type «mappe» stemmer overens med antall «mappe numerOfOccurrences» i arkivuttrekk.xml – altså om antall mapper som blir med ut i uttrekket stemmer overens med faktisk antall mapper i arkivdelen.';
    protected $beskrivelseA8 = 'Tester om antall elementer av type «registreringer» stemmer i henhold til det som oppgis i uttrekket. Utføres på samme måte som med mappe.';
    protected $beskrivelseA9 = 'Tester om sjekksumen til filen arkivuttrekk.xml som er oppgitt i info.xml stemmer.';


    protected $phpWord;

    function __construct($testResultsHandler)
    {
        Autoloader::register();
        Settings::loadConfig();

         $this->testResultsHandler = $testResultsHandler;
         $this->phpWord = new PhpWord();
    }

    public function createDocument() {

        $this->phpWord->addFontStyle('rStyle', array('bold' => true, 'italic' => true, 'size' => 16, 'allCaps' => true, 'doubleStrikethrough' => true));
        $this->phpWord->addParagraphStyle('pStyle', array('align' => 'center', 'spaceAfter' => 100));
        $this->phpWord->addTitleStyle(1, array('bold' => true), array('spaceAfter' => 240));


        $section = $this->phpWord->addSection();
        $section->addTitle(htmlspecialchars('Rapport - Validering av Noark 5 Uttrekk'), 1);

        // get all A1 test results
        $testResults = $this->testResultsHandler->getResults(Constants::TEST_TYPE_A1);
        $this->buildReportPageA1($section, $testResults, 'A-1 Er uttrekket valid og velformulert', $this->beskrivelseA1);

        // get all A2 test results
        $testResults = $this->testResultsHandler->getResults(Constants::TEST_TYPE_A2);
        $this->buildReportPageA1($section, $testResults, 'A-2 Sjekksummer for dokumenter', $this->beskrivelseA2);

        // get all A3 test results
        $testResults = $this->testResultsHandler->getResults(Constants::TEST_TYPE_A3);
        $this->buildReportPageA1($section, $testResults, 'A-3 Validering av dokumenter mot arkivformat', $this->beskrivelseA3);

        // get all A4 test results
        $testResults = $this->testResultsHandler->getResults(Constants::TEST_TYPE_A4);
        $this->buildReportPageA1($section, $testResults, 'A-4 Validering av endringslogg.xml mot arkivstruktur.xml', $this->beskrivelseA4);

        // get all A5 test results
        $testResults = $this->testResultsHandler->getResults(Constants::TEST_TYPE_A5);
        $this->buildReportPageA1($section, $testResults, 'A-5 Validering antall dokumenter 1', $this->beskrivelseA5);

        // get all A6 test results
        $testResults = $this->testResultsHandler->getResults(Constants::TEST_TYPE_A6);
        $this->buildReportPageA1($section, $testResults, 'A-6 Validering antall dokumenter 2', $this->beskrivelseA6);

        // get all A7 test results
        $testResults = $this->testResultsHandler->getResults(Constants::TEST_TYPE_A7);
        $this->buildReportPageA1($section, $testResults, 'A-7 Validerer oppgitt antall mapper', $this->beskrivelseA7);

        // get all A8 test results
        $testResults = $this->testResultsHandler->getResults(Constants::TEST_TYPE_A8);
        $this->buildReportPageA1($section, $testResults, 'A-8 Validerer antall oppgitt registreringer', $this->beskrivelseA8);

        // get all A9 test results
        $testResults = $this->testResultsHandler->getResults(Constants::TEST_TYPE_A9);
        $this->buildReportPageA1($section, $testResults, 'A-9 Integritetsjekk av info.xml', $this->beskrivelseA9);

        $this->phpWord->save('test.odt', 'ODText');
        //$this->phpWord->save('test.docx', 'Word2007');
        //$this->phpWord->save('test.pdf', 'PDF');

    }

    protected function buildReportPageA1($section, $testResults, $testHeader, $description) {


        $fontStyle = array();
        // Inline font style
        $fontStyle['name'] = 'Times New Roman';
        $fontStyle['size'] = 12;

        $header = array('size' => 16, 'bold' => true);
        $section->addText(htmlspecialchars($testHeader), $header);

        $styleTable = array('borderSize' => 60, 'borderColor' => '006699', 'cellMargin' => 8, 'width' => '100%');
        $styleFirstRow = array('borderBottomSize' => 18, 'borderBottomColor' => '0000FF', 'bgColor' => '66BBFF');
        $this->phpWord->addTableStyle('Fancy Table', $styleTable, $styleFirstRow);

        $cellStyle = array('textDirection' => 'tbRl');

        $section->addText("Beskrivelse");
        $beskrivelseTable = $section->addTable('Fancy Table');
        $beskrivelseTable->addRow();
        $cell = $beskrivelseTable->addCell(1000);
        $cell->addText($description);
        $section->addTextBreak();

        $section->addText("Resultat");
        $resultatTable = $section->addTable('Fancy Table');
        $resultatTable->addRow();
        $cell = $resultatTable->addCell(1750);

        if ($testResults != null) {
            foreach ($testResults as $testResult) {
                $cell->addText(htmlspecialchars($testResult->getDescriptionReport()));
            }
        }
        else {
            $cell->addText('Finner ingenting å rapportere. Dette kan være fordi denne testen er ikke ferdig implemtert, men kan også være pga en feil i kjøring av tester! Manuell kontroll nødvendig!');
        }
        $section->addTextBreak();

        $section->addText("Kommentarer");
        $kommentarTable = $section->addTable();
        $kommentarTable->addRow();
        $cell = $kommentarTable->addCell(1750);
        $section->addTextBreak();

        $section->addText("Eventuell utbedring");
        $utbedringTable = $section->addTable();
        $utbedringTable->addRow();
        $cell = $utbedringTable->addCell(1750);

        $section->addPageBreak();

    }
}


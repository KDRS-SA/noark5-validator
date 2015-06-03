<?php
require_once ('tests/Test.php');

class DocumentDirectoryTest extends Test
{

    protected $directory;

    protected $documentListHandler;

    function __construct($testName, $directory, $documentListHandler, $testProperty)
    {
        parent::__construct($testName, $testProperty);
        $this->directory = $directory;
        $this->documentListHandler = $documentListHandler;
    }

    public function runTest()
    {
        print 'Testing for know errors that can occur in the documents directory' . PHP_EOL;

        $allOK = true;
        $description = 'Documents directory check contained errors. Following is found. ';

        $fileListDuplicates = $this->documentListHandler->getDuplicates();
        $duplicatDocumentCount = count($fileListDuplicates);
        if ($duplicatDocumentCount != 0) {
            $description .= 'Duplicate Documents detected. This may not be an error. Number is (' . $duplicatDocumentCount . ') ';
            $description .= 'It could be acceptable, but also the use of subdirectories might cause this to happen. Manual check advised. ';
            $allOK = false;
            // TODO: Print these to the log file
            $allDuplicateFiles = $this->documentListHandler->getRemaining();
            foreach ($allDuplicateFiles as $duplicateFile => $value) {
                echo 'The file (' . $duplicateFile . ') exists in the document folder but is not referenced in arkivstruktur.xml' . PHP_EOL;
                $description .= 'The file (' . $duplicateFile . ') exists in the document folder but is not referenced in arkivstruktur.xml';
            }
        }

        $allFilesNotReferenced = $this->documentListHandler->getRemaining();
        $documentsInDocumentsFolderNotInArkivstruktur = count($allFilesNotReferenced);
        if ($documentsInDocumentsFolderNotInArkivstruktur != 0) {
            $description .= 'There are a number of files in the document folder that are not referenced in arkivstruktur.xml. ';
            $description .= 'The number is (' . $documentsInDocumentsFolderNotInArkivstruktur . '). This is most likely an error. ';
            $allOK = false;
            // TODO: Print these to the log file
            foreach ($allFilesNotReferenced as $fileNotReferenced => $value) {
                echo 'The file (' . $fileNotReferenced . ') exists in the document folder but is not referenced in arkivstruktur.xml' . PHP_EOL;
                $description .= 'The file (' . $fileNotReferenced . ') exists in the document folder but is not referenced in arkivstruktur.xml' ;
            }
        }

        $allRemainingFiles = $this->documentListHandler->getNotReferenced();
        $documentsInArkivstrukturNotInDocumentsFolder = count($allRemainingFiles);
        if ($documentsInArkivstrukturNotInDocumentsFolder != 0) {
            $description .= 'There are a number of files referenced in arkivstruktur.xml that are not in the documents folder. ';
            $description .= 'The number is (' . $documentsInArkivstrukturNotInDocumentsFolder . '). This is most likely an error. ';
            $allOK = false;
            // TODO: Print these to the log file
            foreach ($allRemainingFiles as $remainingFile => $value) {
                echo 'The file (' . $remainingFile . ') is referenced in arkivstruktur.xml, but does not exist in the documents folder' . PHP_EOL;
                $description .= 'The file (' . $remainingFile . ') is referenced in arkivstruktur.xml, but does not exist in the documents folder';
            }
        }

        $description .= 'See log file for details about files. ';

        if ($allOK == false) {
            $this->testProperty->addTestResult(false);
            $this->testProperty->addTestResultDescription($description);
        } else {
            $this->testProperty->addTestResult(true);
            $this->testProperty->addTestResultDescription('No document count errors detected! ');
        }
    }
}

?>
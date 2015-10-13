<?php
require_once ('handler/ArkivstrukturParser.php');

class ArkivstrukturDBImporter extends ArkivstrukturParser
{

    protected $entityManager;

    /*
     * //TODO : You have to handle
     * referanseArkivdel
     *
     * Look at the flush command if it's not a File, i.e only records
     *
     * Tidy alphabetically, check that all add have a contains, consider reducing all pre to a singel function
     * should all subclasses call parent in construct
     *
     * check logger messages, remove merge, persist at regualr intervals
     * check messages and variables in error handling code for variables that don't exist
     *
     * Storagelocation - check ontomany/manytomany. defo problem here, check java version as well!
     *
     * Check all arraycollection objects are initialised, missing in file??
     *
     * Check that manytomany autoset their partnetr
     * TODO: Remove duplicates on storage location if they are there
     * so do a check first
     *
     * When handling duplicate values in storagelocation
     * or keyword, the duplicate values will be added into
     * the database. We can consider searching for the key word first
     *
     * ORM references are handles as follows:
     *
     * 1:1
     *
     * 1:M References are added to the many-side
     *
     * M:M References are added to both sides. The FROM-entity will always
     * set the reference to the TO-entity
     *
     */

    // Used to handle the referanseArkivdel under File/Record/DocumentDescription
    protected $currentSeries;

    // Used when a class has a subclass. Neet to link every class back to classificationSystem
    protected $currentClassificationSystem;

    public function __construct($directory, $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($directory, null, null);
    }

    protected function preProcessNoarkObject($classType)
    {
        if ($this->checkObjectClassTypeCorrect($classType) == true) {
            $noarkObject = end($this->stack);
            $this->entityManager->persist($noarkObject);
            $this->logger->trace('Persisting initial ' . $classType . ' to database. Method (' . __METHOD__ . ')' . $noarkObject);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ' Expected ' . $classType . ', found ' . get_class(end($this->stack)));
        }
    }

    public function preProcessCaseParty()
    {
        $this->preProcessNoarkObject("CaseParty");
    }

    public function preProcessClassificationSystem()
    {
        $this->preProcessNoarkObject("ClassificationSystem");
        $this->currentClassificationSystem = end($this->stack);
    }

    public function preProcessClassified()
    {
        $this->preProcessNoarkObject("Classified");
    }

    public function preProcessClass()
    {
        $this->preProcessNoarkObject("Klass");
    }

    public function preProcessComment()
    {
        $this->preProcessNoarkObject("Comment");
    }

    public function preProcessConversion()
    {
        $this->preProcessNoarkObject("Conversion");
    }

    public function preProcessCorrespondencePart()
    {
        $this->preProcessNoarkObject("CorrespondencePart");
    }

    public function preProcessCrossReference()
    {
        $this->preProcessNoarkObject("CrossReference");
    }

    public function preProcessDeletion()
    {
        $this->preProcessNoarkObject("Deletion");
    }

    public function preProcessDisposal()
    {
        $this->preProcessNoarkObject("Disposal");
    }

    public function preProcessDisposalUndertaken()
    {
        $this->preProcessNoarkObject("DisposalUndertaken");
    }

    public function preProcessDocumentDescription()
    {
        $this->preProcessNoarkObject("DocumentDescription");
    }

    public function preProcessDocumentObject()
    {
        $this->preProcessNoarkObject("DocumentObject");
    }

    public function preProcessElectronicSignature()
    {
        // $this->preProcessNoarkObject("ElectronicSignature");
    }

    public function preProcessFile($classType)
    {
        $this->preProcessNoarkObject($classType);
    }

    public function preProcessFonds()
    {
        $this->preProcessNoarkObject("Fonds");
    }

    public function preProcessFondsCreator()
    {
        $this->preProcessNoarkObject("FondsCreator");
    }

    public function preProcessMeetingParticipant()
    {
        $this->preProcessNoarkObject("MeetingParticipant");
    }

    public function preProcessPrecedence()
    {
        $this->preProcessNoarkObject("Precedence");
    }

    public function preProcessRecord($classType)
    {
        $this->preProcessNoarkObject($classType);
    }

    public function preProcessScreening()
    {
        $this->preProcessNoarkObject("Screening");
    }

    public function preProcessSeries()
    {
        $this->preProcessNoarkObject("Series");
        $this->currentSeries = end($this->stack);
    }

    public function preProcessSignOff()
    {
        $this->preProcessNoarkObject("SignOff");
    }

    public function preProcessWorkflow()
    {
        $this->preProcessNoarkObject("Workflow");
    }

    public function postProcessCaseParty()
    {
        if ($this->checkObjectClassTypeCorrect("ClassificationSystem") === true) {

            $parentObject = prev($this->stack);
            $caseParty = end($this->stack);

            if (get_class($parentObject) === "CaseFile") {
                // Associate this CaseParty with the parent CaseFile
                // CaseFile:CaseParty is M:M
                // Two-way reference is set in addReferenceCaseParty
                $parentObject->addReferenceCaseParty($parentObject);
            } else {
                throw new Exception("Incorrect parent object to CaseParty. Got " . get_class($parentObject), null, null);
            }
            $this->logger->trace('Updated a ' . get_class(end($this->stack)) . ' [' . end($this->stack) . ']');
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected CaseParty found " . get_class(end($this->stack)));
        }
    }
    public function postProcessClass()
    {
        if ($this->checkObjectClassTypeCorrect("Klass") === true) {

            $parentObject = prev($this->stack);
            $klass = end($this->stack);

            if (get_class($parentObject) === "ClassificationSystem") {
                // Associate this Class with the parent ClassificationSystem
                // ClassificationSystem:Class is 1:M
                $parentObject->addReferenceClass($klass);
                $this->entityManager->flush();
            } elseif (get_class($parentObject) === "Klass") {
                // Associate this Class with the parent Class
                // Class:Class is 1:M
                $parentObject->addReferenceChildClass($klass);
            } else {
                throw new Exception("Unknown parent object to Class. Got " . get_class($parentObject), null, null);
            }
            $this->logger->trace('Updated a ' . get_class(end($this->stack)) . ' [' . end($this->stack) . ']');
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected Class found " . get_class(end($this->stack)));
        }
    }

    public function postProcessClassified()
    {
        if ($this->checkObjectClassTypeCorrect("Classified") === true) {

            $parentObject = prev($this->stack);
            $classified = end($this->stack);

            if (get_class($parentObject) === 'Series') {
                // Associate this Classified with the parent Series
                // Classified:Series is 1:M
                $classified->addReferenceSeries($parentObject);
            } elseif (get_class($parentObject) === 'File') {
                // Associate this Classified with the parent File
                // Classified:File is 1:M
                $classified->addReferenceFile($parentObject);
            } elseif (get_class($parentObject) === 'Klass') {
                // Associate this Classified with the parent Klass
                // Classified:Klass is 1:M
                $classified->addReferenceRecord($parentObject);
            } elseif (get_class($parentObject) === 'Record') {
                // Associate this Classified with the parent Record
                // Classified:Record is 1:M
                $classified->addReferenceDocumentDescription($parentObject);
            } elseif (get_class($parentObject) === 'DocumentDescription') {
                // Associate this Classified with the parent DocumentDescription
                // Classified:DocumentDescription is 1:M
                $classified->addReferenceSeries($parentObject);
            } else {
                throw new Exception("Unknown parent object to Classified. Got " . get_class($parentObject), null, null);
            }
            $this->logger->trace('Updated a ' . get_class(end($this->stack)) . ' [' . end($this->stack) . ']');
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected Classified found " . get_class(end($this->stack)));
        }
    }

    public function postProcessClassificationSystem()
    {
        if ($this->checkObjectClassTypeCorrect("ClassificationSystem") === true) {

            $parentObject = prev($this->stack);
            $classificationSystem = end($this->stack);

            if (get_class($parentObject) === "Series") {
                // Associate this ClassificationSystem with the parent Series
                // Series:ClassificationSystem is M:M
                // Two-way reference is set in addReferenceClassificationSystem
                $parentObject->addReferenceClassificationSystem($classificationSystem);
            } else {
                throw new Exception("Incorrect parent object to ClassificationSystem. Got " . get_class($parentObject), null, null);
            }
            $this->logger->trace('Updated a ' . get_class(end($this->stack)) . ' [' . end($this->stack) . ']');
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected ClassificationSystem found " . get_class(end($this->stack)));
        }
    }

    // Note that due linear processing, we do not
    // keep earlier comments in memory. This reduces the
    // M:M to 1:M. It's a trade-off on complexity/memory use.
    public function postProcessComment()
    {
        if ($this->checkObjectClassTypeCorrect("Comment") === true) {
            $parentObject = prev($this->stack);
            $comment = end($this->stack);
            $classParents = class_parents($parentObject);

            if (isset($classParents["File"]) === true) {
                // Associate this Comment with the parent File
                // File:Comment is M:M
                // Two-way reference is set in addReferenceComment
                $parentObject->addReferenceComment($comment);
            } elseif (isset($classParents["BasicRecord"]) === true) {
                // Associate this Comment with the parent File
                // BasicRecord:Comment is M:M
                // Two-way reference is set in addReferenceComment
                $parentObject->addReferenceComment($comment);
            } elseif (get_class($parentObject) === "DocumentDescription") {
                // Associate this Comment with the parent File
                // DocumentDescription:Comment is M:M
                // Two-way reference is set in addReferenceComment
                $parentObject->addReferenceComment($comment);
            } else {
                throw new Exception("Unknown parent object to Comment. Got " . get_class($parentObject), null, null);
            }
            $this->logger->trace('Updated a ' . get_class(end($this->stack)) . ' [' . end($this->stack) . ']');
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected Comment found " . get_class(end($this->stack)));
        }
    }

    public function postProcessConversion()
    {
        if ($this->checkObjectClassTypeCorrect("Conversion") === true) {
            $parentObject = prev($this->stack);
            $conversion = end($this->stack);

            if (get_class($parentObject) === "DocumentObject") {
                // Associate this Conversion with the parent DocumentObject
                // DocumentObject:Conversion is 1:M
                $conversion->addReferenceDocumentObject();
                $parentObject->addReferenceConversion($conversion);
            } else {
                throw new Exception("Unknown parent object to Conversion. Got " . get_class($parentObject), null, null);
            }
            $this->logger->trace('Updated a ' . get_class(end($this->stack)) . ' [' . end($this->stack) . ']');
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected Comment found " . get_class(end($this->stack)));
        }
    }

    public function postProcessCorrespondencePart()
    {
        if ($this->checkObjectClassTypeCorrect("CorrespondencePart") === true) {
            $parentObject = prev($this->stack);
            $correspondencePart = end($this->stack);

            if (get_class($parentObject) === "RegistryEntry") {
                // Associate this CorrespondencePart with the parent RegistryEntry
                // RegistryEntry:CorrespondencePart is 1:M
                $parentObject->addCorrespondencePart($correspondencePart);
            } else {
                throw new Exception("Unknown parent object to CorrespondencePart. Got " . get_class($parentObject), null, null);
            }
            $this->logger->trace('Updated a ' . get_class(end($this->stack)) . ' [' . end($this->stack) . ']');
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected CorrespondencePart found " . get_class(end($this->stack)));
        }
    }

    public function postProcessCrossReference()
    {
        if ($this->checkObjectClassTypeCorrect("CrossReference") === true) {
            $parentObject = prev($this->stack);
            $crossReference = end($this->stack);

            if (get_class($parentObject) === "Klass" || get_class($parentObject) === "File" || get_class($parentObject) === "BasicRecord") {
                // Associate this CrossReference with the parent Class | File | BasicRecord
                // Object:CrossReference is 1:M
                $parentObject->addReferenceCrossReference($crossReference);
            } else {
                throw new Exception("Unknown parent object to CrossReference. Got " . get_class($parentObject), null, null);
            }
            $this->logger->trace('Updated a ' . get_class(end($this->stack)) . ' [' . end($this->stack) . ']');
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected CrossReference found " . get_class(end($this->stack)));
        }
    }

    public function postProcessDeletion()
    {
        if ($this->checkObjectClassTypeCorrect("Deletion") === true) {
            $parentObject = prev($this->stack);
            $deletion = end($this->stack);

            if (get_class($parentObject) === "Series") {
                // Associate this Deletion with the parent Series
                // Series:Deletion is 1:M
                $deletion->addReferenceSeries($parentObject);
            } elseif (get_class($parentObject) === "DocumentDescription") {
                // Associate this Deletion with the parent DocumentDescription
                // Series:DocumentDescription is 1:M
                $deletion->addReferenceDocumentDescription($parentObject);
            } else {
                throw new Exception("Unknown parent object to Deletion. Got " . get_class($parentObject), null, null);
            }
            $this->logger->trace('Updated a ' . get_class(end($this->stack)) . ' [' . end($this->stack) . ']');
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected Deletion found " . get_class(end($this->stack)));
        }
    }

    public function postProcessDisposal()
    {}

    public function postProcessDisposalUndertaken()
    {}

    public function postProcessDocumentDescription()
    {
        if ($this->checkObjectClassTypeCorrect("DocumentDescription") === true) {
            $parentObject = prev($this->stack);
            $documentDescription = end($this->stack);

            // Associate this documentDescription with the current Record object
            $documentDescription->addReferenceRecord($parentObject);

            // Associate the current Record object with this documentDescription
            // It is not required to get the association correct in the db
            // but needed for the dettach
            // $this->currentRecord->addReferenceDocumentDescription($documentDescription);
            print $documentDescription . PHP_EOL;
            $this->entityManager->flush();
            $this->logger->trace('Updated a ' . get_class(end($this->stack)) . ' [' . end($this->stack) . ']');
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected DocumentDescription found " . get_class(end($this->stack)));
        }
    }

    public function postProcessDocumentObject()
    {
        if ($this->checkObjectClassTypeCorrect("DocumentObject") == true) {

            $parentObject = prev($this->stack);
            $documentObject = end($this->stack);

            if (get_class($parentObject) === "DocumentDescription") {
                // Associate this documentObject with the current DocumentDescription object
                $documentObject->setReferenceDocumentDescription($parentObject);
                $parentObject->addReferenceDocumentObject($documentObject);
            } elseif (class_parents($parentObject) === "Record") {
                // Associate this documentObject with the current Record object
                // $documentObject->setReferencerecord($parentObject);
                $parentObject->addReferenceDocumentObject($documentObject);
            } else {
                $this->entityManager->flush();
                throw new Exception("Unknown parent object to DocumentObject. Got " . get_class($parentObject), null, null);
            }
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected DocumentObject found " . get_class(end($this->stack)));
        }
    }

    public function postProcessElectronicSignature()
    {}

    public function postProcessFile($classType)
    {
        if ($this->checkObjectClassTypeCorrect($classType) == true) {

            $parentObject = prev($this->stack);
            $file = end($this->stack);

            // If there is a File object above me, then I am a child File
            if (get_class($parentObject) === "File") {
                $this->setReferenceParentFile($parentObject);
                $parentObject->addReferenceChildFile($file);
            } elseif (get_class($parentObject) === "Klass") {
                // Associate this file with the current Klass object
                $file->setReferenceClass($parentObject);
            } elseif (get_class($parentObject) === "Series") {
                // Associate this file with the current series object
                $file->setReferenceSeries($parentObject);
            } else {
                throw new Exception("Unknown parent object to DocumentObject. Got " . get_class($parentObject), null, null);
            }
            $this->entityManager->flush();
            $this->logger->trace('Updated a ' . get_class(end($this->stack)) . ' [' . end($this->stack) . ']');
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected File found " . get_class(end($this->stack)));
        }
    }

    public function postProcessFonds()
    {
        if ($this->checkObjectClassTypeCorrect("Fonds") == true) {
            $parentObject = prev($this->stack);
            $fonds = end($this->stack);

            // If there is a Fonds object above me, then I am a child Fonds
            if ($parentObject != false && get_class($parentObject) === "Fonds") {
                $this->setReferenceParentFonds($parentObject);
                $parentObject->addReferenceChildFonds($fonds);
            } elseif ($parentObject != false) {
                throw new Exception("Unknown parent object to Fonds. Got " . get_class($parentObject), null, null);
            }

            $this->logger->trace('Updated a ' . get_class(end($this->stack)) . ' [' . end($this->stack) . ']');
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected Fonds found " . get_class(end($this->stack)));
        }
    }

    public function postProcessFondsCreator()
    {
        if ($this->checkObjectClassTypeCorrect("FondsCreator") == true) {
            $parentObject = prev($this->stack);
            $fondsCreator = end($this->stack);

            // Associate this fondsCreator with the current fonds object
            $fondsCreator->addReferenceFonds($parentObject);
            $this->logger->trace('Updated a ' . get_class(end($this->stack)) . ' [' . end($this->stack) . ']');
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected FondsCreator found " . get_class(end($this->stack)));
        }
    }

    public function postProcessMeetingParticipant()
    {
        if ($this->checkObjectClassTypeCorrect('MeetingParticipant') == true) {

            $parentObject = prev($this->stack);
            $meetingParticipant = end($this->stack);

            if (get_class($parentObject) === "MeetingFile") {
                $parentObject->addReferenceMeetingParticipant($meetingParticipant);
                $this->logger->trace('In method ' . __METHOD__ . '. Associating meetingParticipant ' . $meetingParticipant . ' with meetingFile' . $parentObject);
            } else {
                throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Found a record associated with a " . get_class(end($this->stack)) . " object but according to n5 standard, this object cannot be associated with a MeetingFile");
            }
            $this->logger->trace('Updated a ' . get_class(end($this->stack)) . ' [' . end($this->stack) . ']');
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected Record found " . get_class(end($this->stack)));
        }
    }

    public function postProcessPrecedence()
    {
        if ($this->checkObjectClassTypeCorrect('Precedence') === true) {

            $parentObject = prev($this->stack);
            $precedence = end($this->stack);

            if (get_class($parentObject) === "CaseFile") {
                // Associate this Precedence with the parent CaseFile
                // CaseFile:Precedence is M:M
                // Two-way reference is set in addReferencePrecedence
                $parentObject->addReferencePrecedence($precedence);
                $this->logger->trace('Updated a ' . get_class(end($this->stack)) . ' [' . end($this->stack) . ']');
            } elseif (get_class($parentObject) === "RegistryEntry") {
                // Associate this Precedence with the parent RegistryEntry
                // RegistryEntry:Precedence is M:M
                // Two-way reference is set in addReferencePrecedence
                $parentObject->addReferencePrecedence($precedence);
                $this->logger->trace('Updated a ' . get_class(end($this->stack)) . ' [' . end($this->stack) . ']');
            } else {
                throw new Exception("Incorrect parent object to Precedence. Got " . get_class($parentObject), null, null);
            }
            $this->entityManager->flush();
            $this->logger->trace('Updated a ' . get_class(end($this->stack)) . ' [' . end($this->stack) . ']');
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected Record found " . get_class(end($this->stack)));
        }
    }

    public function postProcessRecord($classType)
    {
        if ($this->checkObjectClassTypeCorrect($classType) == true) {

            $parentObject = prev($this->stack);
            $record = end($this->stack);

            if ($parentObject instanceof series) {
                // Associate this record object with the current Series object
                $record->setReferenceSeries($parentObject);
                $this->logger->trace('In method ' . __METHOD__ . '. Associating record ' . $record . ' with serie ' . $parentObject);
            } elseif ($parentObject instanceof File) {
                // Associate this record object with the current File object
                $record->setReferenceFile($parentObject);
                // Doing this so I can call detach on all objects under File, inorder
                // to limit memory use
                $parentObject->addReferenceRecord($record);
                $this->logger->trace('In method ' . __METHOD__ . '. Associating record ' . $record . ' with file ' . $parentObject);
            } elseif ($parentObject instanceof Klass) {
                // Associate this record object with the current Klass object
                $record->addReferenceClass($parentObject);
                $this->logger->trace('In method ' . __METHOD__ . '. Associating record ' . $record . ' with class ' . $parentObject);
            } else {
                throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Found a record associated with a " . get_class(end($this->stack)) . " object but according to n5 standard, this object cannot be associated with a record");
            }
            $this->entityManager->flush();
            $this->logger->trace('Updated a ' . get_class(end($this->stack)) . ' [' . end($this->stack) . ']');
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected Record found " . get_class(end($this->stack)));
        }
    }

    public function postProcessScreening()
    {
        if ($this->checkObjectClassTypeCorrect("Screening") == true) {

            $parentObject = prev($this->stack);
            $screening = end($this->stack);

            if (get_class($parentObject) === 'Series') {
                $screening->setReferenceSeries($parentObject);
            } elseif (get_class($parentObject) === 'File') {
                $screening->setReferenceFile($parentObject);
            } elseif (get_class($parentObject) === 'Klass') {
                $screening->setReferenceClass($parentObject);
            } elseif (get_class($parentObject) === 'Record') {
                $screening->setReferenceRecord($parentObject);
            } elseif (get_class($parentObject) === 'DocumentDescription') {
                $screening->setReferenceDocumentDescription($parentObject);
            }

            $this->logger->trace('Updated a ' . get_class(end($this->stack)) . ' [' . end($this->stack) . ']');
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected Screening found " . get_class(end($this->stack)));
        }
    }

    public function postProcessSeries()
    {
        if ($this->checkObjectClassTypeCorrect("Series") == true) {
            $parentObject = prev($this->stack);
            $series = end($this->stack);
            // Associate this series with the current fonds object
            $series->setReferenceFonds($parentObject);
            $parentObject->addReferenceSeries($series);

            $this->logger->trace('Updated a ' . get_class(end($this->stack)) . ' [' . end($this->stack) . ']');
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected Series found " . get_class(end($this->stack)));
        }
    }

    public function postProcessSignOff()
    {
        if ($this->checkObjectClassTypeCorrect("SignOff") == true) {
            $parentObject = prev($this->stack);
            $signOff = end($this->stack);

            if (get_class($parentObject) === "RegistryEntry") {
                $parentObject->addSignOff($signOff);
            }

            $this->logger->trace('Updated a ' . get_class(end($this->stack)) . ' [' . end($this->stack) . ']');
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected SignOff found " . get_class(end($this->stack)));
        }
    }

    public function postProcessWorkflow()
    {}

    // Override
    protected function handleKeyword()
    {
        $keyword = new Keyword();
        $keyword->setKeyword($this->currentCdata);

        $currentObject = end($this->stack);

        if ($currentObject instanceof BasicRecord) {
            // Associate this keyword object with the current Record->BaiscRecord object
            $currentObject->addKeyword($keyword);
            $keyword->addReferenceBasicRecord($currentObject);

            $this->logger->trace('In method ' . __METHOD__ . '. Associating keyword ' . $keyword . ' with basicrecord ' . $currentObject);
        } elseif ($currentObject instanceof File) {
            // Associate this keyword object with the current File object
            $currentObject->addKeyword($keyword);
            // $keyword->addReferenceFile($this->currentFile);
            $this->logger->trace('In method ' . __METHOD__ . '. Associating keyword ' . $keyword . ' with File ' . $currentObject);
        } elseif ($currentObject instanceof Klass) {
            // Associate this keyword object with the current Klass object
            $currentObject->addKeyword($keyword);
            $keyword->addReferenceClass($currentObject);
            $this->logger->trace('In method ' . __METHOD__ . '. Associating keyword ' . $keyword . ' with class ' . $currentObject);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Found a keyword associated with a " . get_class(end($this->stack)) . " object but according to n5 standard, this object cannot be associated with a keyword");
        }

        $this->entityManager->persist($keyword);
        $this->entityManager->flush();

        $this->logger->trace('Merging updated keyword to database. Method (' . __METHOD__ . ')' . $keyword);
    }

    // Override
    protected function handleAuthor()
    {
        $author = new Author();
        $author->setauthor($this->currentCdata);

        $parentObject = prev($this->stack);
        $authorUsingObject = end($this->stack);

        if ($authorUsingObject instanceof BasicRecord) {
            // Associate this author object with the current Record->BasicRecord object
            $authorUsingObject->addAuthor($author);
            $this->logger->trace('In method ' . __METHOD__ . '. Associating author ' . $author . ' with basicrecord ' . $authorUsingObject);
        } elseif ($authorUsingObject instanceof DocumentDescription) {
            // Associate this author object with the current DocumentDescription
            $authorUsingObject->addAuthor($author);
            $this->logger->trace('In method ' . __METHOD__ . '. Associating author ' . $author . ' with documentDescription ' . $authorUsingObject);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Found a author associated with a " . get_class(end($this->stack)) . " object but according to n5 standard, this object cannot be associated with a author");
        }
        $this->entityManager->persist($author);
        $this->logger->trace('Persisting author to database. Method (' . __METHOD__ . ')' . $author);

        // $this->entityManager->merge($author);
        // $this->logger->trace('Merging updated author to database. Method (' . __METHOD__ . ')' . $author);
    }

    protected function handleStorageLocation()
    {
        // parent::handleStorageLocation();
        $storageLocation = null;
        $storageLocationData = $this->currentCdata;

        if (isset($storageLocationData) == true) {
            $storageLocation = $this->entityManager->getRepository('StorageLocation')->findOneBy(array(
                'storageLocation' => $storageLocationData
            ));
        } else {
            // empty element so do nothing
            return;
        }

        if (is_null($storageLocation) == true) {
            $storageLocation = new StorageLocation();
            $storageLocation->setStorageLocation($this->currentCdata);

            $this->entityManager->persist($storageLocation);
            // $this->entityManager->flush();
            $this->logger->trace('Persisting storageLocation to database. Method (' . __METHOD__ . ')' . $storageLocation);
        }

        $parentObject = prev($this->stack);
        $storageLocationUsingObject = end($this->stack);

        if ($storageLocationUsingObject instanceof DocumentDescription) {
            // Associate this storageLocation object with the current documentDescription object
            $parentObject->addReferenceStorageLocation($storageLocation);
            $storageLocation->addReferenceDocumentDescription($parentObject);
            $this->logger->trace('In method ' . __METHOD__ . '. Associating storageLocation ' . $storageLocation . ' with DocumentDescription ' . $parentObject);
        } elseif ($storageLocationUsingObject instanceof BasicRecord) {
            // Associate this storageLocation object with the current Record->BaiscRecord object
            $parentObject->addReferenceStorageLocation($storageLocation);
            $storageLocation->addReferenceBasicRecord($parentObject);
            $this->logger->trace('In method ' . __METHOD__ . '. Associating storageLocation ' . $storageLocation . ' with basicrecord ' . $parentObject);
        } elseif ($storageLocationUsingObject instanceof File) {
            // Associate this storageLocation object with the current File object
            $parentObject->addReferenceStorageLocation($storageLocation);
            $storageLocation->addReferenceFile($parentObject);
            $this->logger->trace('In method ' . __METHOD__ . '. Associating storageLocation ' . $storageLocation . ' with file ' . $parentObject);
        } elseif ($storageLocationUsingObject instanceof Series) {
            // Associate this storageLocation object with the current series object
            $parentObject->addReferenceStorageLocation($storageLocation);
            $storageLocation->addReferenceSeries($parentObject);
            $this->logger->trace('In method ' . __METHOD__ . '. Associating storageLocation ' . $storageLocation . ' with series ' . $parentObject);
        } elseif ($storageLocationUsingObject instanceof Fonds) {
            // Associate this storageLocation object with the current fonds object
            $storageLocation->addReferenceFonds($parentObject);
            $this->logger->trace('In method ' . __METHOD__ . '. Associating storageLocation ' . $storageLocation . ' with fonds ' . $parentObject);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Found a storage location associated with a " . get_class(end($this->stack)) . " object but according to n5 standard, this object cannot be associated with a storage location");
        }

        // $this->entityManager->merge($storageLocation);
        $this->entityManager->flush();
        $this->logger->trace('Merging updated storageLocation to database. Method (' . __METHOD__ . ')' . $storageLocation);
    }

    public function detachFileAndAllUnder($file)
    {

        // Get a list of all record objects associated with the file
        // and detach them. Similarily detach all docobjects/docdescriptions.
        // This should prevent the overuse of memory as doctrine maps
        // the entire file into memory ... i hope
        $allRecords = $file->getReferenceRecord();

        foreach ($allRecords as $record) {
            $allDocumentDescription = $record->getReferenceDocumentDescription();
            if (isset($allDocumentDescription)) {
                foreach ($allDocumentDescription as $documentDescription) {

                    $allAuthors = $documentDescription->getReferenceAuthor();
                    if (isset($allAuthors)) {
                        foreach ($allAuthors as $author) {
                            $this->entityManager->detach($author);
                        }
                    }

                    $allDocumentObject = $documentDescription->getReferenceDocumentObject();
                    if (isset($allDocumentObject)) {
                        foreach ($allDocumentObject as $documentObject) {
                            $this->entityManager->detach($documentObject);
                        }
                    }
                    $this->entityManager->detach($documentDescription);
                }
            }
            $allDocumentObject = $record->getReferenceDocumentObject();
            if (isset($allDocumentObject)) {
                foreach ($allDocumentObject as $documentObject) {
                    $this->entityManager->detach($documentObject);
                }
            }
            $this->entityManager->detach($record);
        }
        $this->entityManager->detach($file);
    }

    public function __toString()
    {
        return "id[" . $this->id . "], " . "systemId[" . $this->systemId . "] " . "title[" . $this->title . "] ";
    }
}

?>
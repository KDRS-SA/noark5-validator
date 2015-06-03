<?php
require_once ('handler/ArkivstrukturParser.php');

class ArkivstrukturDBImporter extends ArkivstrukturParser
{

    protected $entityManager;

    protected $currentClass = null;

    protected $currentClassificationsystem = null;

    protected $currentDocumentDescription = null;

    protected $currentFile = null;

    protected $currentFonds = null;

    protected $currentFondsCreator = null;

    protected $currentRecord = null;

    protected $currentSeries = null;

    protected $currentSignOff = null;

    /*
     * //TODO : You have to handle
     * referanseArkivdel
     *
     * How does the contains work, can you set equals ??? Use find byone
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
     *
     */
    public function __construct($directory, $entityManager, $onlyParse)
    {
        $this->entityManager = $entityManager;
        parent::__construct($directory, null, null, $onlyParse);
    }

    public function preProcessClassificationSystem()
    {
        if ($this->checkObjectClassTypeCorrect("ClassificationSystem") == true) {
            $classificationSystem = end($this->stack);
            $this->currentClassificationsystem = $classificationSystem;
            $this->entityManager->persist($classificationSystem);
            $this->entityManager->flush();
            $this->currentClassificationsystem = $classificationSystem;
            $this->logger->trace('Persisting initial ClassificationSystem to database. Method (' . __METHOD__ . ')' . $classificationSystem);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . " Expected ClassificationSystem, found " . get_class(end($this->stack)));
        }
    }

    public function preProcessClass()
    {
        if ($this->checkObjectClassTypeCorrect("Klass") == true) {
            $klass = end($this->stack);
            $this->currentClass = $klass;
            $this->entityManager->persist($klass);
            $this->entityManager->flush();
            $this->logger->trace('Persisting initial class to database. Method (' . __METHOD__ . ')' . $klass);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . " Expected Class, found " . get_class(end($this->stack)));
        }
    }

    public function preProcessCorrespondencePart()
    {
        if ($this->checkObjectClassTypeCorrect("CorrespondencePart") == true) {
            $correspondencePart = end($this->stack);
            $this->entityManager->persist($correspondencePart);
            $this->entityManager->flush();
            $this->logger->trace('Persisting initial correspondencePart to database. Method (' . __METHOD__ . ')' . $correspondencePart);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . " Expected CorrespondencePart, found " . get_class(end($this->stack)));
        }
    }

    public function preProcessDocumentDescription()
    {
        if ($this->checkObjectClassTypeCorrect("DocumentDescription") == true) {
            $documentDescription = end($this->stack);
            $this->currentDocumentDescription = $documentDescription;
            $this->entityManager->persist($documentDescription);
            $this->entityManager->flush();
            $this->logger->trace('Persisting initial documentDescription to database. Method (' . __METHOD__ . ')' . $documentDescription);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . " Expected DocumentDescription, found " . get_class(end($this->stack)));
        }
    }

    public function preProcessDocumentObject()
    {
        if ($this->checkObjectClassTypeCorrect("DocumentObject") == true) {
            $documentObject = end($this->stack);
            $this->entityManager->persist($documentObject);
            $this->entityManager->flush();
            $this->logger->trace('Persisting initial documentObject to database. Method (' . __METHOD__ . ')' . $documentObject);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . " Expected DocumentObject, found " . get_class(end($this->stack)));
        }
    }

    public function preProcessFile($classType)
    {
        if ($this->checkObjectClassTypeCorrect($classType) == true) {
            $file = end($this->stack);
            $file->setReferenceSeries($this->currentSeries);
            $this->entityManager->persist($file);
            $this->entityManager->flush();
            $this->currentFile = $file;
            $this->logger->trace('Persisting initial file to database. Method (' . __METHOD__ . ')' . $file);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . " Expected File, found " . get_class(end($this->stack)));
        }
    }

    public function preProcessFonds()
    {
        if ($this->checkObjectClassTypeCorrect("Fonds") == true) {
            $fonds = end($this->stack);
            $this->entityManager->persist($fonds);
            // $this->entityManager->flush();
            $this->currentFonds = $fonds;
            $this->logger->trace('Persisting initial fonds to database. Method (' . __METHOD__ . ')' . $fonds);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . " Expected Fonds, found " . get_class(end($this->stack)));
        }
    }

    public function preProcessFondsCreator()
    {
        if ($this->checkObjectClassTypeCorrect("FondsCreator") == true) {
            $fondsCreator = end($this->stack);
            $this->entityManager->persist($fondsCreator);
            $this->entityManager->flush();
            $this->currentFondsCreator = $fondsCreator;
            $this->logger->trace('Persisting initial fondsCreator to database. Method (' . __METHOD__ . ')' . $fondsCreator);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . " Expected FondsCreator, found " . get_class(end($this->stack)));
        }
    }

    public function preProcessRecord($classType)
    {
        if ($this->checkObjectClassTypeCorrect($classType) == true) {
            $record = end($this->stack);
            $this->entityManager->persist($record);
            $this->entityManager->flush();
            $this->currentRecord = $record;
            $this->logger->trace('Persisting initial record to database. Method (' . __METHOD__ . ')' . $record);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . " Expected Record, found " . get_class(end($this->stack)));
        }
    }

    public function preProcessSeries()
    {
        if ($this->checkObjectClassTypeCorrect("Series") == true) {
            $series = end($this->stack);
            $series->setReferenceFonds($this->currentFonds);
            $this->entityManager->persist($series);
            $this->entityManager->flush();
            $this->currentSeries = $series;
            $this->logger->trace('Persisting initial series to database. Method (' . __METHOD__ . ')' . $series);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . " Expected Series, found " . get_class(end($this->stack)));
        }
    }

    public function preProcessSignOff()
    {
        if ($this->checkObjectClassTypeCorrect("SignOff") == true) {
            $signOff = end($this->stack);
            $this->entityManager->persist($signOff);
            // $this->entityManager->flush();
            $this->currentSignOff = $signOff;
            $this->logger->trace('Persisting initial signOff to database. Method (' . __METHOD__ . ')' . $signOff);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . " Expected SignOff, found " . get_class(end($this->stack)));
        }
    }

    public function postProcessClassificationSystem()
    {
        if ($this->checkObjectClassTypeCorrect("ClassificationSystem") == true) {
            $classificationSystem = end($this->stack);

            // Associate this series with the current classificationSystem object
            // TODO: Note this code is not tested!
            $classificationSystem->addReferenceSeries($this->currentSeries);
            $this->currentSeries->addReferenceClassificationSystem($classificationSystem);

            // $this->entityManager->merge($classificationSystem);
            $this->entityManager->flush();
            $this->logger->trace('Merging updated classificationSystem to database. Method (' . __METHOD__ . ')' . $classificationSystem);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected ClassificationSystem found " . get_class(end($this->stack)));
        }
    }

    public function postProcessClass()
    {
        if ($this->checkObjectClassTypeCorrect("Class") == true) {

            $parentObject = prev($this->stack);
            $klass = end($this->stack);

            // Associate this class with the current classificationSystem object
            $klass->addReferenceClassificationSystem($this->currentClassificationSystem);

            // If there is a Class object above me, then I am a child Class
            if (get_class($parentObject) === "Klass") {
                $this->setReferenceParentClass($parentObject);
                $parentObject->addReferenceChildClass($klass);
            } else {
                throw new Exception("Unknown parent object to Class. Got " . get_class($parentObject), null, null);
            }

            $this->entityManager->merge($klass);
            $this->entityManager->flush();
            $this->logger->trace('Merging updated class to database. Method (' . __METHOD__ . ')' . $klass);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected Class found " . get_class(end($this->stack)));
        }
    }

    public function postProcessCorrespondencePart()
    {
        if ($this->checkObjectClassTypeCorrect("CorrespondencePart") == true) {
            $correspondencePart = end($this->stack);

            // Associate this correspondencePart with the current registryEntry object
            $correspondencePart->addRecord($this->currentRecord);
            //$this->currentRecord->addCorrespondencePart($correspondencePart);

            $this->entityManager->merge($correspondencePart);
            $this->logger->trace('Merging updated correspondencePart to database. Method (' . __METHOD__ . ')' . $correspondencePart);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected CorrespondencePart found " . get_class(end($this->stack)));
        }
    }

    public function postProcessDocumentDescription()
    {
        if ($this->checkObjectClassTypeCorrect("DocumentDescription") == true) {
            $documentDescription = end($this->stack);

            // Associate this documentDescription with the current Record object
            $documentDescription->addReferenceRecord($this->currentRecord);

            // Associate the current Record object with this documentDescription
            // It is not required to get the association correct in the db
            // but needed for the dettach
            $this->currentRecord->addReferenceDocumentDescription($documentDescription);

            $this->entityManager->merge($documentDescription);
            $this->logger->trace('Merging updated documentDescription to database. Method (' . __METHOD__ . ')' . $documentDescription);
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
            } else
                if ($parentObject instanceof $this->currentRecord) {
                    // Associate this documentObject with the current Record object
                    $documentObject->setReferencerecord($parentObject);
                    $parentObject->addReferenceDocumentObject($documentObject);
                } else {
                    throw new Exception("Unknown parent object to DocumentObject. Got " . get_class($parentObject), null, null);
                }

            $this->entityManager->merge($documentObject);
            $this->logger->trace('Merging updated documentObject to database. Method (' . __METHOD__ . ')' . $documentObject);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected DocumentObject found " . get_class(end($this->stack)));
        }
    }

    public function postProcessFile($classType)
    {
        if ($this->checkObjectClassTypeCorrect($classType) == true) {

            $parentObject = prev($this->stack);
            $file = end($this->stack);

            // If there is a File object above me, then I am a child File
            if (get_class($parentObject) === "File") {
                $this->setReferenceParentFile($parentObject);
                $parentObject->addReferenceChildFile($file);
            } else
                if (get_class($parentObject) === "Klass") {
                    // Associate this file with the current Klass object
                    $file->setReferenceClass($parentObject);
                } else
                    if (get_class($parentObject) === "Series") {
                        // Associate this file with the current series object
                        $file->setReferenceSeries($parentObject);
                    } else {
                        throw new Exception("Unknown parent object to DocumentObject. Got " . get_class($parentObject), null, null);
                    }

            $this->entityManager->merge($file);
            $this->entityManager->flush();
            $this->detachFileAndAllUnder($file);
            $this->logger->trace('Merging updated file to database. Method (' . __METHOD__ . ')' . $file);
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
            } else
                if ($parentObject != false) {
                    throw new Exception("Unknown parent object to Fonds. Got " . get_class($parentObject), null, null);
                }

            // $this->entityManager->merge($fonds);
            // $this->entityManager->flush();
            $this->logger->trace('Merging updated fonds to database. Method (' . __METHOD__ . ')' . $fonds);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected Fonds found " . get_class(end($this->stack)));
        }
    }

    public function postProcessFondsCreator()
    {
        if ($this->checkObjectClassTypeCorrect("FondsCreator") == true) {
            $fondsCreator = end($this->stack);

            // Associate this fondsCreator with the current fonds object
            $fondsCreator->addReferenceFonds($this->currentFonds);
            // $this->currentFonds->addReferenceFondsCreator($fondsCreator);

            // $this->entityManager->merge($fondsCreator);
            // $this->entityManager->flush();
            $this->logger->trace('Merging updated fondsCreator to database. Method (' . __METHOD__ . ')' . $fondsCreator);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected FondsCreator found " . get_class(end($this->stack)));
        }
    }

    public function postProcessRecord($classType)
    {
        if ($this->checkObjectClassTypeCorrect($classType) == true) {

            $parentObject = prev($this->stack);
            $record = end($this->stack);

            if ($parentObject instanceof series) {
                // Associate this record object with the current Series object
                $record->setReferenceSeries($this->currentSeries);
                $this->logger->trace('In method ' . __METHOD__ . '. Associating record ' . $record . ' with serie ' . $this->currentSeries);
            } else
                if ($parentObject instanceof File) {
                    // Associate this record object with the current File object
                    $record->setReferenceFile($this->currentFile);
                    // Doing this so I can call detach on all objects under File, inorder
                    // to limit memory use
                    $this->currentFile->addReferenceRecord($record);
                    $this->logger->trace('In method ' . __METHOD__ . '. Associating record ' . $record . ' with file ' . $this->currentFile);
                } else
                    if ($parentObject instanceof Klass) {
                        // Associate this record object with the current Klass object
                        $record->addReferenceClass($this->currentClass);
                        $this->logger->trace('In method ' . __METHOD__ . '. Associating record ' . $record . ' with class ' . $this->currentClass);
                    } else {
                        throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Found a record associated with a " . get_class(end($this->stack)) . " object but according to n5 standard, this object cannot be associated with a record");
                    }
            $this->entityManager->merge($record);

            $this->logger->trace('Merging updated record to database. Method (' . __METHOD__ . ')' . $record);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected Record found " . get_class(end($this->stack)));
        }
    }

    public function postProcessSeries()
    {
        if ($this->checkObjectClassTypeCorrect("Series") == true) {
            $series = end($this->stack);

            // Associate this series with the current fonds object
            $series->setReferenceFonds($this->currentFonds);
            $this->currentFonds->addReferenceSeries($series);

            $this->entityManager->merge($series);
            $this->entityManager->flush();
            $this->logger->trace('Merging updated series to database. Method (' . __METHOD__ . ')' . $series);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected Series found " . get_class(end($this->stack)));
        }
    }

    public function postProcessSignOff()
    {
        if ($this->checkObjectClassTypeCorrect("SignOff") == true) {
            $signOff = end($this->stack);

            if (get_class($this->currentRecord) === "RegistryEntry") {
                // Associate this signoff object with the current Record->RegistryEntry object
                $this->currentRecord->addSignOff($signOff);
            }

            $this->entityManager->merge($signOff);
            $this->entityManager->flush();
            $this->logger->trace('Merging updated signOff to database. Method (' . __METHOD__ . ')' . $signOff);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected SignOff found " . get_class(end($this->stack)));
        }
    }

    // Override
    protected function handleKeyword()
    {
        $keyword = new Keyword();
        $keyword->setKeyword($this->currentCdata);

        $this->entityManager->persist($keyword);
        $this->entityManager->flush();
        $this->logger->trace('Persisting keyword to database. Method (' . __METHOD__ . ')' . $keyword);

        $keywordUsingObject = end($this->stack);

        if ($keywordUsingObject instanceof BasicRecord) {
            // Associate this keyword object with the current Record->BaiscRecord object
            $this->currentRecord->addKeyword($keyword);
            $keyword->addReferenceBasicRecord($this->currentRecord);
            $this->logger->trace('In method ' . __METHOD__ . '. Associating keyword ' . $keyword . ' with basicrecord ' . $this->currentRecord);
        } else
            if ($keywordUsingObject instanceof File) {
                // Associate this keyword object with the current File object
                $this->currentFile->addKeyword($keyword);
                $keyword->addReferenceFile($this->currentFile);
                $this->logger->trace('In method ' . __METHOD__ . '. Associating keyword ' . $keyword . ' with file ' . $this->currentFile);
            } else
                if ($keywordUsingObject instanceof Klass) {
                    // Associate this keyword object with the current Klass object
                    $this->currentClass->addKeyword($keyword);
                    $keyword->addReferenceClass($this->currentClass);
                    $this->logger->trace('In method ' . __METHOD__ . '. Associating keyword ' . $keyword . ' with class ' . $this->currentRecord);
                } else {
                    throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Found a keyword associated with a " . get_class(end($this->stack)) . " object but according to n5 standard, this object cannot be associated with a keyword");
                }

        $this->entityManager->merge($keyword);
        $this->entityManager->flush();
        $this->logger->trace('Merging updated keyword to database. Method (' . __METHOD__ . ')' . $keyword);
    }


    // Override
    protected function handleAuthor()
    {
        $author = new Author();
        $author->setauthor($this->currentCdata);


        $authorUsingObject = end($this->stack);

        if ($authorUsingObject instanceof BasicRecord) {
            // Associate this author object with the current Record->BasicRecord object
            $this->currentRecord->addAuthor($author);
            $this->logger->trace('In method ' . __METHOD__ . '. Associating author ' . $author . ' with basicrecord ' . $this->currentRecord);
        }
        else if ($authorUsingObject instanceof DocumentDescription) {
            // Associate this author object with the current DocumentDescription
            $this->currentDocumentDescription->addAuthor($author);
            $this->logger->trace('In method ' . __METHOD__ . '. Associating author ' . $author . ' with documentDescription ' . $this->currentDocumentDescription);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Found a author associated with a " . get_class(end($this->stack)) . " object but according to n5 standard, this object cannot be associated with a author");
        }
        $this->entityManager->persist($author);
        $this->logger->trace('Persisting author to database. Method (' . __METHOD__ . ')' . $author);

        //$this->entityManager->merge($author);
        //$this->logger->trace('Merging updated author to database. Method (' . __METHOD__ . ')' . $author);
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

        $storageLocationUsingObject = end($this->stack);

        if ($storageLocationUsingObject instanceof DocumentDescription) {
            // Associate this storageLocation object with the current documentDescription object
            $this->currentDocumentDescription->addReferenceStorageLocation($storageLocation);
            $storageLocation->addReferenceDocumentDescription($this->currentDocumentDescription);
            $this->logger->trace('In method ' . __METHOD__ . '. Associating storageLocation ' . $storageLocation . ' with DocumentDescription ' . $this->currentDocumentDescription);
        } else
            if ($storageLocationUsingObject instanceof BasicRecord) {
                // Associate this storageLocation object with the current Record->BaiscRecord object
                $this->currentRecord->addReferenceStorageLocation($storageLocation);
                $storageLocation->addReferenceBasicRecord($this->currentRecord);
                $this->logger->trace('In method ' . __METHOD__ . '. Associating storageLocation ' . $storageLocation . ' with basicrecord ' . $this->currentRecord);
            } else
                if ($storageLocationUsingObject instanceof File) {
                    // Associate this storageLocation object with the current File object
                    $this->currentFile->addReferenceStorageLocation($storageLocation);
                    $storageLocation->addReferenceFile($this->currentFile);
                    $this->logger->trace('In method ' . __METHOD__ . '. Associating storageLocation ' . $storageLocation . ' with file ' . $this->currentFile);
                } else
                    if ($storageLocationUsingObject instanceof Series) {
                        // Associate this storageLocation object with the current series object
                        $this->currentSeries->addReferenceStorageLocation($storageLocation);
                        $storageLocation->addReferenceSeries($this->currentSeries);
                        $this->logger->trace('In method ' . __METHOD__ . '. Associating storageLocation ' . $storageLocation . ' with series ' . $this->currentSeries);
                    } else
                        if ($storageLocationUsingObject instanceof Fonds) {
                            // Associate this storageLocation object with the current fonds object
                            $storageLocation->addReferenceFonds($this->currentFonds);
                            $this->logger->trace('In method ' . __METHOD__ . '. Associating storageLocation ' . $storageLocation . ' with fonds ' . $this->currentFonds);
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

}

?>
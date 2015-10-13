<?php
require_once ('handler/ArkivstrukturParser.php');

class ArkivstrukturDBImporter extends ArkivstrukturParser
{


    public function __construct($directory, $onlyParse)
    {
        parent::__construct($directory, null, null, $onlyParse);
    }

    public function preProcessClassificationSystem()
    {
        if ($this->checkObjectClassTypeCorrect("ClassificationSystem") == true) {
            $classificationSystem = end($this->stack);
            $this->currentClassificationsystem = $classificationSystem;
            $this->logger->trace('Preprocess ClassificationSystem. Method (' . __METHOD__ . ')' . $classificationSystem);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . " Expected ClassificationSystem, found " . get_class(end($this->stack)));
        }
    }

    public function preProcessClass()
    {
        if ($this->checkObjectClassTypeCorrect("Klass") == true) {
            $klass = end($this->stack);
            $this->currentClass = $klass;

            $this->logger->trace('Preprocess Class. Method (' . __METHOD__ . ')' . $klass);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . " Expected Class, found " . get_class(end($this->stack)));
        }
    }

    public function preProcessCorrespondencePart()
    {
        if ($this->checkObjectClassTypeCorrect("CorrespondencePart") == true) {
            $correspondencePart = end($this->stack);
            $this->logger->trace('Preprocess . Method (' . __METHOD__ . ')' . $correspondencePart);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . " Expected CorrespondencePart, found " . get_class(end($this->stack)));
        }
    }

    public function preProcessDocumentDescription()
    {
        if ($this->checkObjectClassTypeCorrect("DocumentDescription") == true) {
            $documentDescription = end($this->stack);
            $this->currentDocumentDescription = $documentDescription;
            $this->logger->trace('Preprocess . Method (' . __METHOD__ . ')' . $documentDescription);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . " Expected DocumentDescription, found " . get_class(end($this->stack)));
        }
    }

    public function preProcessDocumentObject()
    {
        if ($this->checkObjectClassTypeCorrect("DocumentObject") == true) {
            $documentObject = end($this->stack);
            $this->logger->trace('Preprocess . Method (' . __METHOD__ . ')' . $documentObject);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . " Expected DocumentObject, found " . get_class(end($this->stack)));
        }
    }

    public function preProcessFile($classType)
    {
        if ($this->checkObjectClassTypeCorrect($classType) == true) {
            $file = end($this->stack);
            $this->currentFile = $file;
            $this->logger->trace('Preprocess . Method (' . __METHOD__ . ')' . $file);
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
            $this->logger->trace('Preprocess . Method (' . __METHOD__ . ')' . $fonds);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . " Expected Fonds, found " . get_class(end($this->stack)));
        }
    }

    public function preProcessFondsCreator()
    {
        if ($this->checkObjectClassTypeCorrect("FondsCreator") == true) {
            $fondsCreator = end($this->stack);
            $this->currentFondsCreator = $fondsCreator;
            $this->logger->trace('Preprocess . Method (' . __METHOD__ . ')' . $fondsCreator);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . " Expected FondsCreator, found " . get_class(end($this->stack)));
        }
    }

    public function preProcessRecord($classType)
    {
        if ($this->checkObjectClassTypeCorrect($classType) == true) {
            $record = end($this->stack);
            $this->currentRecord = $record;
            $this->logger->trace('Preprocess . Method (' . __METHOD__ . ')' . $record);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . " Expected Record, found " . get_class(end($this->stack)));
        }
    }

    public function preProcessSeries()
    {
        if ($this->checkObjectClassTypeCorrect("Series") == true) {
            $series = end($this->stack);
            $this->currentSeries = $series;
            $this->logger->trace('Preprocess . Method (' . __METHOD__ . ')' . $series);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . " Expected Series, found " . get_class(end($this->stack)));
        }
    }

    public function preProcessSignOff()
    {
        if ($this->checkObjectClassTypeCorrect("SignOff") == true) {
            $signOff = end($this->stack);
            $this->currentSignOff = $signOff;

            $this->logger->trace('Preprocess. Method (' . __METHOD__ . ')' . $signOff);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . " Expected SignOff, found " . get_class(end($this->stack)));
        }
    }

    public function postProcessClassificationSystem()
    {
        if ($this->checkObjectClassTypeCorrect("ClassificationSystem") == true) {
            $classificationSystem = end($this->stack);
            $this->logger->trace('Post process ClassificationSystem . Method (' . __METHOD__ . ')' . $classificationSystem);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected ClassificationSystem found " . get_class(end($this->stack)));
        }
    }

    public function postProcessClass()
    {
        if ($this->checkObjectClassTypeCorrect("Class") == true) {

            $klass = end($this->stack);
            $this->logger->trace('Post process Class. Method (' . __METHOD__ . ')' . $klass);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected Class found " . get_class(end($this->stack)));
        }
    }

    public function postProcessCorrespondencePart()
    {
        if ($this->checkObjectClassTypeCorrect("CorrespondencePart") == true) {
            $correspondencePart = end($this->stack);
            $this->logger->trace('Post process CorrespondencePart. Method (' . __METHOD__ . ')' . $correspondencePart);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected CorrespondencePart found " . get_class(end($this->stack)));
        }
    }

    public function postProcessDocumentDescription()
    {
        if ($this->checkObjectClassTypeCorrect("DocumentDescription") == true) {
            $documentDescription = end($this->stack);
            $this->logger->trace('Post process DocumentDescription. Method (' . __METHOD__ . ')' . $documentDescription);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected DocumentDescription found " . get_class(end($this->stack)));
        }
    }

    public function postProcessDocumentObject()
    {
        if ($this->checkObjectClassTypeCorrect("DocumentObject") == true) {
            $documentObject = end($this->stack);
            $this->logger->trace('Post process DocumentObject. Method (' . __METHOD__ . ')' . $documentObject);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected DocumentObject found " . get_class(end($this->stack)));
        }
    }

    public function postProcessFile($classType)
    {
        if ($this->checkObjectClassTypeCorrect($classType) == true) {
            $file = end($this->stack);
            $this->logger->trace('Post process File. Method (' . __METHOD__ . ')' . $file);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected File found " . get_class(end($this->stack)));
        }
    }

    public function postProcessFonds()
    {
        if ($this->checkObjectClassTypeCorrect("Fonds") == true) {
            $fonds = end($this->stack);
            $this->logger->trace('Post process Fonds. Method (' . __METHOD__ . ')' . $fonds);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected Fonds found " . get_class(end($this->stack)));
        }
    }

    public function postProcessFondsCreator()
    {
        if ($this->checkObjectClassTypeCorrect("FondsCreator") == true) {
            $fondsCreator = end($this->stack);
            $this->logger->trace('Post process FondsCreator. Method (' . __METHOD__ . ')' . $fondsCreator);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected FondsCreator found " . get_class(end($this->stack)));
        }
    }

    public function postProcessRecord($classType)
    {
        if ($this->checkObjectClassTypeCorrect($classType) == true) {
            $record = end($this->stack);
            $this->logger->trace('Post process Record. Method (' . __METHOD__ . ')' . $record);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected Record found " . get_class(end($this->stack)));
        }
    }

    public function postProcessSeries()
    {
        if ($this->checkObjectClassTypeCorrect("Series") == true) {
            $series = end($this->stack);
            $this->logger->trace('Post process Series. Method (' . __METHOD__ . ')' . $series);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected Series found " . get_class(end($this->stack)));
        }
    }

    public function postProcessSignOff()
    {
        if ($this->checkObjectClassTypeCorrect("SignOff") == true) {
            $signOff = end($this->stack);
            $this->logger->trace('Post process SignOff. Method (' . __METHOD__ . ')' . $signOff);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected SignOff found " . get_class(end($this->stack)));
        }
    }

    // Override
    protected function handleKeyword()
    {
        $this->logger->trace('Post process keyword. Method (' . __METHOD__ . ')' . $keyword);
    }


    // Override
    protected function handleAuthor()
    {
        $this->logger->trace('Post process Author. Method (' . __METHOD__ . ')' . $author);
    }

    // Override
    protected function handleStorageLocation()
    {
            $this->logger->trace('Post process StorageLocation . Method (' . __METHOD__ . ')' . $storageLocation);
    }

}

?>
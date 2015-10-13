<?php

class DocumentListHandler
{
    protected $fileList;
    protected $fileListDuplicates;
    protected $fileListNotReferenced;
    protected $errorsFound = false;

    public function __construct($directory) {
        $this->directory = $directory;
        $this->fileList = array();
        $this->fileListDuplicates = array();
        $this->fileListNotReferenced = array();
    }

    public function add($filename) {

        $fileToAdd = $filename;
        $retVal = false;

        if (isset($this->fileList[$fileToAdd]) == false) {
            $this->fileList[$fileToAdd] = 1;
        }
        else {
            $this->fileList[$fileToAdd]++;
            $this->fileListDuplicates[$fileToAdd] = 1;
            $this->errorsFound = true;
            $retVal = true;
        }
        return $retVal;
    }

    public function remove($filename) {

        $fileToRemove = ltrim(strrchr ($filename, '/'), '/');// $filename;

        if (isset($this->fileList[$fileToRemove]) == true) {
            unset($this->fileList[$fileToRemove]);
        }
        else {
            $this->errorsFound = true;
            if (isset($this->fileListNotReferenced[$fileToRemove]) == true) {
                $this->fileListNotReferenced[$fileToRemove]++;
            }
            else {
                $this->fileListNotReferenced[$fileToRemove] = 1;
            }
        }
    }

    public function getRemaining() {
        return $this->fileList;
    }

    public function getDuplicates() {
        return $this->fileListDuplicates;
    }

    public function getNotReferenced() {
        return $this->fileListNotReferenced;
    }

    public function anyErrors() {
        return $this->errorsFound;
    }
}

?>
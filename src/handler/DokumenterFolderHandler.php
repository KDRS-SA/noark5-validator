<?php

class DokumenterFolderHandler {

    protected $rootDirectory;
    // Just here in case the recursive call goes wrong or something seems strange
    // We wouldn't expect any real depth here, maybe max 3 or 4 subfolders
    protected $maxDepth;
    protected $fileList;
    protected $numberOfUniqueFiles;
    protected $numberOfFiles;
    protected $documentListHandler;

    public function __construct($rootDirectory, $maxDepth, $documentListHandler) {
        $this->rootDirectory = $rootDirectory;
        $this->maxDepth = $maxDepth;
        $this->documentListHandler = $documentListHandler;
        $this->numberOfUniqueFiles = 0;
        $this->numberOfFiles = 0;
    }

    public function process() {
        $this->processDirectory($this->rootDirectory, $this->maxDepth);
    }

    public function getNumberOfUniqueFiles() {
        return $this->numberOfUniqueFiles;
    }

    public function getNumberOfFiles() {
        return $this->numberOfFiles;
    }

    private function processDirectory($directory, $maxDepth)
    {
        $directoryHandle = opendir($directory);

        while ($currentFileOrDirectory = readdir($directoryHandle)) {
            if ($currentFileOrDirectory == '.' || $currentFileOrDirectory == '..') {
                continue;
            }

            $subDirectory = "$directory/$currentFileOrDirectory";
            if (is_dir($subDirectory)) {
                if ($maxDepth != 0) {
                    $this->processDirectory(processDirectory, $maxDepth - 1);
                }
            } else {
                $duplicateFound = $this->documentListHandler->add($currentFileOrDirectory);
                if ($duplicateFound == true) {
                    $this->numberOfFiles++;
                }
                else {
                    $this->numberOfUniqueFiles++;
                    $this->numberOfFiles++;
                }
            }
        }
        closedir($directoryHandle);
    }
}
?>

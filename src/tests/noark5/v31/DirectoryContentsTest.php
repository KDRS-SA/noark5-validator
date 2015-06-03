<?php
namespace tests\noark5\v31;

require_once ('src/tests/Test.php');

/**
 *
 * @author tsodring
 *
 */
class DirectoryContentsTest extends \Test
{

    /**
     *
     * @param
     *            $testName
     *
     * @param
     *            $testProperty
     *
     */
    public function __construct($testName, $testProperty)
    {
        parent::__construct($testName, $testProperty);
    }

    public function runTest () {

        $hashOfFile =  hash_file($this->checksumAlgorithm, join(DIRECTORY_SEPARATOR, array($this->directory, $this->fileName)));
        if (strcasecmp($this->checksumValue , $hashOfFile) == 0) {
            $this->testProperty->addTestResult(true);
            $this->testProperty->addTestResultDescription('The file '. $this->fileName . ' checksum is correct');
        }
        else {
            $this->testProperty->addTestResult(false);
            $this->testProperty->addTestResultDescription('The file '. $this->fileName . ' checksum is not correct. ' .
                'Original checksum is (' . $this->checksumValue . '). Computed checksum is (' . $hashOfFile .')'
            );
        }
    }

}

?>
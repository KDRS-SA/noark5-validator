<?php
require_once ('handler/ArkivstrukturParser.php');

/*
 * This class uses ArkivstrukturParser and picks out every journalpost->recordType
 * and if the recordType is an incoming document, it adds it to a list of
 * $listOfRegistryEntryIncoming all incoming registryEntry.
 *
 * The parser continues and if when a signOff is encountered
 *
 */

class AllIncomingRegistryEntrySignedOff extends ArkivstrukturParser
{
    protected $listOfRegistryEntryIncoming = array();
    protected $listOfSignOffThatAreWrong = array();
    protected $numberIncomingRegistryEntryfound = 0;
    protected $processedRecord = null;

    public function __construct()
    {
        parent::__construct();
        $this->listOfRegistryEntryIncoming =  array();
    }

    // override
    // Luckily RegistryEntryType comes after RegistryEntryStatus
    public function handleRecordStatus() {
        parent::handleRecordStatus();
        $object = end($this->stack);
        $recordType = $object->getRegistryEntryType();

        if (isset($recordType) &&
                $recordType === Constants::REGISTRY_ENTRY_TYPE_INCOMING ) {
                    $this->listOfRegistryEntryIncoming[$object->getSystemId()] = $object;
                    $this->numberIncomingRegistryEntryfound++;
        }
    }

    // override
    public function postProcessSignOff()
    {
        if ($this->checkObjectClassTypeCorrect("SignOff") == true) {
            $signOff = end($this->stack);

            // get the previous object of the stack
            $currentRecord = prev($this->stack);
            // reset stack pointer to end
            end($this->stack);

            // Signing off of the wrong type of record, this is an error! Log it and continue
            if ($currentRecord->getRegistryEntryType() !== Constants::REGISTRY_ENTRY_TYPE_INCOMING) {
                $this->logger->warn('The following RegistryEntry has been signedOff, but it is not specified as Incoming (' .
                     Constants.REGISTRY_ENTRY_TYPE_INCOMING . '). The actual RegistryEntry is '. $currentRecord .
                    ' while the signOff is ' .  $signOff . ' This message is coming from ' . __METHOD__ );
                $this->errorsEncountered = true;
                $this->numberErrorsEncountered++;
            }

            if (isset($this->listOfRegistryEntryIncoming[$currentRecord->getSystemId()])) {
                unset($this->listOfRegistryEntryIncoming[$currentRecord->getSystemId()]);
            }
            else {
                $this->logger->warn('Encountered a sigoff for the following RegistryEntry, but am not expecting this RegistryEntry. The actual RegistryEntry is '. $currentRecord .
                                        ' while the signOff is ' .  $signOff . ' This message is coming from ' . __METHOD__ );
                $this->errorsEncountered = true;
                $this->numberErrorsEncountered++;
            }

            $this->logger->trace('Post process SignOff. Method (' . __METHOD__ . ')' . $signOff);
        } else {
            throw new Exception(Constants::STACK_ERROR . __METHOD__ . ". Expected SignOff found " . get_class(end($this->stack)));
        }
    }

    public function getNumberIncomingRegistryEntryfound() {
        return $this->numberIncomingRegistryEntryfound;
    }

    /**
     * This function checks to see how many elements remain in
     * $this->listOfRegistryEntryIncoming. Sets error if there are elements,
     * and prints to info whatever is left.
     */

    public function testOver() {
        foreach ($this->listOfRegistryEntryIncoming as $registryEntry) {
            $this->logger->warn('The following RegistryEntry is not signed off ' . $registryEntry);
            $this->errorsEncountered = true;
            $this->numberErrorsEncountered++;
        }
    }
}

?>
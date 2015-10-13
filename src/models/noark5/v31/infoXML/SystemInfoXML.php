<?php


class SystemInfoXML
{
    protected $systemType;
    protected $version;
    protected $systemName;
    protected $systemVersion;

    function __construct() {
    }

    public function getSystemType()
    {
        return $this->systemType;
    }

    public function setSystemType($systemType)
    {
        $this->systemType = $systemType;
        return $this;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    public function getSystemName()
    {
        return $this->systemName;
    }

    public function setSystemName($systemName)
    {
        $this->systemName = $systemName;
        return $this;
    }

    public function getSystemVersion()
    {
        return $this->systemVersion;
    }

    public function setSystemVersion($systemVersion)
    {
        $this->systemVersion = $systemVersion;
        return $this;
    }

    public function __toString()
    {
        return 'systemType (' . $this->systemType . '), version (' . $this->version .'), systemName (' . $this->systemName . '), systemVersion (' . $this->systemVersion . ')';
    }
}

?>
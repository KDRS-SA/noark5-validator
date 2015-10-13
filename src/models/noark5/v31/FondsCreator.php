<?php

use Doctrine\Common\Collections\ArrayCollection;
require_once ('models/noark5/v31/Fonds.php');

/**
 * @Entity @Table(name="fonds_creator")
 **/
class FondsCreator
{

    /** @Id @Column(type="bigint", name="pk_fonds_creator_id", nullable=false) @GeneratedValue **/
    protected $id;

    /** M001 - systemID (xs:string) */
    /**  @Column(type="string", name="system_id", nullable=true) **/
    protected $systemId;

    /** M006 - arkivskaperID (xs:string) */
    /**  @Column(type="string", name="fonds_creator_name", nullable=true) **/
    protected $fondsCreatorName;

    /** M023 - arkivskaperNavn (xs:string) */
    /**  @Column(type="string", name="fonds_creator_id", nullable=true) **/
    protected $fondsCreatorID;

    /** M021 - beskrivelse (xs:string) */
    /**  @Column(type="string", name="description", nullable=true) **/
    protected $description;

    // Links to Fonds
    /** @ManyToMany(mappedBy="referenceFondsCreator", targetEntity="Fonds", fetch="EXTRA_LAZY") **/
    protected $referenceFonds;

    public function __construct()
    {
        $this->referenceFonds = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getSystemId()
    {
        return $this->systemId;
    }

    public function setSystemId($systemId)
    {
        $this->systemId = $systemId;
        return $this;
    }

    public function getFondsCreatorName()
    {
        return $this->fondsCreatorName;
    }

    public function setFondsCreatorName($fondsCreatorName)
    {
        $this->fondsCreatorName = $fondsCreatorName;
        return $this;
    }

    public function getFondsCreatorID()
    {
        return $this->fondsCreatorID;
    }

    public function setFondsCreatorID($fondsCreatorID)
    {
        $this->fondsCreatorID = $fondsCreatorID;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = utf8_encode($description);
        return $this;
    }

    public function getReferenceFonds()
    {
        return $this->referenceFonds;
    }

    public function setReferenceFonds($referenceFonds)
    {
        $this->referenceFonds = $referenceFonds;
        return $this;
    }

    public function addReferenceFonds($fonds) {

        if ($this->referenceFonds->contains($fonds)) {
            return;
        }
        $this->referenceFonds[] = $fonds;
        $fonds->addReferenceFondsCreator($this);
    }

    public function __toString()
    {
        return "pk_fonds_creator_id[" . $this->id . "], " . "systemId[" . $this->systemId . "], ".
            "fondsCreatorName[" . $this->fondsCreatorName . "], " . "description[" . $this->description . "]";
    }
}

?>
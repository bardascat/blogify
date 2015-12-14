<?php

namespace BusinessLogic\Models\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity 
 * @Table(name="serviciu")
 */
class Serviciu extends AbstractEntity {

    /**
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    protected $id_serviciu;

    /**
     *
     * @Column(type="string",nullable=false) @var string 
     */
    protected $name;

    /**
     *
     * @Column(type="string",nullable=false) @var string 
     */
    protected $name_en;

    /**
     * @Column(type="text",nullable=true) @var string 
     */
    protected $description;

    /**
     * @ManyToMany(targetEntity="Pachet", mappedBy="servicii")
     * */
    protected $pachete;

    function __construct() {
        $this->pachete = new ArrayCollection();
    }

    public function addPachet(Pachet $pachet) {
        $this->pachete->add($pachet);
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getId_serviciu() {
        return $this->id_serviciu;
    }

    public function setId_serviciu($id_serviciu) {
        $this->id_serviciu = $id_serviciu;
    }
    public function getName_en() {
        return $this->name_en;
    }

    public function setName_en($name_en) {
        $this->name_en = $name_en;
    }


}

?>

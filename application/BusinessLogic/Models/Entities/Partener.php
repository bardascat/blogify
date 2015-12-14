<?php

namespace BusinessLogic\Models\Entities;

/**
 * @Entity 
 * @Table(name="partener")
 */
use Doctrine\Common\Collections\ArrayCollection;

class Partener extends AbstractEntity {

    /**
     *
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    private $id_partener;

    /**
     * @Column(type="string")
     */
    protected $name;

    /**
     * @Column(type="text")
     */
    protected $description;
    public function getId_partener() {
        return $this->id_partener;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setId_partener($id_partener) {
        $this->id_partener = $id_partener;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

}

?>

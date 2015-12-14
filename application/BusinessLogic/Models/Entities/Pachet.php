<?php

namespace BusinessLogic\Models\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity 
 * @Table(name="pachet")
 */
class Pachet extends AbstractEntity {

    /**
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    protected $id_pachet;

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
     * @Column(type="float",nullable=false) @var string 
     */
    protected $price;

    /**
     * @Column(type="integer",nullable=true) @var string 
     */
    protected $active = 1;

    /**
     * @ManyToMany(targetEntity="Serviciu", inversedBy="pachete")
     * @JoinTable(name="pachet_serviciu",
     * joinColumns={@JoinColumn(name="id_pachet", referencedColumnName="id_pachet")},
     * inverseJoinColumns={@JoinColumn(name="id_serviciu", referencedColumnName="id_serviciu")}
     * )
     */
    private $servicii;

    /**
     * metode de user
     */
    private $isEnabled = false;
    private $isExpired = false;
    private $expireDate = false;

    public function getIsEnabled() {
        return $this->isEnabled;
    }

    public function getIsExpired() {
        return $this->isExpired;
    }

    public function getExpireDate() {
        return $this->expireDate;
    }

    public function setIsEnabled($isEnabled) {
        $this->isEnabled = $isEnabled;
    }

    public function setIsExpired($isExpired) {
        $this->isExpired = $isExpired;
    }

    public function setExpireDate($expireDate) {
        $this->expireDate = $expireDate;
    }

    function __construct() {
        $this->servicii = new ArrayCollection();
    }

    public function getServicii() {
        return $this->servicii;
    }

    public function addServiciu(Serviciu $serviciu) {

        $serviciu->addPachet($this);
        $this->servicii->add($serviciu);
    }

    public function getOptiuni() {
        return $this->optiuni;
    }

    public function getName() {
        return $this->name;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getActive() {
        return $this->active;
    }

    public function getId_pachet() {
        return $this->id_pachet;
    }

    public function setId_pachet($id_pachet) {
        $this->id_pachet = $id_pachet;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function setActive($active) {
        $this->active = $active;
    }
    

    public function getName_en() {
        return $this->name_en;
    }

    public function setName_en($name_en) {
        $this->name_en = $name_en;
    }


}

?>

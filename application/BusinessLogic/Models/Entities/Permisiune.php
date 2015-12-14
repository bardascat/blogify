<?php

namespace BusinessLogic\Models\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity 
 * @Table(name="permisiune")
 */
class Permisiune extends AbstractEntity {

    /**
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    protected $perm_id;

    /**
     *
     * @Column(type="string") @var string 
     */
    protected $perm_cod;

    /**
     * @Column(type="string",nullable=true) @var string 
     */
    protected $perm_nume;

    /**
     * @Column(type="string",nullable=true) @var string 
     */
    protected $perm_tip;

    /**
     * @Column(type="integer") @var string 
     */
    protected $perm_activ = 1;

    /** @OneToMany(targetEntity="RolPermisiune", mappedBy="permisiune") */
    private $RolPermisiune;

    function __construct() {
           $this->RolPermisiune = new ArrayCollection();
    }

    public function addRolPermisiune(RolPermisiune $rolPermisiune) {
        $rolPermisiune->setPermisiune($this);
        $this->RolPermisiune->add($rolPermisiune);
    }

    public function getPerm_id() {
        return $this->perm_id;
    }

    public function getPerm_cod() {
        return $this->perm_cod;
    }

    public function getPerm_nume() {
        return $this->perm_nume;
    }

    public function getPerm_tip() {
        return $this->perm_tip;
    }

    public function getPerm_activ() {
        return $this->perm_activ;
    }

    public function setPerm_id($perm_id) {
        $this->perm_id = $perm_id;
    }

    public function setPerm_cod($perm_cod) {
        $this->perm_cod = $perm_cod;
    }

    public function setPerm_nume($perm_nume) {
        $this->perm_nume = $perm_nume;
    }

    public function setPerm_tip($perm_tip) {
        $this->perm_tip = $perm_tip;
    }

    public function setPerm_activ($perm_activ) {
        $this->perm_activ = $perm_activ;
    }

}

?>

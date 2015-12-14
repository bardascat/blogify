<?php

namespace BusinessLogic\Models\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity 
 * @Table(name="rol")
 */
class Rol extends AbstractEntity {

    /**
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    protected $rol_id;

    /**
     *
     * @Column(type="string",nullable=false) @var string 
     */
    protected $rol_nume;

    /** @OneToMany(targetEntity="RolPermisiune", mappedBy="permisiune") */
    private $RolPermisiune;

    /**
     * @ManyToMany(targetEntity="User", mappedBy="roluri")
     * */
    private $useri;

    function __construct() {
        $this->RolPermisiune = new ArrayCollection();
    }

    public function addRolPermisiune(RolPermisiune $rolPermisiune) {
        $this->useri = new \Doctrine\Common\Collections\ArrayCollection();
        $rolPermisiune->setRol($this);
        $this->RolPermisiune->add($rolPermisiune);
    }

    public function getRolPermisiune() {
        return $this->RolPermisiune;
    }


        public function addUser(User $user) {
        $this->useri->add($user);
    }

    public function getRol_id() {
        return $this->rol_id;
    }

    public function getRol_nume() {
        return $this->rol_nume;
    }

    public function setRol_id($rol_id) {
        $this->rol_id = $rol_id;
    }

    public function setRol_nume($rol_nume) {
        $this->rol_nume = $rol_nume;
    }

}

?>

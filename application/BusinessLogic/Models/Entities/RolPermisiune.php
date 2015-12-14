<?php

namespace BusinessLogic\Models\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity 
 * @Table(name="rol_permisiune")
 */
class RolPermisiune extends AbstractEntity {

    /**
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     *
     * @Column(type="integer") @var string 
     */
    protected $perm_id;

    /**
     *
     * @Column(type="integer") @var string 
     */
    protected $rol_id;

    /**
     *
     * @Column(type="integer") @var string 
     */
    protected $rp_valoare;

    /** @ManyToOne(targetEntity="Permisiune", inversedBy="RolPermisiune")
     *  @JoinColumn(name="perm_id", referencedColumnName="perm_id" ,onDelete="CASCADE")
     *  */
    protected $permisiune;

    /** @ManyToOne(targetEntity="Rol", inversedBy="RolPermisiune")
     *  @JoinColumn(name="rol_id", referencedColumnName="rol_id" ,onDelete="CASCADE")
     *  
     */
    protected $rol;

    function __construct() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getPerm_id() {
        return $this->perm_id;
    }

    public function getRol_id() {
        return $this->rol_id;
    }

    public function getRp_valoare() {
        return $this->rp_valoare;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setPerm_id($perm_id) {
        $this->perm_id = $perm_id;
    }

    public function setRol_id($rol_id) {
        $this->rol_id = $rol_id;
    }

    public function setRp_valoare($rp_valoare) {
        $this->rp_valoare = $rp_valoare;
    }

    public function getPermisiune() {
        return $this->permisiune;
    }

    public function getRol() {
        return $this->rol;
    }

    public function setPermisiune($permisiune) {
        $this->permisiune = $permisiune;
    }

    public function setRol($rol) {
        $this->rol = $rol;
    }


}

?>

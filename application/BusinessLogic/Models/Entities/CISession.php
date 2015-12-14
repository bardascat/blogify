<?php

namespace BusinessLogic\Models\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity 
 * @Table(name="cisession")
 */
class CISession extends AbstractEntity {

    /**
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    protected $session_id;

    /**
     *
     * @Column(type="string",nullable=true) @var string 
     */
    protected $ip_address;

    /**
     * @Column(type="string",nullable=true) @var string 
     */
    protected $user_agent;

    /**
     * @Column(type="string",nullable=true) @var string 
     */
    protected $last_activity;

    /**
     * @Column(type="text") @var string 
     */
    protected $user_data;
    
    /**
     * @Column(type="integer") @var string 
     */
    protected $id_user;

   
    public function getSession_id() {
        return $this->session_id;
    }

    public function getIp_address() {
        return $this->ip_address;
    }

    public function getUser_agent() {
        return $this->user_agent;
    }

    public function getLast_activity() {
        return $this->last_activity;
    }

    public function getUser_data() {
        return $this->user_data;
    }

    public function setSession_id($session_id) {
        $this->session_id = $session_id;
    }

    public function setIp_address($ip_address) {
        $this->ip_address = $ip_address;
    }

    public function setUser_agent($user_agent) {
        $this->user_agent = $user_agent;
    }

    public function setLast_activity($last_activity) {
        $this->last_activity = $last_activity;
    }

    public function setUser_data($user_data) {
        $this->user_data = $user_data;
    }
    
    public function getId_user() {
        return $this->id_user;
    }

    public function setId_user($id_user) {
        $this->id_user = $id_user;
    }





}

?>

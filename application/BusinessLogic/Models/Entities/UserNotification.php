<?php

namespace BusinessLogic\Models\Entities;

/**
 * @Entity 
 * @Table(name="user_notification")
 */
class UserNotification  extends AbstractEntity{

    /**
     *
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    private $id_notification;

    /**
     * @Column(type="integer")
     */
    protected $type;
    
    
    /**
     * @Column(type="integer")
     */
    private $id_user;
    
    

    /**
     * @ManyToOne(targetEntity="User",inversedBy="notifications")
     * @JoinColumn(name="id_user", referencedColumnName="id_user" ,onDelete="CASCADE")
     */
    private $user;
    
    
    public function getId_notification() {
        return $this->id_notification;
    }

    public function getType() {
        return $this->type;
    }

    public function getId_user() {
        return $this->id_user;
    }

    public function getUser() {
        return $this->user;
    }

    public function setId_notification($id_notification) {
        $this->id_notification = $id_notification;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setId_user($id_user) {
        $this->id_user = $id_user;
    }

    public function setUser($user) {
        $this->user = $user;
    }



}

?>

<?php

namespace BusinessLogic\Models\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity 
 * @Table(name="users")
 */
class User extends AbstractEntity {

    /**
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    protected $id_user;

    /**
     *
     * @Column(type="string",nullable=true) @var string 
     */
    protected $lastname;

    /**
     * @Column(type="string",nullable=true) @var string 
     */
    protected $firstname;

    /**
     * @Column(type="string",nullable=true) @var string 
     */
    protected $phone;

   
    /**
     * @Column(type="string",unique=true) @var string 
     */
    protected $email;

    /**
     * @Column(type="string",nullable=true) @var string 
     */
    protected $address;


    /**
     * @Column(type="string") @var string 
     */
    private $password;

    /**
     * @Column(type="integer") @var string 
     */
    public $enabled = 1;

    /**
     * @Column(type="integer",nullable=true) @var string 
     */
    protected $fromFb = 0;


    /**
     * @Column(type="datetime")
     */
    protected $created_date;

    /**
     * @OneToMany(targetEntity="UserNotification",mappedBy="user",cascade={"persist","merge"})
     */
    private $notifications;
    
    /**
     * @OneToMany(targetEntity="Blog",mappedBy="user",cascade={"persist","merge"})
     */
    private $blogs;
    

    /**
     * @ManyToMany(targetEntity="Rol", inversedBy="useri")
     * @JoinTable(name="user_rol",
     * joinColumns={@JoinColumn(name="id_user", referencedColumnName="id_user")},
     * inverseJoinColumns={@JoinColumn(name="rol_id", referencedColumnName="rol_id")}
     * )
     */
    private $roluri;



    function __construct() {
        $this->created_date = new \DateTime("now");
        $this->roluri = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notifications = new \Doctrine\Common\Collections\ArrayCollection();
        $this->blogs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function removeRole(Rol $role) {
        if ($this->roluri->contains($role)) {
            $this->roluri->removeElement($role);
            $role->removeUser($this);
        }

        return $this;
    }

    public function removeAllRoles() {
        $this->roluri->clear();
    }

    /**
     * 
     * @return Rol
     */
    public function getRoluri() {
        return $this->roluri;
    }

    public function addRol(Rol $rol) {
        $rol->addUser($this);
        $this->roluri->add($rol);
    }

    public function getId_user() {
        return $this->id_user;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getFromFb() {
        return $this->fromFb;
    }

    public function getCreated_date() {
        return $this->created_date;
    }

    public function setId_user($id_user) {
        $this->id_user = $id_user;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setFromFb($fromFb) {
        $this->fromFb = $fromFb;
    }

    public function setCreated_date($created_date) {
        $this->created_date = $created_date;
    }

    public function isEnabled() {
        return $this->enabled;
    }

    public function setIsEnabled($enabled) {
        $this->enabled = $enabled;
    }

    public function addUserNotification(UserNotification $not) {
        $this->notifications->add($not);
        $not->setUser($this);
    }

    /**
     * 
     * @return UserNotification
     */
    public function getUserNotifications() {
        return $this->notifications;
    }

    public function addBlog(Blog $blog) {
        $this->blogs->add($blog);
        $blog->setUser($this);
    }

    /**
     * @return Blogs
     */
    public function getBlogs() {
        return $this->blogs;
    }
    

}

?>

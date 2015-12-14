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
     * @Column(type="string",nullable=true) @var string 
     */
    protected $serie_buletin;

    /**
     * @Column(type="string",unique=true) @var string 
     */
    protected $email;

    /**
     * @Column(type="string",nullable=true) @var string 
     */
    protected $address;

    /**
     * @Column(type="string",nullable=true) @var string 
     */
    protected $profile_image;

    /**
     * @Column(type="string") @var string 
     */
    private $password;

    /**
     * @Column(type="integer") @var string 
     */
    public $user_activ = 1;

    /**
     * @Column(type="integer",nullable=true) @var string 
     */
    protected $fromFb = 0;

    /**
     * @Column(type="float") @var string 
     */
    protected $sold = 0;

    /**
     * @Column(type="datetime")
     */
    protected $created_date;

    /**
     * @OneToMany(targetEntity="UserNotification",mappedBy="user",cascade={"persist","merge"})
     */
    private $notifications;

    /**
     * @OneToMany(targetEntity="Transaction",mappedBy="client",cascade={"persist","merge"})
     */
    private $transactions;

    /**
     * @ManyToMany(targetEntity="Rol", inversedBy="useri")
     * @JoinTable(name="user_rol",
     * joinColumns={@JoinColumn(name="id_user", referencedColumnName="id_user")},
     * inverseJoinColumns={@JoinColumn(name="rol_id", referencedColumnName="rol_id")}
     * )
     */
    private $roluri;

    /**
     * @OneToMany(targetEntity="Email",mappedBy="fromE",cascade={"persist","merge"})
     * @OrderBy({"id_email"="desc"})
     */
    private $inbox;

    /**
     * @OneToMany(targetEntity="Email",mappedBy="toE",cascade={"persist","merge"})
     * @OrderBy({"id_email"="desc"})
     */
    private $sent;

    /**
     * @OneToMany(targetEntity="Email",mappedBy="operator",cascade={"persist","merge"})
     * @OrderBy({"id_task"="desc"})
     */
    private $toDoTask;

    /**
     * @OneToMany(targetEntity="Email",mappedBy="client",cascade={"persist","merge"})
     * @OrderBy({"id_task"="desc"})
     */
    private $requestedTask;

    /**
     * @OneToMany(targetEntity="Order",mappedBy="user",cascade={"persist"})
     * @OrderBy({"id_order" = "desc"})
     */
    protected $orders;

    function __construct() {
        $this->created_date = new \DateTime("now");
        $this->roluri = new \Doctrine\Common\Collections\ArrayCollection();

        $this->inbox = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sent = new \Doctrine\Common\Collections\ArrayCollection();
        $this->requestedTask = new \Doctrine\Common\Collections\ArrayCollection();
        $this->toDoTask = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notifications = new \Doctrine\Common\Collections\ArrayCollection();
        $this->transactions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    //adminul primeste un task
    public function addToDOTask(Task $task) {
        $this->toDoTask->add($task);
        $task->setOperator($this);
    }

    public function getToDoTasks() {
        return $this->toDoTask;
    }

    //clientul solicita taskul sa fie facut
    public function requestTask(Task $task) {
        $this->requestedTask->add($task);
        $task->setClient($this);
    }

    public function getRequestedTasks() {
        return $this->requestedTask;
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

    public function getUser_activ() {
        return $this->user_activ;
    }

    public function setUser_activ($user_activ) {
        $this->user_activ = $user_activ;
    }

    public function addEmail(Email $email) {
        $this->inbox->add($email);
        $email->setFrom($user);
    }

    public function getInbox() {
        return $this->sent;
    }

    public function getSent() {
        return $this->inbox;
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

    /**
     * 
     * @return Order
     */
    public function getOrders() {
        return $this->orders;
    }

    public function getPachete() {
        $pachete = array();
        $orders = $this->getOrders();
        foreach ($orders as $order) {
            if ($order->getOrder_type() == "alimentare")
                continue;


            $items = $order->getItems();
            $pachet = $items[0]->getPachet();

            if ($order->getPayment_status() == \App_constants::$PAYMENT_STATUS_CONFIRMED) {
                $pachet->setIsEnabled(true);
            } else {
                $pachet->setIsEnabled(false);
            }

            $pachet->setExpireDate($items[0]->getExpires()->format("d-m-Y"));



            if (strtotime($pachet->getExpireDate()) < strtotime(date("d-m-Y"))) {
                $pachet->setIsExpired(true);
            } else
                $pachet->setIsExpired(false);



            $pachete[] = $pachet;
        }

        //momentan un utilizator poate avea un sg pachet activ
        return $pachete;
    }

    /**
     * @return OrderItem
     */
    public function getPachetOrderItem() {
        $pachete = array();
        $orders = $this->getOrders();
        foreach ($orders as $order) {

            if ($order->getOrder_type() == "alimentare")
                continue;

            // if ($order->getPayment_status() != \App_constants::$PAYMENT_STATUS_CONFIRMED)
            //     continue;;
            $items = $order->getItems();
            return $items[0];
        }
        return false;
    }

    /**
     * todo: vezi care pachete e ok
     * @return Pachet
     */
    public function getActivePachet() {
        $pachete = $this->getPachete();

        return (isset($pachete[0]) ? $pachete[0] : false);
    }

    public function addOrder(Order $order) {
        $this->orders->add($order);
        $order->setUser($this);
    }

    public function addTransaction(Transaction $transaction) {
        $this->transactions->add($transaction);
        $transaction->setClient($this);
    }

    public function getTransactions() {
        return $this->transactions;
    }

    public function setTransactions($transactions) {
        $this->transactions = $transactions;
    }

    public function getUserImage() {
        if (file_exists($this->profile_image))
            return base_url($this->profile_image);
        else
            return base_url("assets/frontend/layout/no_image.jpg");
    }

    public function getProfile_image() {
        return $this->profile_image;
    }

    public function setProfile_image($profile_image) {
        $this->profile_image = $profile_image;
    }

    public function getSold() {
        return $this->sold;
    }

    public function setSold($sold) {
        $this->sold = $sold;
    }

    public function getSerie_buletin() {
        return $this->serie_buletin;
    }

    public function setSerie_buletin($serie_buletin) {
        $this->serie_buletin = $serie_buletin;
    }

}

?>

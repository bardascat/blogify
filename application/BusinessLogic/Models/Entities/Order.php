<?php

namespace BusinessLogic\Models\Entities;

/**
 * @Entity 
 * @Table(name="orders")
 */
use Doctrine\Common\Collections\ArrayCollection;

class Order extends AbstractEntity {

    /**
     *
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    protected $id_order;

    /**
     * @ManyToOne(targetEntity="User",inversedBy="orders")
     * @JoinColumn(name="id_user", referencedColumnName="id_user" ,onDelete="CASCADE")
     */
    protected $user;

    /** @OneToMany(targetEntity="OrderItem", mappedBy="order",cascade={"persist"}) */
    protected $orderItems;

    /**
     * @Column(type="integer") @var float
     */
    protected $id_user;

    /**
     * @Column(type="string") @var string
     */
    protected $payment_method;

    /**
     * @Column(type="string",nullable=true) @var float
     */
    protected $shipping_notes;

    /**
     * @Column(type="float") @var float
     */
    protected $shipping_cost=0;

    /**
     * @Column(type="string") @var string
     * statusurile by default sunt: F(finalizat), W(waiting), C(anulat),R(refund)
     */
    protected $payment_status = "W";

    /**
     * @Column(type="string",nullable=true) @var string
     */
    protected $installments;

    /**
     * @Column(type="string",nullable=true) @var string
     */
    protected $mail_notification = 0;

    /**
     * @Column(type="string") @var string
     * statusurile by default sunt: 
     * W=Neprocesata,
     * W2=In curs de procesara,
     * P=Procesata,
     * F=Finalizata(Livrata)
     * C=Anulata
     */
    protected $order_status = "W";

    /**
     * @Column(type="float") @var float
     */
    protected $total;

    /**
     * @Column(type="datetime") @var float
     */
    protected $orderedOn;

    /**
     * @Column(type="datetime",nullable=true) @var float
     */
    protected $shippedOn;

    /**
     * @Column(type="string",nullable=true)
     */
    protected $order_number;
    
    /**
     * alimentare sau cumparare pachet
     * @Column(type="string")
     */
    protected $order_type="pachet";

    public function __construct() {
        $this->orderedOn = new \DateTime("now");

        $this->orderItems = new ArrayCollection();
    }

    public function getOrderedOn() {
        return $this->orderedOn->format("d-m-Y H:i:s");
    }

    public function getShippedOn() {
        if ($this->shippedOn)
            return $this->shippedOn->format('d-m-Y');
        else
            return false;
    }

    public function setUser(User $user) {
        $this->user = $user;
    }

    public function addOrderItem(OrderItem $orderItem) {
        $this->orderItems->add($orderItem);
        $orderItem->setOrder($this);
    }

    /**
     * @return OrderItem
     */
    public function getItems() {
        return $this->orderItems;
    }

    public function getPayment_method() {
        return $this->payment_method;
    }

    public function setPayment_method($payment_method) {
        $this->payment_method = $payment_method;
    }

    public function getShipping_notes() {
        return $this->shipping_notes;
    }

    public function setShipping_notes($shipping_notes) {
        $this->shipping_notes = $shipping_notes;
    }

    public function getShipping_cost() {
        return $this->shipping_cost;
    }

    public function setShipping_cost($shipping_cost) {
        $this->shipping_cost = $shipping_cost;
    }

    public function getPayment_status() {
        return $this->payment_status;
    }

    public function setPayment_status($payment_status) {
        $this->payment_status = $payment_status;
    }

    public function getTotal() {
        return $this->total;
    }

    public function setTotal($total) {
        $this->total = $total;
    }

    public function setOrderNumber($number) {
        $this->order_number = $number;
    }

    public function getOrderNumber() {
        return $this->order_number;
    }

    /**
     * 
     * @return \Dealscount\Models\Entities\User
     */
    public function getUser() {
        return $this->user;
    }

    public function getId_order() {
        return $this->id_order;
    }

    public function setId_order($id_order) {
        $this->id_order = $id_order;
    }

    public function getOrderStatus() {
        return $this->order_status;
    }

    public function setOrderStatus($order_status) {
        $this->order_status = $order_status;
    }

    public function getInstallments() {
        return $this->installments;
    }

    public function setInstallments($installments) {
        $this->installments = $installments;
    }

    /**
     * 
     * Metoda folosita pentru a repopula inputurile din forms
     */
    public function getIterationArray() {

        $iteration = array();
        foreach ($this as $key => $value) {
            if (!is_object($value) || ($value instanceof \DateTime))
                $iteration[$key] = $value;
        }

        /*
          $ItemDetails = $this->getItemDetails();

          $extra = $ItemDetails->getIterationArray();

          foreach ($extra as $key => $value)
          $iteration[$key] = $value;
         */
        return $iteration;
    }

    public function getMail_notification() {
        return $this->mail_notification;
    }

    public function setMail_notification($mail_notification) {
        $this->mail_notification = $mail_notification;
    }

    public function getFree() {
        return $this->free;
    }

    public function setFree($free) {
        $this->free = $free;
    }

    public function getOrder_type() {
        return $this->order_type;
    }

    public function setOrder_type($order_type) {
        $this->order_type = $order_type;
    }

        /**
     * 
     * Summary functions
     */
    public function getTotalPaymentPartner() {
        $orderItems = $this->getItems();
        $total = 0;
        foreach ($orderItems as $orderItem) {
            $item = $orderItem->getItem();
            $total+=$orderItem->getQuantity() * $item->getVoucher_price();
        }
        return $total;
    }

    public function getTotalDiscount() {
        $orderItems = $this->getItems();
        $total = 0;
        foreach ($orderItems as $orderItem) {
            $item = $orderItem->getItem();
            $total+=($orderItem->getQuantity() * $item->getPrice()) - ($orderItem->getQuantity() * $item->getVoucher_price());
        }
        return $total;
    }

    public function getVouchersNr() {
        $orderItems = $this->getItems();
        $nr = 0;
        foreach ($orderItems as $orderItem) {
            $nr+= $orderItem->getQuantity();
        }
        return $nr;
    }

}

?>

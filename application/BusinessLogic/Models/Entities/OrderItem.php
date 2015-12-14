<?php

namespace BusinessLogic\Models\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity 
 * @Table(name="orders_items")
 */
class OrderItem extends AbstractEntity {

    /**
     *
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     *
     * @Column(type="integer")
     */
    protected $id_order;

    /**
     *
     * @Column(type="integer")
     */
    protected $id_pachet;



    /**
     * @ManyToOne(targetEntity="Order",inversedBy="orderItems")
     * @JoinColumn(name="id_order", referencedColumnName="id_order" ,onDelete="CASCADE")
     */
    private $order;

    /**
     * @ManyToOne(targetEntity="Pachet")
     * @JoinColumn(name="id_pachet", referencedColumnName="id_pachet")
     */
    private $pachet;


    /**
     * @Column(type="integer")
     */
    protected $quantity;

    /**
     * @Column(type="date")
     */
    protected $expires;
    
    /**
     * @Column(type="integer")
     */
    protected $total;

    /**
     * W = wating
     * F = finalizat
     * C - anulat
     * @Column(type="string")
     */
    protected $status = "F";

    function __construct() {
        $this->vouchers = new ArrayCollection();
    }

    public function setOrder(Order $order) {
        $this->order = $order;
    }

    /**
     * @return Order
     */
    public function getOrder() {
        return $this->order;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    public function setTotal($total) {
        $this->total = $total;
    }

    public function getTotal() {
        return $this->total;
    }

    public function setItem(\BusinessLogic\Models\Entities\Pachet $pachet) {
        $this->pachet = $pachet;
    }

    /**
     * 
     * @return Pachet
     */
    public function getPachet() {
        return $this->pachet;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getId_order() {
        return $this->id_order;
    }

    public function setId_order($id_order) {
        $this->id_order = $id_order;
    }

    public function getId_item() {
        return $this->id_item;
    }

    public function setId_item($id_item) {
        $this->id_item = $id_item;
    }

    public function getExpires() {
        return $this->expires;
    }

    public function setExpires($expires) {
        $this->expires = $expires;
    }


   

}

?>

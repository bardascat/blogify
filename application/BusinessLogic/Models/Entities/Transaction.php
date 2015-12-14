<?php

namespace BusinessLogic\Models\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity 
 * @Table(name="user_transaction")
 */
class Transaction extends AbstractEntity {

    /**
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    protected $id_transaction;

    /**
     * $TRANZACTIE_DEBITARE
     * $TRANZACTIE_CHELTUIELI
     * @Column(type="integer",nullable=false) @var string 
     */
    protected $type;

    /**
     * @Column(type="datetime") @var string 
     */
    protected $stamp;

    /**
     * @Column(type="float")
     */
    protected $value;
    
    /**
     * @Column(type="float")
     */
    protected $current_sold;
    
    /**
     * @Column(type="string")
     */
    protected $details;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="id_operator", referencedColumnName="id_user" ,onDelete="CASCADE")
     */
    protected $operator;

    /**
     * @ManyToOne(targetEntity="User",inversedBy="transactions")
     * @JoinColumn(name="id_client", referencedColumnName="id_user" ,onDelete="CASCADE")
     */
    protected $client;

    /**
     * @ManyToOne(targetEntity="Partener")
     * @JoinColumn(name="id_partener", referencedColumnName="id_partener" ,onDelete="CASCADE")
     */
    private $partener;

    function __construct() {
        $this->stamp=new \DateTime("now");
    }
    public function getId_transaction() {
        return $this->id_transaction;
    }

    public function getType() {
        return $this->type;
    }

    public function getStamp() {
        return $this->stamp;
    }

    public function getValue() {
        return $this->value;
    }

    public function getOperator() {
        return $this->operator;
    }

    public function getClient() {
        return $this->client;
    }

    public function setId_transaction($id_transaction) {
        $this->id_transaction = $id_transaction;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setStamp($stamp) {
        $this->stamp = $stamp;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function setOperator($operator) {
        $this->operator = $operator;
    }

    public function setClient($client) {
        $this->client = $client;
    }

    public function getPartener() {
        return $this->partener;
    }

    public function setPartener($partener) {
        $this->partener = $partener;
    }

    public function getDetails() {
        return $this->details;
      
    }

    public function setDetails($details) {
        $this->details = $details;
    }


    public function getCurrent_sold() {
        return $this->current_sold;
    }

    public function setCurrent_sold($current_sold) {
        $this->current_sold = $current_sold;
    }



}

?>

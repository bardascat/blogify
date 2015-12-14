<?php

namespace BusinessLogic\Models\Entities;

/**
 * @Entity 
 * @Table(name="email")
 */
class Email extends AbstractEntity {

    /**
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    private $id_email;

    /**
      @Column(type="string")
     */
    protected $title;

    /**
      @Column(type="text",nullable=true)
     */
    protected $content;

    /**
      @Column(type="integer")
     */
    protected $viewed = 0;

    /**
      @Column(type="integer")
     */
    protected $deleted = 0;

    /**
      @Column(type="datetime")
     */
    protected $cDate;

    /**
     * @ManyToOne(targetEntity="User",inversedBy="inbox")
     * @JoinColumn(name="from_email", referencedColumnName="id_user" ,onDelete="CASCADE")
     */
    private $fromE;

    /**
     * @ManyToOne(targetEntity="User",inversedBy="sent")
     * @JoinColumn(name="to_email", referencedColumnName="id_user" ,onDelete="CASCADE")
     */
    private $toE;

    function __construct() {
        $this->cDate = new \DateTime("now");
    }

    public function getId_email() {
        return $this->id_email;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getContent() {
        return $this->content;
    }

    public function getCDate() {
        return $this->cDate;
    }

    public function getUser() {
        return $this->user;
    }

    public function setId_email($id_email) {
        $this->id_email = $id_email;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setCDate($cDate) {
        $this->cDate = $cDate;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function getFrom() {
        return $this->fromE;
    }

    public function getTo() {
        return $this->toE;
    }

    public function setFrom($from) {
        $this->fromE = $from;
    }

    public function setTo($to) {
        $this->toE = $to;
    }

    public function getViewed() {
        return $this->viewed;
    }

    public function setViewed($viewed) {
        $this->viewed = $viewed;
    }

    public function getDeleted() {
        return $this->deleted;
    }

    public function setDeleted($deleted) {
        $this->deleted = $deleted;
    }


}

?>

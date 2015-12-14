<?php

namespace BusinessLogic\Models\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity 
 * @Table(name="newsletter_subscribers")
 */
class NewsletterSubscriber {

    /**
     *
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    private $id_subscriber;

    /**
      @Column(type="string",unique=true)
     */
    protected $email;

    /**
      @Column(type="datetime")
     */
    protected $cDate;

    function __construct() {
        $this->cDate = new \DateTime('now');
    }

    public function getId_subscriber() {
        return $this->id_subscriber;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getCDate() {
        return $this->cDate;
    }

    public function setId_subscriber($id_subscriber) {
        $this->id_subscriber = $id_subscriber;
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setCDate($cDate) {
        $this->cDate = $cDate;
        return $this;
    }

}

?>

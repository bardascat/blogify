<?php

namespace BusinessLogic\Models\Entities;

/**
 * @Entity 
 * @Table(name="news")
 */
use Doctrine\Common\Collections\ArrayCollection;

class News extends AbstractEntity {

    /**
     *
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    private $id_news;

    /**
     * @Column(type="string")
     */
    protected $slug;

    /**
     * @Column(type="text")
     */
    protected $brief;

    /**
     * @Column(type="text",nullable=true)
     */
    protected $description;

    /**
     * @Column(type="integer")
     */
    protected $position;

    /**
     * @Column(type="datetime")
     */
    protected $cDate;

    /**
     * @Column(type="text",nullable=true)
     */
    protected $brief_ro;

    /**
     * @Column(type="text",nullable=true)
     */
    protected $description_ro;

    function __construct() {
        $this->cDate = new \DateTime('now');
    }

    public function getId_news() {
        return $this->id_news;
    }

    public function getDescription() {
         if (\BusinessLogic\Util\Language::getLanguage() == "ro")
            return $this->description_ro;
        else
            return $this->description;
    }

    public function getCDate() {
        return $this->cDate;
    }

    public function setId_news($id_news) {
        $this->id_news = $id_news;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setCDate($cDate) {
        $this->cDate = $cDate;
    }

    public function getPosition() {
        return $this->position;
    }

    public function setPosition($position) {
        $this->position = $position;
    }

    public function getBrief() {
         if (\BusinessLogic\Util\Language::getLanguage() == "ro")
            return $this->brief_ro;
        else
            return $this->brief;
    }

    public function setBrief($brief) {
        $this->brief = $brief;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }

}

?>

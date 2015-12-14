<?php

namespace BusinessLogic\Models\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity 
 * @Table(name="simple_pages")
 */
class SimplePage extends AbstractEntity {

    /**
     *
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    private $id_page;

    /**
     *
     * @Column(type="string") @var string 
     */
    protected $name;

    /**
     * @Column(type="string") @var string 
     */
    protected $slug;

    /**
     * @Column(type="string",nullable=true) @var string 
     */
    protected $layout;

    /**
     * @Column(type="text",nullable=true) @var string 
     */
    protected $content;

    /**
     * @Column(type="text",nullable=true) @var string 
     */
    protected $content_ro;

    public function getId_page() {
        return $this->id_page;
    }

    public function setId_page($id_page) {
        $this->id_page = $id_page;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }

    public function getLayout() {
        return $this->layout;
    }

    public function setLayout($layout) {
        $this->layout = $layout;
    }

    public function getContent() {
        if (\BusinessLogic\Util\Language::getLanguage() == "ro")
            return $this->content_ro;
        else
            return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function getIterationArray() {

        $iteration = array();
        foreach ($this as $key => $value) {
            if (!is_object($value) || ($value instanceof \DateTime))
                switch (\BusinessLogic\Util\Language::getLanguage()) {
                    case "ro": {
                            $ro_key = $key."_ro";
                            
                            if (isset($this->$ro_key)) {
                                $iteration[$key] = $this->$ro_key;
                            } else {
                                $iteration[$key] = $value;
                            }
                        }break;
                    default: {
                            $iteration[$key] = $value;
                        }
                }
        }

        return $iteration;
    }

    public function getContent_ro() {
        return $this->content_ro;
    }

    public function setContent_ro($content_ro) {
        $this->content_ro = $content_ro;
    }

}

?>

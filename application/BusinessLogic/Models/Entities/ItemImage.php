<?php

namespace BusinessLogic\Models\Entities;

/**
 * @Entity 
 * @Table(name="items_images")
 */
class ItemImage {

    public function getId_image() {
        return $this->id_image;
    }

    public function setId_image($id_image) {
        $this->id_image = $id_image;
    }

    public function getImage($type = false) {

            return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function getThumb() {
      
        return $this->thumb;
    }

    public function setThumb($thumb) {
        $this->thumb = $thumb;
    }

    public function getIdItem() {
        return $this->id_item;
    }

    public function setIdItem($id_item) {
        $this->id_item = $id_item;
    }

    /**
     *
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    private $id_image;

    /**
     * @Column(type="integer",nullable=true) @var int 
     */
    public $primary_image = 0;

    public function getPrimary() {
        return $this->primary_image;
    }

    public function setPrimary($primary) {
        $this->primary_image = $primary;
    }

    /**
     *
     * @Column(type="string") @var string 
     */
    public $image;

    /**
     *
     * @Column(type="string") @var string 
     */
    public $thumb;

    /**
     * @Column(type="integer") @var string 
     */
    private $id_item;

    /**
     * @ManyToOne(targetEntity="Item",inversedBy="images")
     * @JoinColumn(name="id_item", referencedColumnName="id_item" ,onDelete="CASCADE")
     */
    private $item;

    public function setItem(Item $item) {
        $this->item = $item;
    }

    public function getItem() {
        return $this->item;
    }

}

?>

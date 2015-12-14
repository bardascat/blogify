<?php

namespace BusinessLogic\Models\Entities;

/**
 * @Entity 
 * @Table(name="items_tags")
 */
class ItemTags {

    /**
     *
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    private $id_tag;

    /**
     * @Column(type="integer")
     */
    protected $id_item;

    /**
     * @Column(type="string")
     */
    protected $value;

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

    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    public function getId_tag() {
        return $this->id_tag;
    }

    public function setId_tag($id_tag) {
        $this->id_tag = $id_tag;
        return $this;
    }
    
    public function getId_item() {
        return $this->id_item;
    }

    public function setId_item($id_item) {
        $this->id_item = $id_item;
        return $this;
    }



}

?>

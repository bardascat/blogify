<?php

namespace BusinessLogic\Models\Entities;

/**
 * @Entity 
 * @Table(name="post_categories")
 */
class PostCategories {

    /**
     *
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(type="integer")
     */
    private $id_post;

    /**
     * @Column(type="integer")
     */
    private $id_category;

    /** @ManyToOne(targetEntity="Category", inversedBy="ItemCategories")
     *  @JoinColumn(name="id_category", referencedColumnName="id_category" ,onDelete="CASCADE")
     *  */
    protected $category;

    /** @ManyToOne(targetEntity="Post", inversedBy="PostCategories")
     *  @JoinColumn(name="id_post", referencedColumnName="id_post" ,onDelete="CASCADE")
     *  
     */
    protected $item;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId_post() {
        return $this->id_post;
    }

    public function setId_post($id_post) {
        $this->id_post = $id_post;
    }

    public function getId_category() {
        return $this->id_category;
    }

    public function setId_category($id_category) {
        $this->id_category = $id_category;
    }

    public function getCategory() {
        
        return $this->category;
    }

    public function setCategory($category) {
        $this->category = $category;
    }

    public function getPost() {
        return $this->post;
    }

    public function setPost($post) {
        $this->post = $post;
    }



}

?>

<?php

namespace BusinessLogic\Models\Entities;

/**
 * this table is used for storing post images which most of them comes from the media rss tag.
 * @Entity 
 * @Table(name="post_images")
 */
class PostImage {

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

    public function getIdPost() {
        return $this->id_Post;
    }

    public function setIdPost($id_Post) {
        $this->id_Post = $id_Post;
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
    private $id_post;

    /**
     * @ManyToOne(targetEntity="Post",inversedBy="images")
     * @JoinColumn(name="id_post", referencedColumnName="id_post" ,onDelete="CASCADE")
     */
    private $Post;

    public function setPost(Post $Post) {
        $this->Post = $Post;
    }

    public function getPost() {
        return $this->Post;
    }

}

?>

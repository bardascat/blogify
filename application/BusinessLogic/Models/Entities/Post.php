<?php

namespace BusinessLogic\Models\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity 
 * @Table(name="post")
 */
 
class Post extends AbstractEntity {

    /**
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    protected $id_post;
    
    
    /**
     * @Column(type="integer",unique=true)
     */
    protected $guid;

    /**
     *
     * @Column(type="string",nullable=false) @var string 
     */
    protected $title;

  
    /**
     * @Column(type="text",nullable=true) @var string 
     */
    protected $description;

    /**
     * @ManyToOne(targetEntity="Blog",inversedBy="posts")
     * @JoinColumn(name="id_blog", referencedColumnName="id_blog" ,onDelete="CASCADE")
     */
    protected $blog;
    
    /**
     *
     * @Column(type="text",nullable=true) @var string 
     */
    protected $content;
    
     /**
     * @Column(type="datetime",nullable=false)
     */
    protected $published;
    
    /**
     * @Column(type="datetime",nullable=true)
     */
    protected $updated;
    
    /**
     * @Column(type="datetime",nullable=false)
     */
    protected $timestamp;
    
    
    
    /**
     *
     * @Column(type="string",nullable=true) @var string 
     */
    protected $image;
    

    /** @OneToMany(targetEntity="PostCategories", mappedBy="item",cascade={"persist","merge"}) */
    private $PostCategories;
    
    function __construct() {
      $this->images = new ArrayCollection();
      $this->timestamp = new \DateTime("now");
    }
    
     /**
     * @OneToMany(targetEntity="PostImage",mappedBy="item",cascade={"persist","merge"})
     * @OrderBy({"primary_image" = "desc","id_image"="desc"})
     */
    private $images;

    public function addImage(PostImage $image) {
        $image->setPost($this);
        if (!($this->images instanceof ArrayCollection))
            $this->images = new ArrayCollection ();

        $this->images->add($image);
    }
    public function getImages() {
        return $this->images;
    }
    
     public function addCategory(PostCategories $postCategories) {
        $postCategories->setPost($this);
        $this->PostCategories->add($postCategories);
    }

   
    public function getPostCategories() {
        return $this->PostCategories;
    }
    
    public function setBlog($blog){
        $this->blog=$blog;
    }
    public function getBlog(){
     return $this->blog;   
    }
    
    public function setTitle($title) {
        $this->title = $title;
    }
    public function getTitle(){
        return $this->title;
    }

    public function setDescription($description) {
        $this->description = $description;
    }
    
    public function getDescription() {
        return $this->description;
    }

    public function getIdPost(){
        return $this->id_post;
    }

    public function setContent($content) {
        $this->content = $content;
    }
    public function getContent(){
        return $this->content;
    }
    
    public function setPublished($published) {
        $this->published = $published;
    }
    public function getPublished(){
        return $this->published;
    }
    
    public function setUpdated($updated) {
        $this->updated = $updated;
    }
    public function getUpdated(){
        return $this->updated;
    }
    
    
    public function getTimestamp(){
        return $this->timestamp;
    }
    
    public function setImage($image) {
        $this->image = $image;
    }
    public function getImage(){
        return $this->image;
    }
    
     public function setGuid($guid) {
        $this->guid = $guid;
    }
    public function getGuid(){
        return $this->guid;
    }
 

}

?>

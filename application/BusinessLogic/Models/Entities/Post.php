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
     * @Column(type="string",nullable=true) @var string 
     */
    protected $content;
    
     /**
      * todo check if this can be nullable
     * @Column(type="datetime",nullable=false)
     */
    protected $date;
    
    /**
     *
     * @Column(type="string",nullable=true) @var string 
     */
    protected $image;
    

    function __construct() {
      
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
    
    public function setDate($date) {
        $this->date = $date;
    }
    public function getDate(){
        return $this->date;
    }
    
    public function setImage($image) {
        $this->image = $image;
    }
    public function getImage(){
        return $this->image;
    }
 

}

?>

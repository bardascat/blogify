<?php

namespace BusinessLogic\Models\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity 
 * @Table(name="blog")
 */
class Blog extends AbstractEntity {

    /**
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    protected $id_blog;

    /**
     *
     * @Column(type="string",nullable=false) @var string 
     */
    protected $url;

    /**
     *
     * @Column(type="string",nullable=false) @var string 
     */
    protected $name;


    /**
     * @Column(type="integer",nullable=true) @var string 
     */
    protected $active = 1;

    /** @OneToMany(targetEntity="Post", mappedBy="blog",cascade={"persist"}) */
    private $posts;

   
    /**
     * @ManyToOne(targetEntity="User",inversedBy="blogs")
     * @JoinColumn(name="id_user", referencedColumnName="id_user" ,onDelete="CASCADE")
     */
    private $user;
    
    

    function __construct() {
        $this->posts = new ArrayCollection();
    }
    public function addPost(Post $post) {
        $post->addBlog($this);
        $this->posts->add($post);
    }
    public function getName(){
        return $this->name;
    }
    public function setName($name){
        $this->name=$name;
    }
    public function setUrl($url){
        $this->url=$url;
    }
    public function getUrl(){
        return $this->url;
    }
     public function setUser(User $user) {
        $this->user = $user;
    }
    public function getUser() {
        return $this->user;
    }

    

}

?>

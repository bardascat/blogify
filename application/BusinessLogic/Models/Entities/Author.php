<?php

namespace BusinessLogic\Models\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity 
 * @Table(name="author",uniqueConstraints={@UniqueConstraint(name="unique_author", columns={"id_blog", "name"})})
 */
class Author extends AbstractEntity {

    /**
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    protected $id_author;

    /**
     *
     * @Column(type="string",nullable=false) @var string 
     */
    protected $name;

  
    /**
     * @Column(type="string",nullable=true) @var string 
     */
    protected $email;

    /**
     * @ManyToOne(targetEntity="Blog",inversedBy="authors")
     * @JoinColumn(name="id_blog", referencedColumnName="id_blog" ,onDelete="CASCADE")
     */
    protected $blog;
    
    /**
     *
     * @Column(type="string",nullable=true) @var string 
     */
    protected $link;

 
    

    function __construct() {
      
    }

  
    public function setBlog($blog){
        $this->blog=$blog;
    }
    public function getBlog(){
     return $this->blog;   
    }
    
    public function setName($name) {
        $this->name = $name;
    }
    public function getName(){
        return $this->name;
    }

    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function getEmail() {
        return $this->email;
    }

    public function setLink($link){
        $this->link=$link;
    }

    public function getLink(){
        return $this->link;
    }

}

?>

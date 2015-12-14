<?php

namespace BusinessLogic\Models\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity 
 * @Table(name="task_note")
 */
class TaskNotes extends AbstractEntity {

    /**
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    private $id_note;

    /**
     * @Column(type="string",nullable=true)
     */
    protected $name;

    /**
     * @Column(type="text")
     */
    protected $content;

    /**
     * @Column(type="datetime")
     */
    protected $stamp;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="id_operator", referencedColumnName="id_user" ,onDelete="CASCADE")
     */
    private $operator;
    
    /**
     * @ManyToOne(targetEntity="Task")
     * @JoinColumn(name="id_task", referencedColumnName="id_task" ,onDelete="CASCADE")
     */
    private $task;

   
    function __construct() {
        $this->stamp=new \DateTime("now");
    }
    
    public function getId_note() {
        return $this->id_note;
    }

    public function getName() {
        return $this->name;
    }

    public function getContent() {
        return $this->content;
    }

    public function getStamp() {
        return $this->stamp;
    }

    public function getOperator() {
        return $this->operator;
    }

    public function getTask() {
        return $this->task;
    }

    public function setId_note($id_note) {
        $this->id_note = $id_note;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setStamp($stamp) {
        $this->stamp = $stamp;
    }

    public function setOperator($operator) {
        $this->operator = $operator;
    }

    public function setTask($task) {
        $this->task = $task;
    }



}

?>

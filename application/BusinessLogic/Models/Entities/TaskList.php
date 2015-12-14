<?php

namespace BusinessLogic\Models\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity 
 * @Table(name="task_list")
 */
class Tasklist extends AbstractEntity {

    /**
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    private $id_list;

    /**
     * @Column(type="string")
     */
    private $name;
    
     /**
     * @Column(type="string",nullable=true)
     */
    private $editable;

    /**
     * @Column(type="integer")
     */
    private $id_parent;

    /**
     * @OneToMany(targetEntity="Tasklist", mappedBy="parentList")
     */
    private $childrenList;

    /**
     * @ManyToOne(targetEntity="Tasklist", inversedBy="childrenList")
     * @JoinColumn(name="id_parent", referencedColumnName="id_list")
     */
    private $parentList;

    /**
     * @OneToMany(targetEntity="Task",mappedBy="taskList",cascade={"persist","merge"})
     * @OrderBy({"id_task" = "desc"})
     */
    private $tasks;
    
   
    

    function __construct() {
        $this->childrenList = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tasks =new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function addTask(Task $task){
        $this->tasks->add($task);
        $task->setTaskList($this);
        
    }
    
    public function getId_list() {
        return $this->id_list;
    }

    public function getName() {
        return $this->name;
    }

    public function getId_parent() {
        return $this->id_parent;
    }

    public function getChildrenList() {
        return $this->childrenList;
    }

    public function getParentList() {
        return $this->parentList;
    }

    public function setId_list($id_list) {
        $this->id_list = $id_list;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setId_parent($id_parent) {
        $this->id_parent = $id_parent;
    }

    public function setChildrenList($childrenList) {
        $this->childrenList = $childrenList;
    }

    public function setParentList($parentList) {
        $this->parentList = $parentList;
    }

    public function getEditable() {
        return $this->editable;
    }

    public function setEditable($editable) {
        $this->editable = $editable;
    }


}

?>

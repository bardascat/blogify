<?php

namespace BusinessLogic\Models\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity 
 * @Table(name="task_reminder")
 */
class TaskReminder extends AbstractEntity {

    /**
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    private $id_reminder;

    /**
     * @Column(type="text")
     */
    protected $reminder_description;

    /**
     * @Column(type="datetime")
     */
    protected $reminder_date;

    /**
     * @Column(type="datetime")
     */
    protected $cDate;

    /**
     * @Column(type="integer")
     */
    protected $sent = 0;

    /**
     * @ManyToOne(targetEntity="Task",inversedBy="reminders")
     * @JoinColumn(name="id_task", referencedColumnName="id_task" ,onDelete="CASCADE")
     */
    private $task;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="id_operator", referencedColumnName="id_user" ,onDelete="CASCADE")
     */
    private $operator;

    function __construct() {
        $this->cDate = new \DateTime("now");
    }

    public function getSent() {
        return $this->sent;
    }

    public function setSent($sent) {
        $this->sent = $sent;
    }

    public function getId_reminder() {
        return $this->id_reminder;
    }

    public function getName() {
        return $this->name;
    }

    public function getReminder_date() {
        return $this->reminder_date;
    }

    public function getTask() {
        return $this->task;
    }

    public function setId_reminder($id_reminder) {
        $this->id_reminder = $id_reminder;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setReminder_date($reminder_date) {
        $this->reminder_date = $reminder_date;
    }

    public function setTask($task) {
        $this->task = $task;
    }

    public function getOperator() {
        return $this->operator;
    }

    public function setOperator($operator) {
        $this->operator = $operator;
    }

    public function getReminder_description() {
        return $this->reminder_description;
    }

    public function setReminder_description($reminder_description) {
        $this->reminder_description = $reminder_description;
    }

}

?>

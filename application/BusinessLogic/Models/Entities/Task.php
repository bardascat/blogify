<?php

namespace BusinessLogic\Models\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity 
 * @Table(name="task")
 */
class Task extends AbstractEntity {

    /**
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    private $id_task;

    /**
     * @Column(type="string")
     */
    protected $name;

    /**
     * @Column(type="text")
     */
    protected $content;

    /**
     * @Column(type="integer")
     */
    protected $status = 1;

    /**
     * @Column(type="datetime")
     */
    protected $dueDate;

    /**
     * @Column(type="datetime",nullable=true)
     */
    protected $completedDate;

    /**
     * data creare
     * @Column(type="datetime")
     */
    protected $cdate;

    /**
     * @ManyToOne(targetEntity="TaskList",inversedBy="tasks")
     * @JoinColumn(name="id_list", referencedColumnName="id_list" ,onDelete="CASCADE")
     */
    private $taskList;

    /**
     * @ManyToOne(targetEntity="User",inversedBy="toDoTask")
     * @JoinColumn(name="id_operator", referencedColumnName="id_user" ,onDelete="CASCADE")
     */
    private $operator;

    /**
     * @ManyToOne(targetEntity="Serviciu")
     * @JoinColumn(name="id_serviciu", referencedColumnName="id_serviciu" ,onDelete="CASCADE")
     */
    private $serviciu;

    /**
     * @ManyToOne(targetEntity="Pachet")
     * @JoinColumn(name="id_pachet", referencedColumnName="id_pachet" ,onDelete="CASCADE")
     */
    private $pachet;

    /**
     * @ManyToOne(targetEntity="User",inversedBy="requestedTask")
     * @JoinColumn(name="id_client", referencedColumnName="id_user" ,onDelete="CASCADE")
     */
    private $client;

    /**
     * @OneToMany(targetEntity="TaskReminder",mappedBy="task",cascade={"persist","merge"})
     * @OrderBy({"reminder_date" = "desc"})
     */
    private $reminders;

    /**
     * @OneToMany(targetEntity="TaskNotes",mappedBy="task",cascade={"persist","merge"})
     * @OrderBy({"stamp" = "desc"})
     */
    private $notes;

    function __construct() {
        $this->reminders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cdate = new \DateTime("now");
    }

    public function addNotes(TaskNotes $note) {
        $this->notes->add($note);
        $note->setTask($this);
    }

    public function getNotes() {
        return $this->notes;
    }

    public function addReminder(TaskReminder $taskReminder) {
        $this->reminders->add($taskReminder);
        $taskReminder->setTask($this);
    }

    public function getReminders() {
        return $this->reminders;
    }

    public function getName() {
        return $this->name;
    }

    public function getContent() {
        return $this->content;
    }

    public function getOperator() {
        return $this->operator;
    }

    public function setOperator($operator) {
        $this->operator = $operator;
    }

    /**
     * Cauta task dupa id.
     * @param  id int
     * @return User
     */
    public function getClient() {
        return $this->client;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setClient($client) {
        $this->client = $client;
    }

    public function setTaskList($tasklist) {
        $this->taskList = $tasklist;
    }

    public function getTaskList() {
        return $this->taskList;
    }

    public function getId_task() {
        return $this->id_task;
    }

    public function getDueDate() {
        return $this->dueDate;
    }

    public function setId_task($id_task) {
        $this->id_task = $id_task;
    }

    public function setDueDate($dueDate) {
        $this->dueDate = $dueDate;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getServiciu() {
        return $this->serviciu;
    }

    public function getPachet() {
        return $this->pachet;
    }

    public function setServiciu($serviciu) {
        $this->serviciu = $serviciu;
    }

    public function setPachet($pachet) {
        $this->pachet = $pachet;
    }

    public function getCompletedDate() {
        return $this->completedDate;
    }

    public function setCompletedDate($completedDate) {
        $this->completedDate = $completedDate;
    }

}

?>

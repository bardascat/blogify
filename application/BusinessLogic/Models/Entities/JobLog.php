<?php

namespace BusinessLogic\Models\Entities;

/**
 * @Entity 
 * @Table(name="jobs_log")
 */
use Doctrine\Common\Collections\ArrayCollection;

class JobLog extends AbstractEntity {

    /**
     * @Id  @Column(type="integer")
     * @GeneratedValue
     */
    private $id_job_log;

    /**
     * @Column(type="string",nullable=true)     
     */
    protected $controller;
    
    /**
     * @Column(type="string",nullable=true)     
     */
    protected $method;
    
    /**
     * @Column(type="text",nullable=true)     
     */
    protected $data;
    
    /**
     * @Column(type="datetime")     
     */
    protected $cDate;
    
    
    function __construct() {
        $this->cDate=new \DateTime("now");
    }
    public function getId_job_log() {
        return $this->id_job_log;
    }

    public function getController() {
        return $this->controller;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getData() {
        return $this->data;
    }

    public function getCDate() {
        return $this->cDate;
    }

    public function setId_job_log($id_job_log) {
        $this->id_job_log = $id_job_log;
    }

    public function setController($controller) {
        $this->controller = $controller;
    }

    public function setMethod($method) {
        $this->method = $method;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function setCDate($cDate) {
        $this->cDate = $cDate;
    }


    
    

}

?>

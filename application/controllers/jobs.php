<?php

class jobs extends PUBLIC_Controller {

    private $taskModel;
    private $jobModel;

    public function __construct() {


        parent::__construct();
        $this->taskModel = new \BusinessLogic\Models\TaskModel();
        $this->jobModel = new \BusinessLogic\Models\JobModel();
       // if ($this->input->get("c") != App_constants::$JOB_CONTROLLER_PASS) {
        //    show_404();
        //}
    }

    public function index() {
        $data = array();
    }

    public function sendTaskReminders() {

        $this->jobModel->sendTaskReminders();
    }

    public function testEmail() {
        $to = array('bardascat@gmail.com');
        \NeoMail::genericMail("test", "test2asd", $to);
    }

}

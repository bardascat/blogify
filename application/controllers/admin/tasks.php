<?php

class Tasks extends MY_Controller {

    private $root_id = 1;
    private $aPost = array();
    private $TaskModel;

    function __construct() {
        parent::__construct();
        copyPost($this->aPost);
        $this->TaskModel = new \BusinessLogic\Models\TaskModel();
    }

    public function getTasksList() {
        switch ($this->input->get('node')) {
            //get all list
            case "root": {
                    $r = $this->TaskModel->getRootTaskLists($this->auth->getUserDetails()->getId_user());
                }break;
            default: {
                    //get lists
                    $r = $this->TaskModel->getTasksFromList($this->input->get('node'));
                }
        }

        echo json_encode($r);
    }

    public function getTaksListsCombo() {
        $list_type = false;
        if (isset($this->aPost['list_type'])) {
            switch ($this->aPost['list_type']) {
                case "pending": {
                        $list_type = App_constants::$TASKLIST_PENDING;
                    }break;
                case "closed": {
                        $list_type = App_constants::$TASKLIST_CLOSED;
                    }break;
            }
        }

        $r = $this->TaskModel->getRootTaskLists($this->auth->getUserDetails()->getId_user(), $list_type);

        $data = array(
            'totalCount' => $r,
            'data' => $r
        );

        echo json_encode($data);
    }

    public function newTaskList() {
        $this->aPost['id_parent'] = $this->root_id;
        $this->aPost['id_user'] = $this->auth->getUserDetails()->getId_user();
        $this->TaskModel->addTaskList($this->aPost);
        $this->showExtjsMessage("success", "Lista a fost creata cu succes");
    }

    public function getTasksGrid() {
        $r = $this->TaskModel->getTasksGrid($this->aPost, $this->auth->getUserRoles(), $this->auth->getUserDetails());
        echo json_encode($r);
    }

    public function getTaskLog() {
        $r = $this->TaskModel->getTasklog($this->aPost);
        echo json_encode($r);
    }

    public function addReminder() {
        $this->TaskModel->addReminder($this->aPost, $this->auth->getUserDetails());
        $this->showExtjsMessage("success", "Reminder-ul a fost setat cu succes");
    }

    public function getTaskReminders() {
        $r = $this->TaskModel->getTaskReminders($this->aPost);
        echo json_encode($r);
    }

    public function addTaskNote() {
        $this->TaskModel->addTaskNote($this->aPost, $this->auth->getUserDetails());
        $this->showExtjsMessage("success", "Observatia a fost salvata cu succes");
    }

    public function newTask() {

        $this->TaskModel->newTask($this->aPost, $this->auth->getUserDetails());
        if ($this->aPost['id_task'])
            $this->showExtjsMessage("success", "Task-ul a fost modificat cu succes.");
        else
            $this->showExtjsMessage("success", "Task-ul a fost creat cu succes.");
    }

    public function cancelTask() {
        $this->TaskModel->cancelTask($this->aPost);
        $this->showExtjsMessage("success", "Task-ul a fost anulat cu succces");
    }

    public function closeTask() {
        $task = $this->TaskModel->closeTask($this->aPost);
        $notifications = $task->getClient()->getUserNotifications();
        if (!count($notifications))
            $notificare = " Clientul nu a ales modul de notificare";
        else {
            $notificare = " Clientul a ales sa fie notificat prin: ";
            $this->TaskModel->notificareUser($task,$notifications);

            foreach ($notifications as $not) {
                switch ($not->getType()) {
                    case App_constants::$NOTIFICATION_EMAIL: {
                            $notificare.=" EMAIL,";
                        }break;
                    case App_constants::$NOTIFICATION_PHONE: {
                            $notificare.=" TELEFON,";
                        }break;
                    case App_constants::$NOTIFICATION_SMS: {
                            $notificare.=" SMS,";
                        }break;
                    case App_constants::$CONT_HELPIE: {
                            $notificare.=" CONT HELPIE,";
                        }break;
                }
            }
        }
        $this->showExtjsMessage("success", "Task-ul a fost inchis cu succes si mutat in lista 'Closed task'." . $notificare);
    }

    public function getTask() {
        $task = $this->TaskModel->getTaskByPkForExtjs($this->aPost['id_task']);
        echo json_encode(array(
            "success" => true,
            "error" => false,
            "description" => "ok",
            "task" => $task
        ));
        exit();
    }

}

/* End of file user.php */
/* Location: ./application/controllers/user.php */

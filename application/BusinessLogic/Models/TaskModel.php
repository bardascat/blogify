<?php

/**
 * @author Bardas Catalin
 * date: 05 feb 2014
 */

namespace BusinessLogic\Models;

class TaskModel extends AbstractModel {

    /**
     * Cauta category dupa id.
     * @param  id int
     * @return Entities\Tasklist
     */
    public function getTaskListByPk($id) {
        $cat = $this->em->find("Entities:TaskList", $id);
        if (!$cat)
            return false;
        else
            return $cat;
    }

    /**
     * Cauta task dupa id.
     * @param  id int
     * @return Entities\Task
     */
    public function getTaskByPkForExtjs($id) {
        try {
            $r1 = $this->em->createQueryBuilder()
                            ->select("t,operator,client,taskList,pachet,serviciu")
                            ->from("Entities:Task", "t")
                            ->join("t.operator", "operator")
                            ->join("t.client", "client")
                            ->join("t.taskList", "taskList")
                            ->join("t.serviciu", "serviciu")
                            ->join("t.pachet", "pachet")
                            ->where("t.id_task=:id_task")
                            ->setParameter(":id_task", $id)
                            ->getQuery()->getArrayResult();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return $r1;
    }

    /**
     * Cauta task dupa id.
     * @param  id int
     * @return Entities\Task
     */
    public function getTaskByPk($id) {
        $cat = $this->em->find("Entities:Task", $id);
        if (!$cat)
            return false;
        else
            return $cat;
    }

    /**
     * Verificam daca o categorie are subcategorii
     * @param type $id_category
     * @return boolean
     */
    public function hasChilds($id_category) {
        $result = $this->em->createQuery("select 1 from Entities:TaskList c where c.id_parent=:id_parent")
                ->setParameter("id_parent", $id_category)
                ->getResult();
        if (empty($result))
            return false;
        else
            return true;
    }

    public function addTaskList($post) {
        try {
            $id_parent = $post['id_parent'];
            $parent = $this->em->find("Entities:TaskList", $id_parent);
            $category = new Entities\Tasklist();

            $category->setName($post['name']);
            $category->setId_user($post['id_user']);

            if ($id_parent)
                $category->setId_parent($id_parent);

            $id = $this->getNextId("task_list", "id_list");

            $category->setParentList($parent);
            $this->em->persist($category);
            $this->em->flush($category);
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit();
        }
        return true;
    }

    public function getTasksGrid($aPost, $aRoles, $oUser) {
        $aColumnMapping = array(
            array("table" => false, "col" => "CONCAT(client.lastname, ' ',client.firstname)", "ref" => "client_lastname"),
            array("table" => false, "col" => "CONCAT(operator.lastname, ' ',operator.firstname)", "ref" => "operator_lastname"),
            array("table" => "task_list", "col" => "name", "ref" => "tasklist_name")
        );



        $dql = $this->em->getConnection()->createQueryBuilder();
        $dql->select("t.id_task,t.name,t.dueDate,t.status,t.completedDate, "
                        . "client.firstname as client_firstname,client.lastname as client_lastname,"
                        . "operator.firstname as operator_firstname,operator.lastname as operator_lastname, task_list.name as tasklist_name")
                ->from("task", "t")
                ->join("t", "users", "client", "t.id_client=client.id_user")
                ->join("t", "users", "operator", "t.id_operator=operator.id_user")
                ->join("t", "task_list", "task_list", "t.id_list=task_list.id_list");


        
        if (isset($aPost['operator'])) {
            $dql->andWhere("t.id_operator=" . $aPost['operator']);
        } else if (isset($aPost['client'])) {
            $dql->andWhere("t.id_client=" . $aPost['client']);
        } else {
            //daca nu e admin trebuie sa le vada doar pe ale lui
            if (!in_array(\App_constants::$ADMIN_ROLE, $aRoles)) {
                $dql->andWhere("t.id_operator=" . $oUser->getId_user());
            }
        }

        if (isset($aPost['filter'])) {
            $filters = json_decode($aPost['filter']);
            foreach ($filters as $key => $filter) {
                if ($filter->field == "status") {
                    switch (strtolower($filter->value)) {
                        case "canceled": {
                                $filter->value = \App_constants::$TASKSTATUS_CANCELED;
                            }break;
                        case "pending": {
                                $filter->value = \App_constants::$TASKSTATUS_PENDING;
                            }break;
                        case "close": {
                                $filter->value = \App_constants::$TASKSTATUS_CLOSED;
                            }break;
                    }
                    $filters[$key] = $filter;
                }
            }
            $aPost['filter'] = json_encode($filters);
        }



        //determinam din ce lista sa afisam taskurile
        switch ($aPost['id_list']) {
            case "root": {
                    //le afisam pe toate
                }break;
            case \App_constants::$TASKLIST_PENDING: {
                    //afisam toate taskurile cu status pending, infiderent de lista
                    $dql->andWhere("t.status=" . \App_constants::$TASKSTATUS_PENDING);
                }break;
            case \App_constants::$TASKLIST_CANCELED: {
                    //afisam toate taskurile cu status pending, infiderent de lista
                    $dql->andWhere("t.status=" . \App_constants::$TASKSTATUS_CANCELED);
                }break;
            case \App_constants::$TASKLIST_CLOSED: {
                    //afisam toate taskurile cu status pending, infiderent de lista
                    $dql->andWhere("t.status=" . \App_constants::$TASKSTATUS_CLOSED);
                }break;
            default: {
                    $dql->andWhere("t.id_list=" . $aPost['id_list']);
                }break;
        }



        $filters = $this->getGridFilterParams($aPost);


        $this->gridFiltersExt($dql, $filters, $aColumnMapping);


        $result = $dql->execute()->fetchAll();

        $totalCount = $this->getFoundRows();
        $data = array(
            'totalCount' => $totalCount,
            'data' => $result
        );

        return $data;
    }

    public function getTasklog($aPost) {
        $aColumnMapping = array(
            array("table" => false, "col" => "CONCAT(operator.lastname, ' ',operator.firstname)", "ref" => "operator_lastname"),
            array("table" => "task", "col" => "name", "ref" => "taskname")
        );


        $dql = $this->em->getConnection()->createQueryBuilder();
        $dql->select("n.*,task.name as taskname,CONCAT(users.lastname, ' ',users.firstname) as username")
                ->from("task_note", "n")
                ->join("n", "task", "task", "n.id_task=task.id_task")
                ->join("n", "users", "users", "n.id_operator=users.id_user")
                ->where("n.id_task=" . $aPost['id_task']);


        $filters = $this->getGridFilterParams($aPost);


        $this->gridFiltersExt($dql, $filters, $aColumnMapping);



        $result = $dql->execute()->fetchAll();

        $totalCount = $this->getFoundRows();
        $data = array(
            'totalCount' => $totalCount,
            'data' => $result
        );

        return $data;
    }

    public function getTaskReminders($aPost) {
        $aColumnMapping = array(
            array("table" => false, "col" => "CONCAT(users.lastname, ' ',users.firstname)", "ref" => "operator"),
        );

        $dql = $this->em->getConnection()->createQueryBuilder();
        $dql->select("reminder.*,task.name as taskname,CONCAT(users.lastname, ' ',users.firstname) as operator")
                ->from("task_reminder", "reminder")
                ->join("reminder", "task", "task", "reminder.id_task=task.id_task")
                ->leftJoin("reminder", "users", "users", "reminder.id_operator=users.id_user")
                ->where("reminder.id_task=" . $aPost['id_task']);


        $filters = $this->getGridFilterParams($aPost);


        $this->gridFiltersExt($dql, $filters, $aColumnMapping);



        $result = $dql->execute()->fetchAll();

        $totalCount = $this->getFoundRows();
        $data = array(
            'totalCount' => $totalCount,
            'data' => $result
        );

        return $data;
    }

    public function addReminder($aPost, $operator) {
        $task = $this->getTaskByPk($aPost['id_task']);
        $reminder = new Entities\TaskReminder();
        $reminder->postHydrate($aPost);
        $reminder->setOperator($operator);
        $task->addReminder($reminder);

        $this->em->persist($task);
        $this->em->flush();
    }

    public function deleteTaskList($id_category) {
        $this->em->createQuery("delete from Entities:TaskList c where c.id_list='$id_category'")->execute();
        return true;
    }

    public function updateTaskList($post) {
        try {
            $category = $this->em->find("Entities:TaskList", $post['id_list']);
            $category->setName($post['name']);
            $this->em->persist($category);
            $this->em->flush();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return true;
    }

    public function addTaskNote($aPost, $operator) {
        $task = $this->getTaskByPk($aPost['id_task']);
        $note = new Entities\TaskNotes();
        $note->setContent($aPost['content']);
        $note->setOperator($operator);
        $task->addNotes($note);

        $this->em->persist($task);
        $this->em->persist($note);

        $this->em->flush();
    }

    /**
     * Intoarce lista categoriilor e taskuri 
     * @return Array
     */
    public function getRootTaskLists($id_user, $list_type = false) {

        try {
            //luam intai categoriile vizibile pentru toti userii
            $r1 = $this->em->createQueryBuilder()
                    ->select("p,p.name as text,p.id_list as id,p.name,p.id_list, 'true' as leaf, 'list' as cls ")
                    ->from("Entities:TaskList", "p")
                    ->where("p.id_parent=:id_parent")
                    ->andWhere("p.editable=0")
                    ->setParameter("id_parent", 1);
            if ($list_type) {
                $r1->andWhere("p.id_list=:id_list");
                $r1->setParameter("id_list", $list_type);
            }
            $r1 = $r1->getQuery()->getArrayResult();

            $r2 = $this->em->createQueryBuilder()
                    ->select("p,p.name as text,p.name,p.id_list,p.id_list as id, 'true' as leaf, 'list' as cls ")
                    ->from("Entities:TaskList", "p")
                    ->where("p.id_parent=:id_parent")
                    ->andWhere("p.id_user=:id_user")
                    ->setParameter("id_parent", 1)
                    ->setParameter("id_user", $id_user);
            if ($list_type) {
                $r2->andWhere("p.id_list=:id_list");
                $r2->setParameter("id_list", $list_type);
            }
            $r2 = $r2->getQuery()->getArrayResult();

            $r = array_merge($r1, $r2);

            return $r;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getTaskFromlist($id_list) {
        try {
            $r = $this->em->createQueryBuilder()
                            ->select("t")
                            ->from("Entities:Task", "t")
                            ->where("t.id_list=:id_list")
                            ->setParameter("id_list", $id_list)
                            ->getQuery()->getArrayResult();
            return $r;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function newTask($aPost, $currentUser) {

        try {
            //update or delete task
            if (isset($aPost['id_task']) && $aPost['id_task'])
                $task = $this->getTaskByPk($aPost['id_task']);
            else
                $task = new Entities\Task();

            //daca adminula ales operator
            if (!$aPost['id_operator']){
                $task->setOperator($currentUser);
            }
            else {
                $operator = $this->em->find("Entities:User", $aPost['id_operator']);
                $task->setOperator($operator);
            }
            $task->postHydrate($aPost);
            $client = $this->em->find("Entities:User", $aPost['id_client']);
            $task->setClient($client);

            $pachet = $this->em->find("Entities:Pachet", $aPost['id_pachet']);
            $serviciu = $this->em->find("Entities:Serviciu", $aPost['id_serviciu']);

            $task->setPachet($pachet);
            $task->setServiciu($serviciu);

            $taskList = $this->getTaskListByPk($aPost['id_list']);

            //daca se schimba una din aceste liste se schimba si statusul
            switch ($taskList->getId_list()) {
                case \App_constants::$TASKLIST_CANCELED: {
                        $task->setStatus(\App_constants::$TASKSTATUS_CANCELED);
                    }break;
                case \App_constants::$TASKLIST_CLOSED: {
                        $task->setStatus(\App_constants::$TASKSTATUS_CLOSED);
                    }break;
                case \App_constants::$TASKLIST_PENDING: {
                        $task->setStatus(\App_constants::$TASKSTATUS_PENDING);
                    }break;
            }
            
            
            $task->setTaskList($taskList);
            $this->em->persist($task);
            $this->em->flush();
       
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
    }

    public function updateTask($aPost, $currentUser) {
        
    }

    public function cancelTask($aPost) {
        $task = $this->getTaskByPk($aPost['id_task']);
        $cancelList = $this->getTaskListByPk(\App_constants::$TASKLIST_CANCELED);
        $task->setStatus(\App_constants::$TASKSTATUS_CANCELED);
        $task->setTaskList($cancelList);

        $this->em->persist($task);
        $this->em->flush();
        return $task;
    }

    /**
     * Cauta task dupa id.
     * @param  id int
     * @return Entities\Task
     */
    public function closeTask($aPost) {
        $task = $this->getTaskByPk($aPost['id_task']);

        $closedList = $this->getTaskListByPk(\App_constants::$TASKLIST_CLOSED);
        $task->setStatus(\App_constants::$TASKSTATUS_CLOSED);
        $task->setTaskList($closedList);
        $task->setCompletedDate(new \DateTime("now"));
        $this->em->persist($task);
        $this->em->flush();
        return $task;
    }

    public function notificareUser(Entities\Task $task, $notifications) {

        foreach ($notifications as $not) {
            switch ($not->getType()) {
                case \App_constants::$NOTIFICATION_EMAIL: {
                        $client = $task->getClient();
                        $body = "Buna Ziua, <br/> Va informam ca taskul " . $task->getName() . " a fost finalizat cu success.";
                        $title = "Taskul " . $task->getName() . " finalizat !";
                        \NeoMail::genericMail($body, $title, $client->getEmail());
                    }break;
                case \App_constants::$CONT_HELPIE: {
                        $client = $task->getClient();
                        $message = new Entities\Email();
                        $message->setContent("Buna Ziua, <br/> Va informam ca taskul <b>" . $task->getName() . "</b> a fost finalizat cu success.");
                        $message->setTitle("Taskul " . $task->getName() . " finalizat !");
                        $message->setTo($client);
                        $message->setFrom($task->getOperator());
                        $this->em->persist($message);
                        $this->em->flush();
                    }break;
            }
        }
    }

    /**
     * 
     * @return Entities\User
     */
    public function getAvailableOperator() {
        $sql = "select users.*,
(
select count(*) from task where task.id_operator=users.id_user and task.status=2
) as nr_tasks
from users 
join user_rol on(users.id_user=user_rol.id_user)
where users.user_activ=".\App_constants::$TASKSTATUS_PENDING." 
and user_rol.rol_id=3 
group by users.id_user order by nr_tasks asc";
     
        $users = $this->em->getConnection()->fetchAll($sql);

        if (isset($users[0])) {
            return $this->em->find("Entities:User", $users[0]['id_user']);
        } else {
            //cautam adminul daca nu am gasit niciun operator
            try {
                $admin = $this->em->createQueryBuilder()
                                ->select("u")
                                ->from("Entities:User", "u")
                                ->join("u.roluri", 'roluri')
                                ->where("roluri.rol_nume='admin'")
                                ->getQuery()->getResult();


                if ($admin)
                    return $admin[0];
                else
                    return false;
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
    }

}

?>

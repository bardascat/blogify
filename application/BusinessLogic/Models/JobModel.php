<?php

namespace BusinessLogic\Models;

use DoctrineExtensions\Query\Mysql\Month;

/**
 *
 * @author Neo aka Bardas Catalin
 */
class JobModel extends AbstractModel {

    public function log(Entities\JobLog $jobLog) {
        $this->em->persist($jobLog);
        $this->em->flush();
    }

    /**
     * Trimite operatorilor email cu reminderele setate
     */
    public function sendTaskReminders() {


        $date = date("Y.m.d H");
        //$date="2014.10.28 14";
        try {
            $qb = $this->em->createQueryBuilder();
            $qb->select("reminders")
                    ->from("Entities:TaskReminder", "reminders")
                    ->where("DATE_FORMAT(reminders.reminder_date,'%Y.%m.%d %H')=:cDate")
                    ->andWhere("reminders.sent=0")
                    ->setParameter("cDate", $date);

            $info = "";
            $r = $qb->getQuery()->execute();

            $info.="Am gasit " . count($r) . " remindere <br/>";

            if (count($r)) {
                foreach ($r as $reminder) {
                    $to = $reminder->getTask()->getOperator()->getEmail();
                    $task_name = $reminder->getTask()->getName();
                    $client_name = $reminder->getTask()->getClient()->getFirstname() . ' ' . $reminder->getTask()->getClient()->getLastname();
                    $client_phone = $reminder->getTask()->getClient()->getPhone();
                    $task_reminder_date = $reminder->getReminder_date();

                    $reminder_desc = $reminder->getReminder_description();
                    $body = "<p style='font-size:12px; font-family:Arial'>";

                    $body.="Reminder task: " . $task_name . "<br/><br/>";
                    $body.="Clientul " . $client_name . " asteapta taskul cu numele <b>" . $task_name . "</b><br/>";
                    $body.="Data reminder: " . $task_reminder_date->format("d-m-Y H:h") . "<br/>";
                    $body.="Mesaj reminder:" . $reminder_desc . "<br/>";

                    $body.="<br/><br/> <b>Mesaj Automat</b><p>";
                    $subject = "Reminder task: " . $task_name;

                    \NeoMail::genericMail($body, $subject, \App_constants::$OFFICE_EMAIl);
                    
                    $info.="Notificare " . print_r($to, true) . " task: " . $task_name . "<br/>";
                    $reminder->setSent(1);
                    $this->em->persist($reminder);
                    $this->em->flush();
                }
            }

            $log = new Entities\JobLog();
            $log->setController("jobs");
            $log->setMethod("sendTaskReminders");
            $log->setData($info);
            $this->log($log);
            echo $info;
        } catch (\Exception $e) {
            $log = new Entities\JobLog();
            $log->setController("jobs");
            $log->setMethod("sendTaskReminders");
            $log->setData($e->getMessage());
            $this->log($log);
            echo $e->getMessage();
        }
    }

}

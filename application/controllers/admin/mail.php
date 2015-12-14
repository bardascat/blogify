<?php

class Mail extends MY_Controller {

    private $aPost = array();
    private $EmailModel;

    function __construct() {
        parent::__construct();
        copyPost($this->aPost);
        $this->EmailModel = new \BusinessLogic\Models\EmailModel();
    }

    public function getEmailsForGrid() {
        $data = $this->EmailModel->getUserEmailGrid($this->aPost['email_type'], $this->auth->getUserDetails()->getId_user(), $this->aPost);
        echo json_encode($data);
        exit();
    }

    public function newEmail() {
       
        $result = $this->EmailModel->sendEmail($this->auth->getUserDetails(),$this->aPost);

        
        if ($result['status'])
            $this->showExtjsMessage("success", "Mesajul a fost trimis cu succes lui " . $result['to']->getFirstname());
        else
            $this->showExtjsMessage("error", "Eroare mesajul nu a fost trimis. Va rugam reincercati.");
    }

    public function deleteEmail() {
        $this->EmailModel->deleteEmail($this->aPost);
        $this->showExtjsMessage("success", "Mesajul a fost sters cu succes");
    }

    public function getEmailContent() {
        $email = $this->EmailModel->getEmail($this->aPost['id_email']);

        $wrapper = "";
        $from = '<div style="width:100%; background-color:#E6EEF7;font-size:11px; font-family:Tahoma; padding:2px; padding-left:6px; border-bottom:1px solid #BED2EA;"><b>De la </b>: ' . $email->getFrom()->getFirstname() . " " . $email->getFrom()->getLastname() . '</div>';
        $to = '<div style="width:100%; background-color:#E6EEF7;font-size:11px; font-family:Tahoma; padding:2px; padding-left:6px; border-bottom:1px solid #BED2EA;"><b>Catre</b>: ' . $email->getTo()->getFirstname() . " " . $email->getTo()->getLastname() . '</div>';
        $at = '<div style="width:100%; background-color:#E6EEF7; font-size:11px; font-family:Tahoma; padding:2px; padding-left:6px; border-bottom:1px solid #BED2EA;"><b>In data </b>:' . $email->getCDate()->format("d-m-Y H:i:s") . '</div>';
        $subject = '<div style="width:100%; background-color:#E6EEF7; font-size:11px; font-family:Tahoma; padding:2px;padding-left:6px; border-bottom:1px solid #BED2EA;"><b>Subiect: </b>' . $email->getTitle() . '</div>';
        $wrapper.=$at . $from .$to. $subject;

        $wrapper.="<div style='font-size:11px; font-family:Tahoma; padding:6px;'>";

        $wrapper.=$email->getContent();
        $wrapper.="</div>";

        echo $wrapper;
    }

    public function getReplayData() {

        $email = $this->EmailModel->getEmail($this->aPost['id_email']);

        $wrapper = "<p >&nbsp;</p><div style='margin-top:20px; border-top:1px solid #ccc; padding-top:10px; width:100%;'></div>";
        $from = '<div style="font-size:11px; font-family:Tahoma; padding:2px; padding-left:6px; "><b>De la </b>: ' . $email->getFrom()->getFirstname() . " " . $email->getFrom()->getLastname() . '</div>';
        $to = '<div style="font-size:11px; font-family:Tahoma; padding:2px; padding-left:6px; "><b>Catre </b>: ' . $email->getTo()->getFirstname() . " " . $email->getTo()->getLastname() . '</div>';
        
        $at = '<div style="  font-size:11px; font-family:Tahoma; padding:2px; padding-left:6px; "><b>In data </b>:' . $email->getCDate()->format("d-m-Y H:i:s") . '</div>';
        $subject = '<div style=" font-size:11px; font-family:Tahoma; padding:2px;padding-left:6px; "><b>Subiect: </b>' . $email->getTitle() . '</div>';
        $wrapper.=$at . $from . $to. $subject;

        $wrapper.="<div style='font-size:11px; font-family:Tahoma; padding:6px;'>";

        $wrapper.=$email->getContent();
        $wrapper.="</div>";

        
        
        echo json_encode(array(
            "success"=>true,
            "error"=>false,
            "data"=>array(
                "body"=>$wrapper,
                "subject"=>"RE:".$email->getTitle(),
                "to_email"=>$email->getFrom()->getId_user()
            )
        ));
    }

}

/* End of file user.php */
/* Location: ./application/controllers/user.php */

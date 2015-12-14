<?php

/** @property  Auth  $auth 
/** @property  View  $view  */
class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('auth');

        $this->view->auth = $this->auth;
        
        if (!$this->auth->isLoggedIn()) {
            //Sesiune expirata sau inexistenta, afiseaza formular de login

            $this->auth->showLogin();
            die();
        }
        if (!$this->auth->isAuthorized()) {
            //Utilizatorul curent nu este autorizat pt acces la url
            //Afiseaza pagina de acces interzis (sau mesaj JSON)
            $this->auth->showAccessRestricted();
            die();
        }
        $this->auth->logAction();
        
        $this->view->setNotification($this->session->flashdata('notification'));
        
    }

      public function load_view($view, $vars = array()) {
        $this->load->view('header', $vars);
        $this->load->view($view, $vars);
        $this->load->view('footer');
        exit();
    }
    
     public function load_view_user($view, $data = array()) {
        $this->load->view('user/header', $data);
        $this->load->view($view, $data);
        if (!isset($data['no_footer']))
            $this->load->view('footer');   
    }

    public function showExtjsMessage($notificationType = "success", $description = "ok", $type = "info") {
        $message = array();
        switch ($notificationType) {
            case "success": {
                    $message['error'] = false;
                    $message['success'] = true;
                }break;
            default: {
                    $message['error'] = true;
                    $message['success'] = false;
                }break;
        }
        $message["description"] = $description;
        $message["type"] = $type;

        echo json_encode($message);
        exit();
    }

    protected function populate_form($object) {


        //repopulate fields
        $js = '<script type="text/javascript"> $(document).ready(function(){';
        $iteration = $object->getIterationArray();

        foreach ($iteration as $key => $value) {

            if (is_object($value)) {
                if (get_class($value) == "DateTime") {
                    if ($key == "start_date" || $key == "end_date")
                        $value = $value->format("d-m-Y H:i:s");
                    else
                        $value = $value->format("d-m-Y");
                }
            }
            //daca in DB e NULL in js apare tot null

            if (is_null($value))
                $value = "";
            $value = json_encode($value);
            $js.='$(":input[name= \'' . $key . '\']").val(' . $value . ');';
        }
        $js.='});</script>';

        $this->view->setPopulate_form($js);
    }

}

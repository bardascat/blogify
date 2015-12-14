<?php

class corporate extends PUBLIC_Controller {

    public function __construct() {

        parent::__construct();
    }

    public function index() {

        $this->view->setMetaTitle("Helpie - Corporate");

        $data = array();

        $this->load_view("corporate/landing", $data);
    }

    public function send() {


        $firstname = $this->input->post('firstname');
        $lastname = $this->input->post('lastname');
        $email = $this->input->post('email');
        $phone = $this->input->post('phone');
        $company = $this->input->post('company');


        if (!$firstname || !$lastname || !$email) {
            $this->session->set_flashdata('notification', array("title" => \BusinessLogic\Util\Language::output("atentie"), "plugin" => "jqueryui", "type" => "error", "html" => \BusinessLogic\Util\Language::output("corporate_completare_date")));
            header("Location: " . base_url('corporate'));
            exit();
        }
        $body = "Salut<br/><br/> Ai primit un mesaj de la <b>$firstname $lastname</b> din pagina de contact helpie corporate<br/><br/>
                            <u>Datele de contact alte userului sunt:</u><br>
                            Nume: $firstname $lastname <br/>
                            Email: $email <br/>    
                            Telefon: $phone <br/>    
                            Company: $company <br/>";

        \NeoMail::genericMail($body, "Helpie Corporate", App_constants::$OFFICE_EMAIl);
        $this->session->set_flashdata('notification', array("title" => \BusinessLogic\Util\Language::output("atentie"), "plugin" => "jqueryui", "type" => "ok", "html" => \BusinessLogic\Util\Language::output("corporate_cerere_trimisa")));

        header("Location: " . base_url('corporate'));
        exit();
    }

}

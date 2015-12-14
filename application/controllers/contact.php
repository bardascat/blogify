<?php

class contact extends PUBLIC_Controller {

    public function __construct() {

        parent::__construct();
    }

    public function index() {
        $data = array();

                $this->view->setMetaTitle("Helpie - Contact");
        
        $this->load_view("contact/landing", $data);
    }

    public function submit() {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mesaj = $_POST['mesaj'];
        $subiect ="Mesaj:";


        $body = "Salut<br/><br/> Ai primit un mesaj de la <b>$name</b> cu adresa de email <b>$email :</b><br/>
                            <br/>$subiect<br>
                            <p>$mesaj</p>";
       
        \NeoMail::genericMail($body, $subiect, App_constants::$OFFICE_EMAIl);
        echo \BusinessLogic\Util\Language::output("contact_mesaj_trimis");
    }

}

<?php

class servicii extends PUBLIC_Controller {

    public function __construct() {

        parent::__construct();
        
                $this->view->setMetaTitle("Helpie - Servicii");
        
    }

    public function index() {
        $data = array();

        $this->load_view("servicii/landing", $data);
    }

}

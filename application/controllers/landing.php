<?php

class landing extends PUBLIC_Controller {

    private $NomenclatorModel;

    public function __construct() {

        parent::__construct();
        $this->view->setMetaTitle("Helpie - Alege fericirea");
        $this->view->setMetaDesc("Helpie este platforma doritorilor de timp liber. Este comunitatea celor care aleg să facă mai mult, mai bine, mai plăcut cu viaţa lor");
        $this->NomenclatorModel = new \BusinessLogic\Models\NomenclatorModel();
    }

    public function index() {

        
        $this->load_view("index/landing", $data);
    }

}

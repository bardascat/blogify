<?php

class pachete extends PUBLIC_Controller {

    private $NomenclatorModel;

    public function __construct() {

        parent::__construct();
        $this->view->setMetaTitle("Helpie - Pachete");

        $this->NomenclatorModel = new \BusinessLogic\Models\NomenclatorModel();
    }

    public function index() {

        $pachete = $this->NomenclatorModel->getPacheteEntity();
        $data = array("pachete" => $pachete);

        $this->load_view("pachete/landing", $data);
    }

}

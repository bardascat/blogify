<?php

class Nomenclator extends MY_Controller {

    private $nomenlatorModel;
    private $aPost;

    public function __construct() {
        parent::__construct();
        $this->nomenlatorModel = new BusinessLogic\Models\NomenclatorModel();
        copyPost($this->aPost);
    }

    public function getPacheteGrid() {
        $result = $this->nomenlatorModel->getPachete($this->aPost);

        echo json_encode(array(
            "data" => $result,
            "totalCount" => count($result)
                )
        );
    }

    public function getGridServicii() {
        $result = $this->nomenlatorModel->getGridServicii($this->aPost);

        echo json_encode(array(
            "data" => $result['data'],
            "totalCount" => $result['totalCount']
                )
        );
        exit();
    }

    public function getServicii() {
        $result = $this->nomenlatorModel->getServicii();

        echo json_encode(array(
            "data" => $result,
            "totalCount" => count($result)
                )
        );
    }

    public function getServiciiPachetGrid() {
        $result = $this->nomenlatorModel->getServiciiPachet($this->aPost);
        echo json_encode(array(
            "data" => $result['data'],
            "totalCount" => $result['totalCount']
                )
        );
        exit();
    }

    public function addPachet() {
        $this->nomenlatorModel->addPachet($this->aPost);
    }

    //asociaza un serviciu la pachet
    public function addServiciu() {

        $status = $this->nomenlatorModel->addServiciu($this->aPost);

        if ($status)
            echo json_encode($this->showExtjsMessage("success", "Serviciul a fost adaugat cu succes"));
        else
            echo json_encode($this->showExtjsMessage("error", "Serviciul este deja adaugat la acest pachet"));
    }

    //creaza un serviciu nou
    public function createServiciu() {
        $this->nomenlatorModel->createServiciu($this->aPost);
        echo json_encode($this->showExtjsMessage("success", "Serviciul a fost creat cu succes"));
    }

    public function getRoluri() {

        $result = $this->nomenlatorModel->getRoluri($this->aPost);

        echo json_encode(array(
            "data" => $result,
            "totalCount" => count($result)
                )
        );
    }

    public function getUserPachete() {
        $result = $this->nomenlatorModel->getUserPachete($this->aPost);
        echo json_encode(array(
            "data" => $result,
            "totalCount" => count($result)
        ));
    }

    public function getPachete() {
        $result = $this->nomenlatorModel->getPachete($this->aPost);
        echo json_encode(array(
            "data" => $result,
            "totalCount" => count($result)
        ));
    }

    public function getUseriCombo() {
        $data = $this->nomenlatorModel->getUseriCombo($this->aPost);
        echo json_encode($data);
    }

    public function getParteneri() {
        $data = $this->nomenlatorModel->getParteneri($this->aPost);
        echo json_encode($data);
    }

    public function deletePachet() {
        $this->nomenlatorModel->deletePachet($this->aPost);
        echo json_encode($this->showExtjsMessage("success", "Pachetul a fost sters cu succes"));
    }

    public function deleteServiciu() {
        $this->nomenlatorModel->deleteServiciu($this->aPost);
        echo json_encode($this->showExtjsMessage("success", "Serviciul a fost sters cu succes"));
    }

    public function getTexte() {


        if ($this->aPost['language'] == "en")
            $xml = file_get_contents("application/language/en/en.xml");

        if ($this->aPost['language'] == "ro")
            $xml = file_get_contents("application/language/ro/ro.xml");

        echo json_encode(array(
            "succes" => true,
            "data" => $xml
        ));
    }

    public function saveTexte() {
        $data = $this->aPost['data'];

        $data = urldecode($data);

        if ($data) {
            
                    
            switch ($this->aPost['language']) {
                case "en": {
                        file_put_contents("application/language/en/en.xml", $data);
                    }break;
                case "ro": {
                        file_put_contents("application/language/ro/ro.xml", $data);
                    }break;
            }
        }
        echo json_encode(array(
            "succes" => true,
            "data" => ""
        ));
    }

}

/* End of file main.php */
/* Location: ./system/application/controllers/main.php */

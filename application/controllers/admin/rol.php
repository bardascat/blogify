<?php
class Rol extends MY_Controller {

    private $aPost = array();

    public function __construct() {
        parent::__construct();
        $this->load->model('rolmodel');
        copyPost($this->aPost);
    }


    /**
     * Functie care aduce datele in grid
     */
    function getData() {
        
        echo json_encode($this->rolmodel->getData($this->aPost));
    }

    /**
     * Functie de editare / inserare element
     * @return string json
     */
    function editRecord() {
        $bResponse = $this->rolmodel->editRecord($this->aPost);

        if ($bResponse === TRUE) {
            $aResponse = array("error" => false, "success" => true, "description" => "Datele au fost salvate");
        }
        else {
            $aResponse = array("error" => true, "description" => $bResponse, "type"=>"Validare");
        }
        echo  json_encode($aResponse);
    }

    /**
     * Functie care aduce datele despre unei inregistrari
     * @return string json
     */
    function getRecord() {
        $aResult = $this->rolmodel->getRecord($this->aPost["rolId"]);
        $aResponse = array("error" => false, "success" => true, "data" => $aResult);
        echo json_encode($aResponse);
    }
}
/* End of file rol.php */
/* Location: ./application/controllers/rol.php */
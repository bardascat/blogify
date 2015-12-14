<?php
class Acces extends MY_Controller {

    private $aPost = array();

    public function __construct() {
        parent::__construct();
        $this->load->model('accesmodel');
        copyPost($this->aPost);
    }


    /**
     * Functie care aduce datele despre permisiuni
     */
    function getData() {
        //salvare permisiune
        if (isset($this->aPost["xaction"]) && ($this->aPost["xaction"] == "update") && (isset($this->aPost["rol_id"])) && ($this->aPost["rol_id"] > 0)) {
            $this->savePermisie();
            return;
        }
        //cautare date
        echo json_encode($this->accesmodel->getData($this->aPost));
    }

    /**
     * Functie de modificare permisie
     * @return string json
     */
    function savePermisie() {
        $bResponse = $this->accesmodel->savePermisie($this->aPost);

        if ($bResponse === TRUE) {
            $aResponse = array("error" => false, "success" => true);
        }
        else {
            $aResponse = array("error" => false, "success" => true);
        }
        echo  json_encode($aResponse);
    }

}
/* End of file acces.php */
/* Location: ./application/controllers/accesphp */
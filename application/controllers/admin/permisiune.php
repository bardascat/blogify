<?php
class Permisiune extends MY_Controller {

    private $aPost = array();

    public function __construct() {
        parent::__construct();
        $this->load->model('permisiunemodel');
        $this->load->helper('file');
        copyPost($this->aPost);
    }


    /**
     * Functie care aduce datele despre permisiuni
     */
    function getData() {
        //salvare permisiune
        if (isset($this->aPost["xaction"]) && ($this->aPost["xaction"] == "update")) {
            $this->aPost = json_decode($this->aPost["data"],TRUE) ;
            $this->editRecord();
            return;
        }
        //sincronizare permisiuni
        //$this->sincPermisiune();
        //cautare date
        echo json_encode($this->permisiunemodel->getData($this->aPost));
    }

    /**
     * Functie de editare / inserare element
     * @return string json
     */
    function editRecord() {
        $bResponse = $this->permisiunemodel->editRecord($this->aPost);

        if ($bResponse === TRUE) {
            $aResponse = array("error" => false, "success" => true, "description" => "Datele au fost salvate");
        }
        else {
            $aResponse = array("error" => true, "description" => $bResponse, "type" => "Validare");
        }
        echo  json_encode($aResponse);
    }



    /**
     * Functie care aduce datele despre unei inregistrari
     * @return string json
     */
    function getRecord() {
        $aResult = $this->permisiunemodel->getRecord($this->aPost["perm_id"]);
        $aResponse = array("error" => false, "success" => true, "data" => $aResult);
        echo json_encode($aResponse);
    }

    /**
     * Functie de sincronizare a permisiunilor
     * @return void
     */
    function sincPermisiune() {
        //creare cale controllere
        $sSafeFile = $this->config->item("file_safe");



        $sControllerPath = str_replace("\\", "/", FCPATH . APPPATH . "controllers");
        $aFiles = get_dir_file_info($sControllerPath,true);
        natcasesort($aFiles);

        //parsare fisiere
        foreach ($aFiles as $sFile) {

            if (in_array($sFile["name"],$sSafeFile)) {
                continue;
            }

            $aFilepath = $sControllerPath . "/" . $sFile["name"];
            $aFileData = pathinfo($aFilepath);

            //teste_nominalizare_client
            if ($aFileData["extension"] == "php") {
                include_once($aFilepath);
                $reflection = new ReflectionClass($aFileData["filename"]);
                $aMethods = $reflection->getMethods();

                if (count($aMethods) > 0) {
                    foreach ($aMethods as $aFunction) {
                        if (($aFunction->name == "__construct") || ($aFunction->name == "get_instance")) {
                            continue;
                        }
                        //inserare permisiuni
                        $this->permisiunemodel->insertSincPermisiune($aFileData["filename"], $aFunction->name);
                    }
                }
            }
        }


	    $sControllerPath = str_replace("\\", "/", FCPATH . APPPATH . "controllers/extinderi");
	    $aFiles = get_dir_file_info($sControllerPath,true);
	    natcasesort($aFiles);

	    //parsare fisiere
	    foreach ($aFiles as $sFile) {

		    if (in_array($sFile["name"],$sSafeFile)) {
			    continue;
		    }

		    $aFilepath = $sControllerPath . "/" . $sFile["name"];
		    $aFileData = pathinfo($aFilepath);

		    //teste_nominalizare_client
		    if ($aFileData["extension"] == "php") {
			    include_once($aFilepath);
			    $reflection = new ReflectionClass($aFileData["filename"]);
			    $aMethods = $reflection->getMethods();

			    if (count($aMethods) > 0) {
				    foreach ($aMethods as $aFunction) {
					    if (($aFunction->name == "__construct") || ($aFunction->name == "get_instance")) {
						    continue;
					    }
					    //inserare permisiuni
					    $this->permisiunemodel->insertSincPermisiune("extinderi/".$aFileData["filename"], $aFunction->name);
				    }
			    }
		    }
	    }


    }

}
/* End of file permisiune.php */
/* Location: ./application/controllers/permisiune.php */
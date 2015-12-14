<?php

class User extends MY_Controller {

    private $aPost = array();
    private $UserModel;

    public function __construct() {
        parent::__construct();
        $this->UserModel = new BusinessLogic\Models\UserModel();
        $this->load->model('rolmodel');
        copyPost($this->aPost);
    }

    public function getTransactions() {
        $r=$this->UserModel->getTransactionsForGrid($this->aPost);
        echo json_encode($r);
       
    }

    /**
     * Functie care aduce datele in grid
     */
    function getData() {
        echo json_encode($this->UserModel->getGridData($this->aPost));
    }

    /**
     * Functie care aduce rolurile
     * @return void
     */
    function getRol() {
        $aRez = $this->rolmodel->getData($this->aPost);
        echo json_encode($aRez);
    }

    /**
     * Functie de editare / inserare element
     * @return string json
     */
    function editUser() {

        //verificari date user
        if (isset($this->aPost["newPassword"]) && ($this->aPost["newPassword"] != "")) {
            $bResult = $this->auth->checkPasswordStruct($this->aPost["newPassword"]);
            if ($bResult !== TRUE) {
                $aResponse = array(
                    "error" => true,
                    "description" => $bResult,
                    "type" => "Validare"
                );
                echo json_encode($aResponse);
                return;
            }
        }



        $bResponse = $this->UserModel->editUser($this->aPost);

        if (is_a($bResponse, "\BusinessLogic\Models\Entities\User")) {
            $aResponse = array(
                "error" => false,
                "success" => true,
                "user" => $bResponse->generateStdObject(),
                "description" => "Datele au fost salvate"
            );
        } else {
            $aResponse = array(
                "error" => true,
                "description" => $bResponse
            );
        }
        echo json_encode($aResponse);
    }

    /**
     * Functie care aduce datele despre unei inregistrari
     * @return string json
     */
    function getUser() {
        //date user
        $oUser = $this->UserModel->getUserByPk($this->aPost["userId"]);
        $aRol = $oUser->getRoluri();

        //roluri  user
        $aResultRol = array();
        if (count($aRol)) {
            foreach ($aRol as $aUserRole) {
                $rol_nume = $aUserRole->getRol_nume();
                $rol_id = $aUserRole->getRol_id();
                $aResultRol[] = $aUserRole->getRol_id();
            }
        }



        $oUser = $oUser->generateStdObject();
        $oUser->user_rol = implode(",", $aResultRol);
        $oUser->rol_id_val = $rol_nume;
        $oUser->rol_id = $rol_id;
        $aResponse = array(
            "error" => false,
            "success" => true,
            "data" => $oUser,
            "type" => "silent"
        );
        echo json_encode($aResponse);
    }

    /**
     * Functie care seteaza theme-ul unui user
     */
    function setStyle() {
        $this->usermodel->setStyle($this->aPost["style"]);
    }

    /**
     * functie de salvare a raspunsurilor de securitate
     */
    function saveSecurityAnswer() {
        $this->usermodel->saveSecurityAnswer($this->aPost);
        $aResponse = array(
            "error" => false,
            "success" => true,
            "description" => "Datele au fost salvate"
        );
        echo json_encode($aResponse);
    }

    /**
     * functie de resetare parola
     */
    function resetPasswd() {
        $iUser = $this->input->post("userId");
        $bResult = $this->UserModel->resetPassword($iUser);

        if ($bResult !== FALSE) {
            $aResponse = array(
                "error" => false,
                "success" => true,
                "description" => "Noua parola este :<b>" . $bResult . "</b>"
            );
            echo json_encode($aResponse);
            return;
        }

        $aResponse = array(
            "error" => true,
            "success" => false,
            "description" => "Nu s-a putut genera parola"
        );
        echo json_encode($aResponse);
        return;
    }

}

/* End of file user.php */
/* Location: ./application/controllers/user.php */

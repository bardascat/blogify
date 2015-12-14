<?php

class Main extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("accesmodel");
    }

   
    function index() {



        $data = array();
        $data['security_answer'] = 0;
        $data['este_campanie'] = 0;

        $aUserDetails = $this->auth->getUserDetails();

        $aRoles = $this->session->userdata("user_roles");

        //e($this->session->userdata("user_roles"));

        /*
          //daca userul nu si-a activat raspunsurile atunci..
          if (($aUser['user_security_answer_1'] == "") || ($aUser['user_security_answer_2'] == "")) {
          $data['security_answer'] = 1;
          }

          $bCampanie = $this -> feedback_portal -> isCampanieActivaPentruUser();
          if ($bCampanie === TRUE) {
          $data['este_campanie'] = 1;
          }

          //se aduc mesajele de avertizare portal
          $data['mesaj_avertizare'] = json_encode($this->accesmodel->getMesajAvertizare());

          $data['wk_status'] = json_encode($this->db->select("status_id,status_nume")->get("workflow_status")->result_array());
          $data['email_suport'] = json_encode($this -> config -> item("email_suport_txt"));
          $data['atasamente_suport'] = json_encode($this -> config -> item("atasamente_suport_txt"));
          $data['security_question'] = json_encode(CONSTANTS::intrebariSecuritate());
          $data['partener_nume'] = json_encode($aUserDetails["partener_nume"]);

          $data['template_style'] = $this -> db -> get_where("user", array("user_id" => $aUserDetails["user_id"])) -> row() -> template_style;
          $data['documente'] = json_encode(array(
          Constants::ACT_DATA_OBTINERE_CU,
          Constants::ACT_DATA_OBTINERE_AC,
          Constants::ACT_DATA_OBTINERE_AS
          ));
         */
        $data['userroles'] = json_encode($aRoles);
        $data['userdetails'] = json_encode($aUserDetails->generateStdObject());
        $data['main_panel_title'] = $this->getMainPanelTitle($aRoles, $aUserDetails);


        $this->load->view('page/layout', $data);
    }

    /**
     * Changes password via ajax
     * @return $aUserData json encoded response
     */
    function xChangePassword() {
        //Read user details
        $bResult = TRUE;
        $aUserDetails = $this->auth->getUserDetails();
        $sUserName = $aUserDetails['user_nume'];
        $oldPassword = $this->input->post('oldPassword');
        $newPassword = $this->input->post('newPassword');
        $passwordConfirm = $this->input->post('passwordConfirm');
        if ($this->auth->authenticateUser($sUserName, $oldPassword) === FALSE) {
            echo $this->auth->passwordMatchFailure();
            return;
        }
        if ($newPassword != $passwordConfirm) {
            echo $this->auth->newPasswordMatchFailure();
            return;
        }

        $bResult = $this->auth->checkPasswordStruct($newPassword, $aUserDetails["user_alias"], $aUserDetails["user_nume"]);
        if ($bResult !== TRUE) {
            $aResponse = array(
                "error" => true,
                "success" => false,
                "description" => $bResult,
                "type" => "Validare"
            );
            echo json_encode($aResponse);
            return;
        }

        if ($this->auth->setPassword($newPassword)) {
            echo $this->auth->newPasswordSuccess();
            return;
        }
        echo $this->auth->newPasswordFailure();
    }

    function userDetailsForm() {
        $aUserDetails = $this->auth->getUserDetails();
        $aFields = array();
        $aFields[] = array(
            'xtype' => 'box',
            'isFormField' => TRUE,
            'fieldLabel' => 'E-mail',
            'anchor' => '95%',
            'autoEl' => array(
                'tag' => 'div',
                'html' => $aUserDetails['user_email']
            )
        );
        $aFields[] = array(
            'xtype' => 'box',
            'isFormField' => TRUE,
            'anchor' => '95%',
            'fieldLabel' => 'Nume',
            'autoEl' => array(
                'tag' => 'div',
                'html' => $aUserDetails['user_alias']
            )
        );
        $aFields[] = array(
            'xtype' => 'box',
            'isFormField' => TRUE,
            'anchor' => '95%',
            'fieldLabel' => 'Ultima activitate',
            'autoEl' => array(
                'tag' => 'div',
                'html' => '<font color="green">' . $aUserDetails['user_activitate'] . '</font>'
            )
        );

        $aColumns = array();
        $aColumns[0]['defaults'] = array(
            'width' => 115,
            'disabled' => FALSE
        );
        $aColumns[0]['columnWidth'] = 1;
        $aColumns[0]['items'] = $aFields;
        echo json_encode($aColumns);
    }

    function getFirstPage() {
        $sHtml = "<center>
        <table width=800 height=600 border=0>
            <tr>
                <td align=center valign='middle' >
                    <img src='".base_url('assets/admin/resources/img/logo.png')."'></td>
            </tr>
        <table></center>
        ";
        echo $sHtml;
    }

    /**
     * Metoda folosita pentru a seta denumirea title-ului aplicatiei.
     */
    private function getMainPanelTitle($aRoles, $oUser) {
        $panelTitle = " " . ucfirst($oUser->getFirstname()." ".$oUser->getLastname());
        $panelTitle.=" - ".ucfirst($aRoles[0]);
        return $panelTitle;
    }

}

/* End of file main.php */
/* Location: ./system/application/controllers/main.php */

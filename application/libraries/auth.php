<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
set_include_path("application/libraries");

//Clasa care gestioneaza autentificarea, autorizarea si logarea accesului
class Auth {

    private $CI, $aConfig;
    private $iUserId = FALSE;
    private $aRoles = array();
    private $aPerms = array();
    private $bForceReload = TRUE;
    private $AuthModel;

    function __construct($aConfig) {
        $this->aConfig = $aConfig;
        $this->CI = & get_instance();
        $this->CI->load->database();
        $this->CI->load->library('session');
        $this->CI->load->helper('ajax');
       
        $this->AuthModel = new \BusinessLogic\Models\Authmodel();
        $this->_setup($this->bForceReload);
    }

    public function getUserRoles() {
        return $this->aRoles;
    }

    //Incarca lista de roluri si permisiuni pentru utilizatorul curent,
    //daca acesta e autentificat
    //Daca $bForceReload este TRUE, se reincarca rolurile si permisiunile
    //indiferent daca ele exista deja in sesiune
    function _setup($bForceReload = FALSE) {
        $this->iUserId = $this->CI->session->userdata('user_id');
        
        if ($this->iUserId === FALSE) {
            //User neautentificat, nu mai cauta roluri si permisiuni
            return;
        }

        //Incarca lista de roluri
        if ($this->CI->session->userdata('user_roles') === FALSE) {
            //Nu a fost gasita lista de roluri in sesiune,
            //o generez si adaug in sesiune
            $this->aRoles = $this->AuthModel->getRolesByUserId($this->iUserId);
            $this->CI->session->set_userdata('user_roles', $this->aRoles);
        } else {
            //Gasit lista de roluri in sesiune
            //Daca se cere fortarea reincarcarii rolurilor
            if ($bForceReload) {
                $this->CI->session->set_userdata('user_roles', $this->AuthModel->getRolesByUserId($this->iUserId));
            }
            $this->aRoles = $this->CI->session->userdata('user_roles');
        }

        //Incarca lista de permisiuni
        if ($this->CI->session->userdata('user_perms') === FALSE) {
            //Nu a fost gasita lista de permisiuni in sesiune,
            //o generez si adaug in sesiune
            $this->aPerms = $this->AuthModel->getPermsByUserId($this->iUserId);
            $this->CI->session->set_userdata('user_perms', $this->aPerms);
        } else {
            //Gasit lista de permisiuni in sesiune
            //Daca se cere fortarea reincarcarii permisiunilor
            if ($bForceReload) {
                $this->CI->session->set_userdata('user_perms', $this->AuthModel->getPermsByUserId($this->iUserId));
            }
            $this->aPerms = $this->CI->session->userdata('user_perms');
        }
    }

    //Verifica daca exista un utilizator autentificat
    function isLoggedIn() {
        if ($this->CI->session->userdata('user_id') !== FALSE) {
            return TRUE;
        }

        return FALSE;
    }

    //Redirecteaza catre formularul de login ( pt request HTTP),
    //sau cere afisarea acestuia (pt request AJAX)
    function showLogin() {
        if (is_ajax()) {
            //AJAX request
            $aJSONResponse = array(
                "error" => TRUE,
                "type" => "nosession",
                "description" => $this->aConfig['msg_nosession']
            );

            echo json_encode($aJSONResponse);

            return;
        } else {
            //HTTP request
            redirect('admin/');

            return;
        }
    }

    //Verifica daca utilizatorul curent este autentificat
    //si autorizat sa acceseze URI-ul curent
    function isAuthorized() {

        //return TRUE;
        if ($this->isLoggedIn() === FALSE) {
            //User neautentificat
            return FALSE;
        }

        if (in_array('admin', $this->aRoles)) {
            //Acces permis intotdeauna pt rolul admin
            return TRUE;
        }


        $sPermKey = $this->CI->uri->segment(1) . '_';
        $sPermKey .= (($this->CI->uri->segment(2) != "") ? $this->CI->uri->segment(2) : 'index');


        if ($this->CI->uri->segment(1) == "admin") {
            $sDir = "admin/";

            $sPermKey = $sDir . $this->CI->uri->segment(2) . '_';
            $sPermKey .= (($this->CI->uri->segment(3) != "") ? $this->CI->uri->segment(3) : 'index');
        }



        if ((array_key_exists($sPermKey, $this->aPerms))) {/*
          if ($this->aPerms[$sPermKey]['value'] === '1' || $this->aPerms[$sPermKey]['value'] === TRUE) {

          return TRUE;
          }
          else {
          return FALSE;
          } */
            return true;
        }

        return FALSE;
    }

    //Redirecteaza catre pagina de acces interzis
    function showAccessRestricted() {
        if (is_ajax()) {
            //AJAX request
            $aJSONResponse = array(
                "error" => TRUE,
                "type" => "accessrestricted",
                "description" => $this->aConfig['msg_accessrestricted']
            );

            echo json_encode($aJSONResponse);
            die();
        } else {
            //HTTP request
            redirect('admin/sessions/accesInterzis');

            return;
        }
    }

    //Adauga inregistrare in tabela de log operatii
    function logAction($sCodOperatie = NULL, $sObsOperatie = NULL) {
        if ($sCodOperatie === NULL) {
            $sCodOperatie = $this->CI->uri->segment(1) . '_';
            $sCodOperatie .= (($this->CI->uri->segment(2) != "") ? $this->CI->uri->segment(2) : 'index');
            $sObsOperatie = empty($sObsOperatie) ? $this->AuthModel->getPermName($sCodOperatie) : $sObsOperatie;
        }

        $sUriString = $this->CI->uri->uri_string();
        $sIp = $this->CI->input->ip_address();
        $sBrowser = $this->CI->input->user_agent();

        //Log access to db
        $this->AuthModel->insertLog(($this->iUserId) ? ($this->iUserId) : NULL, $sCodOperatie, $sObsOperatie, $sIp, $sBrowser, $sUriString, json_encode($_POST));

        //Modify last activity time if there is a user logged in
        if ($this->iUserId) {
            $this->AuthModel->updateUserLastActivity($this->iUserId);
        }
    }

    /**
     * Verifica daca perechea username/parola exista
     * @return  Intoarce user_id la success, FALSE la eroare
     */
    function authenticateUser($sUserName, $sPsswd, $bActiv = FALSE) {

        $bFound = $this->AuthModel->getUserId($sUserName, $this->encodePsswd($sPsswd), $bActiv);
        //daca userul nu se autenfica atunci se incrementeaza coloana user_incercare, altfel se reseteaza.. la 5 incercari esuate userul este inactivat
        if ($bFound === FALSE) {
            $this->AuthModel->setIncercareLogin($sUserName, FALSE);
        } else {
            $this->AuthModel->setIncercareLogin($sUserName, TRUE);
        }

        return $bFound;
    }

    //Verifica daca utilizatorul cu $iUserId este conectat deja
    function accountConnected($iUserId) {
        $res = $this->AuthModel->getSessionsByUserId($iUserId);
        if (count($res)) {
            //Exista cel putin o sesiune activa
            return TRUE;
        }

        return FALSE;
    }

    //Deconecteaza(sterge) toate sesiunile utilizatorului $iUserId
    function disconnectAccount($iUserId) {
        $this->AuthModel->deleteSessions($iUserId);
    }

    //Creeaza sesiune pentru user-ul $iUserId
    function loginUser($iUserId) {
        //Seteaza variabila de sesiune $iUserId
        $this->CI->session->set_userdata('user_id', $iUserId);
        //Incarca fortat lista de roluri/permisiuni
        $this->_setup(TRUE);
        //Completeaza campul user_id in tabela de sesiune
        $this->AuthModel->updateSessionUserId($this->CI->session->userdata('session_id'), $iUserId);
    }

    //Intoarce array JSON cu mesaj de succes autentificare
    function authenticationSuccess() {
        return json_encode(array(
            "error" => FALSE,
            "type" => 'validation',
            "description" => "Autentificare reusita."
        ));
    }

    //Intoarce array JSON cu mesaj de cerere de confirmare
    //a deconectarii altor utilizatori pe acelasi cont
    function authenticationConfirm() {
        return json_encode(array(
            "error" => TRUE,
            "type" => 'multiplesession',
            "description" => "Acest utilizator este deja conectat!<br />Doriti sa continuati oricum?<br /><i>(utilizatorul curent va fi delogat)</i>"
        ));
    }

    //Intoarce array JSON cu mesaj de esec autentificare
    function authenticationFailure() {
        return json_encode(array(
            "error" => TRUE,
            "type" => 'validation',
            "description" => "Utilizator sau parola incorecte!"
        ));
    }

    //Intoarce array JSON cu mesaj de esec la comparare la schimbarea parolei
    function passwordMatchFailure() {
        return json_encode(array(
            'error' => TRUE,
            'type' => 'validation',
            'description' => 'Vechea parola nu este corecta!<br />Va rugam incercati din nou.'
        ));
    }

    //Intoarce array JSON cu mesaj de esec la comparare la schimbarea parolei
    function newPasswordMatchFailure() {
        return json_encode(array(
            'error' => TRUE,
            'type' => 'validation',
            'description' => 'Parolele nu coincid!<br />Va rugam incercati din nou.'
        ));
    }

    //Intoarce array JSON cu mesaj de succes la schimbarea parolei
    function newPasswordSuccess() {
        return json_encode(array(
            'error' => FALSE,
            'description' => 'Parola a fost schimbata cu success!'
        ));
    }

    //Intoarce array JSON cu mesaj de esec la schimbarea parolei
    function newPasswordFailure() {
        return json_encode(array(
            'error' => TRUE,
            'type' => 'validation',
            'description' => 'Parola nu a putut fi schimbata'
        ));
    }

    //Delogheaza user-ul
    function logout() {
        //Sterge inregistrarea din tabela de sesiuni
        $this->CI->session->sess_destroy();
        //Clear members
        $this->aPerms = array();
        $this->aRoles = array();
        $this->iUserId = FALSE;
    }

    //Intoarce string-ul specificat in forma criptata
    function encodePsswd($sStr) {
        return sha1($sStr);
    }

    //Intoarce campuri din tabela user pentru utilizatorul curent,
    //daca nu e altul precizat
    /**
     * 
     * @param type $iUserId
     * @return \BusinessLogic\Models\Entities\User
     */
    function getUserDetails($iUserId = FALSE) {
        if ($iUserId === FALSE) {
            $iUserId = $this->iUserId;
        }

        return $this->AuthModel->getUser($iUserId);
    }

    //Schimba vechea parola a user-ului curent cu cea specificata
    //Intoarce TRUE la succes si FALSE la eroare
    function setPassword($sNewPassword, $iUserId = FALSE) {
        if ($iUserId === FALSE) {
            $iUserId = $this->iUserId;
        }

        return $this->CI->authmodel->updateUserPassword($iUserId, $this->encodePsswd($sNewPassword));
    }

    //Verifica daca o parola este suficient de sigura
    function checkPasswordStruct($sPasswd) {

        $sError = "";

        if (strlen($sPasswd) < 6) {
            $sError .= "Parola trebuie sa contina minim 6 caractere!<br />";
        }

        if (preg_match("#[\%\#]+#", $sPasswd)) {
            $sError .= "Nu introduceti caracterele % sau #! <br />";
        }

        /*
          if (!preg_match("#[0-9]+#", $sPasswd)) {
          $sError .= "Parola trebuie sa contina cel putin o cifra! <br />";
          }

          if (!preg_match("#[a-z]+#", $sPasswd)) {
          $sError .= "Parola trebuie sa contina cel putin o litera mica! <br />";
          }


          if (!preg_match("#[A-Z]+#", $sPasswd)) {
          $sError .= "Parola trebuie sa contina cel putin o majuscula! <br />";
          }

          if (!preg_match("#[\,\.\?\!\(\)\{\}\-\=\+\;\:\[\]\*]+#", $sPasswd)) {
          $sError .= "Parola trebuie sa contina cel putin un caracter special  ,.?!(){}-=+;:[]* ! <br />";
          }
         * 


          $bNumeGasit = FALSE;
          if ($sNumeUser != "") {
          $aNumeUser = explode(" ", $sNumeUser);
          if (count($aNumeUser) > 0) {
          foreach ($aNumeUser as $key => $sParteNume) {
          if ($sParteNume == "") {
          continue;
          }
          if (preg_match("/" . $sParteNume . "/i", $sPasswd)) {
          $bNumeGasit = TRUE;
          }
          }
          }
          }
          if ($bNumeGasit === TRUE) {
          $sError .= "Nu introduceti parti din nume in campul Parola! <br />";
          }

          $bUsernameGasit = FALSE;
          if (preg_match("/" . $sUsername . "/i", $sPasswd)) {
          $bUsernameGasit = TRUE;
          }
          if ($bUsernameGasit === TRUE) {
          $sError .= "Nu introduceti Username-ul in campul Parola! <br />";
          }
         */
        return ($sError == "") ? TRUE : $sError;
    }

    function checkUsernameStruct($sUsernume) {
        $sError = "";

        if (strlen($sUsernume) < 8) {
            $sError .= "Username-ul trebuie sa contina minim 8 caractere!<br />";
        }

        if (preg_match("#[\s]+#", $sUsernume)) {
            $sError .= "Username-ul nu trebuie sa contina spatii! <br />";
        }

        return ($sError == "") ? TRUE : $sError;
    }

    function resetPasswd(BusinessLogic\Models\Entities\User $user) {
        $sPass = generatePassword(14, 8);
        
        $user->setPassword($this->encodePsswd($sPass));
        
        //$this->CI->notification->resetPasswdEmail($aUser, $sPass);

        return $sPass;
    }

}

<?php
/** @property  Auth  $auth  */
class Sessions extends CI_Controller {

	public function __construct() {
           
		parent::__construct();
                
	}

    
	/**
	 * Outputs a login form to client browser, validates user input & loggs in the user
	 */
	function index() {
            
		$data = array();
		//$data['security_question'] = json_encode(CONSTANTS::intrebariSecuritate());
		//Daca utilizatorul este deja logat, redirect la main
		if ($this->auth->isLoggedIn()) {

			redirect('admin/main');

			return;
		}
		$this->load->view('login/loginform', $data);
	}

	/**
	 * Login check for AJAX requests
	 * @return
	 */
	function xlogin() {

		if (! is_ajax()) {
			//Not an AJAX request
			redirect('sessions');

			return;
		}
		//Ajax request
		if (($this->input->post("data") != "")) {
			$aData = json_decode($this->input->post("data"), TRUE);
			$sUserName = $aData['email'];
			$sPsswd = $aData['password'];
			$sKick = isset($aData['kickuser']) ? $aData['kickuser'] : 0;
		}
		else {
			$sKick = 1;
			$sUserName = $this->input->post("username");
			$sPsswd = $this->input->post("password");
		}

		if ((trim($sUserName) == "") || (trim($sPsswd) == "")) {
			print $this->auth->authenticationFailure();

			return;
		}
                

		$iUserId = $this->auth->authenticateUser($sUserName, $sPsswd, TRUE);

              
		if ($iUserId !== FALSE) {
			//User autentificat (username/parola ok)
			if ($this->auth->accountConnected($iUserId)) {
                           
				//Exista alti useri conectati pe acelasi cont
				if ($sKick == '1') {
					//Deconecteaza ceilalti useri conectati pe acelasi cont
					$this->auth->disconnectAccount($iUserId);
					//Conecteaza utilizatorul curent
					$this->auth->loginUser($iUserId);
					//Afiseaza mesaj JSON de autentificare reusita
					print $this->auth->authenticationSuccess();

					return;
				}
				else {
					//Afiseaza mesaj JSON de confirmare delogare alti utilizatori
					print $this->auth->authenticationConfirm();

					return;
				}

			}
			else {
                            
				//Conecteaza utilizatorul curent
				$this->auth->loginUser($iUserId);
                                
				//Afiseaza mesaj JSON de autentificare reusita
                                
				print $this->auth->authenticationSuccess();

				return;
			}
		}
		//Autentificarea nu a reusit (user/parola incorecte)
		$this->auth->logAction(NULL, "incercare login nereusita");
		print $this->auth->authenticationFailure();
	}

	//Sterge date sesiune
	function logout() {
		$this->auth->logout();
		redirect('admin/sessions');
	}

	function accesInterzis() {
		print "Acces interzis";
	}

	function getCaptcha() {
		$this->load->library("captchaimage");
		//$captcha = new CaptchaSecurityImages(120, 40, 8);
		$this->captchaimage->generateImage();
	}

	function resetPasswd() {
		$sSecurityCode = $this->session->userdata('security_code');
		if ($sSecurityCode == "") {
			$aResponse = array(
				"error"       => TRUE,
				"success"     => FALSE,
				"description" => "Eroare in validarea codului de securitate.Va rugam contactati administratorul aplicatiei."
			);
			echo json_encode($aResponse);

			return;
		}

		$code = $this->input->post("code");
		$sEmail = $this->input->post("usernume");
		$sAnswer1 = $this->input->post("security_answer_1");
		$sAnswer2 = $this->input->post("security_answer_2");

		if ($code != $sSecurityCode) {
			$aResponse = array(
				"error"       => TRUE,
				"success"     => FALSE,
				"description" => "Codul de securitate nu este corect"
			);
			echo json_encode($aResponse);

			return;
		}

		$aTemp = array(
			"user_nume"              => $this->input->post("user_nume"),
			"user_activ"             => 1,
			'user_security_answer_1' => $this->auth->encodePsswd($sAnswer1),
			'user_security_answer_2' => $this->auth->encodePsswd($sAnswer2)
		);
		$aQuery = $this->db->get_where("user", $aTemp);
		if ($aQuery->num_rows == 0) {
			$aResponse = array(
				"error"       => TRUE,
				"success"     => FALSE,
				"description" => "Datele nu au fost regasite. Parola nu poate fi resetata"
			);
			echo json_encode($aResponse);

			return;
		}
		//mesaj separat pentru mai multe inregistrari.. just in case
		if ($aQuery->num_rows > 1) {
			$aResponse = array(
				"error"       => TRUE,
				"success"     => FALSE,
				"description" => "Datele nu au fost regasite. Parola nu poate fi resetata! Va rugam contactati administratorul aplicatiei. "
			);
			echo json_encode($aResponse);

			return;
		}

		if ($aQuery->num_rows == 1) {
			$aUser = $aQuery->row_array();
			$bResult = $this->auth->resetPasswd($aUser);
			if ($bResult !== FALSE) {
				$aResponse = array(
					"error"       => TRUE,
					"success"     => FALSE,
					"description" => "Noua parola a fost resetata. Veti primi in curand pe adresa de email noua parola. "
				);
				echo json_encode($aResponse);

				return;
			}
		}
	}

}

/* End of file sessions.php */
/* Location: ./system/application/controllers/sessions.php */

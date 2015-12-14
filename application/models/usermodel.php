<?php
class Usermodel extends MY_Model {
	function __construct() {
		parent::__construct();
		$this->load->library('auth');
	}

	/**
	 * Functie care aduce datele despre useri
	 */
	function getData($aPost) {
		$aColumnMapping = array();

		$sSort = ( isset( $aPost["sort"] ) && $aPost["sort"] != "" ) ? $aPost["sort"] : "user_alias";
		$sDir = ( isset( $aPost["dir"] ) && $aPost["dir"] != "" ) ? $aPost["dir"] : "ASC";
		$sStart = ( isset( $aPost["start"] ) && $aPost["start"] != "" ) ? $aPost["start"] : "0";
		$sLimit = ( isset( $aPost["limit"] ) && $aPost["limit"] != "" ) ? $aPost["limit"] : "50";
		$aFilters = isset( $aPost["filter"] ) ? json_decode($aPost["filter"]) : NULL;

		$this->db->select("SQL_CALC_FOUND_ROWS user.*, GROUP_CONCAT( rol_nume ORDER BY rol_nume ASC SEPARATOR ',' ) as user_rol , partener_nume, birou_nume, birou_nume as birou_id_val", FALSE);
		$this->db->join("user_rol", "user_rol.user_id=user.user_id", "left");
		$this->db->join("rol", "user_rol.rol_id=rol.rol_id", "left");
		$this->db->join("nom_partener", "nom_partener.partener_id=user.partener_id", "left");
		$this->db->join("nom_birou", "nom_birou.birou_id=user.birou_id", "left");
		$this->gridFilters($sSort, $sDir, $sStart, $sLimit, $aFilters, FALSE, $aColumnMapping);
		$this->db->group_by("user.user_id");
		$query = $this->db->get("user");
		$totalCount = $this->db->query('SELECT FOUND_ROWS() total_rows')->row()->total_rows;
		$aData = $query->result_array();

		$data = array(
			'totalCount' => $totalCount,
			'data'       => $aData
		);

		return $data;
	}

	/**
	 * Returneaza rolurile unui user
	 *
	 * @param  $iUserId
	 * @return array
	 */
	function getUserRol($iUserId) {
		$this->db->select("rol.rol_id,rol_nume");
		$this->db->join("user_rol", "user_rol.user_id=user.user_id");
		$this->db->join("rol", "user_rol.rol_id=rol.rol_id");
		$this->db->where("user.user_id", $iUserId);
		$aResult = $this->db->get("user")->result_array();

		return $aResult;
	}

	/**
	 * Returneaza rolurile unui user
	 *
	 * @param  $iUserId
	 * @return array
	 */
	function getConstructor($query = NULL, $bActiv = TRUE) {

		if ($bActiv === FALSE) {
			//$this -> db -> where("partener_activ", 1);
		}
		if ($query != "") {
			$this->db->like("partener_nume", $query);
		}
		$this->db->limit(50, 0);
		$this->db->order_by("partener_nume asc");
		$aResult = $this->db->get("nom_partener")->result_array();

		return $aResult;
	}

	/**
	 * Returneaza birourile
	 *
	 * @param  $iUserId
	 * @return array
	 */
	function getBirou($query = NULL, $bActiv = 1) {

		if ($bActiv == 1) {
			$this->db->where("birou_activ", 1);
		}
		if ($query != "") {
			$this->db->like("birou_nume", $query);
		}
		$this->db->limit(50, 0);
		$this->db->order_by("birou_nume asc");
		$aResult = $this->db->get("nom_birou")->result_array();

		return $aResult;
	}

	/**
	 * Returneaza judetele
	 *
	 * @param  $iUserId
	 * @return array
	 */
	function getJudet($query = NULL) {

		if ($query != "") {
			$this->db->like("jud_nume", $query);
		}
		$this->db->limit(100, 0);
		$this->db->order_by("jud_nume asc");
		$aResult = $this->db->get("nom_judete")->result_array();

		return $aResult;
	}


	/**
	 * Adaugare / editare user
	 *
	 * @param  $aData
	 * @return bool|string
	 */
	function editUser($aData) {
		$iRecord = $aData["user_id"];
		$aTemp = array(
			"user_nume"   => $aData["user_nume"],
			"user_alias"  => $aData["user_alias"],
			"user_email"  => $aData["user_email"],
			"user_marca"  => $aData["user_marca"],
			"user_activ"  => $aData["user_activ"],
			"partener_id" => ( $aData["partener_id"] != "" ) ? $aData["partener_id"] : NULL,
			"birou_id"    => ( $aData["birou_id"] != "" ) ? $aData["birou_id"] : NULL,
			"jud_id"      => ( $aData["jud_id"] != "" ) ? $aData["jud_id"] : NULL
		);

		if ($aTemp["jud_id"] != "") {
			$aJudete = explode(",", $aTemp["jud_id"]);

			$this->db->select(" GROUP_CONCAT(jud_nume) as judete ", FALSE);
			$this->db->where_in("jud_id", $aJudete);
			$sJudet = $this->db->get("nom_judete")->row()->judete;

			$aTemp["jud_nume"] = $sJudet;
		}

		//daca userul este activ atunci se reseteaza numarul de loginuri
		if ($aData["user_activ"] == 1) {
			$aTemp["user_nr_login"] = 0;
		}

		//verifica duplicatele
		$bDuplicate = $this->_verifyDuplicat($iRecord, $aTemp);
		if ($bDuplicate === TRUE) {
			return "User duplicat";
		}

		//insert user
		if ($iRecord == 0) {
			//daca parola nu a fost introdusa atunci parola va fi username-ul
			$aTemp["user_parola"] = ( $aData["newPassword"] != "" ) ? $this->auth->encodePsswd($aData["newPassword"]) : $this->auth->encodePsswd($aData["user_nume"]);
			$this->db->insert("user", $aTemp);
			$iRecord = $this->db->insert_id();
		}
		else {
			if ($aData["newPassword"] != "") {
				$aTemp["user_parola"] = $this->auth->encodePsswd($aData["newPassword"]);
			}
			$this->db->where("user_id", $iRecord);
			$this->db->update("user", $aTemp);
		}

		//modificare roluri
		if (isset( $aData["user_rol"] ) && ( $aData["user_rol"] ) != "") {
			$this->db->where("user_id", $iRecord);
			$this->db->delete("user_rol");

			$aRol = explode(",", $aData["user_rol"]);

			if (count($aRol) > 0) {
				foreach ($aRol as $iRol) {
					$this->db->insert("user_rol", array(
						"user_id" => $iRecord,
						"rol_id"  => $iRol,
						"ur_data" => date("Y-m-d H:i:s")
					));
				}
			}
		}

		return TRUE;
	}

	/**  Verificare user duplicat
	 *
	 * @param  $iUserId
	 * @param  $aData
	 * @return bool
	 */
	function _verifyDuplicat($iUserId, $aData) {
		$this->db->where("user_nume", $aData["user_nume"]);
		$this->db->where("user_id !=", $iUserId);
		$iCount = $this->db->get("user")->num_rows();

		if ($iCount > 0) {
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Functie care aduce datele despre o inregistrare
	 */
	function getUser($IUserId) {
            
		$this->db->select("email,id_user");
		
		$this->db->where("id_user", $IUserId);

		return $this->db->get("users")->row_array();
	}

	/**
	 * Functie care seteaza theme-ul unui user
	 */
	function setStyle($sStyle) {
		$aUser = $this->auth->getUserDetails();
		$this->db->where('user_id', $aUser["user_id"]);
		$this->db->set("template_style", $sStyle);
		$this->db->update('user');
	}

	function saveSecurityAnswer($aData) {
		$aUser = $this->auth->getUserDetails();
		$this->db->where('user_id', $aUser["user_id"]);
		$this->db->set('user_security_answer_1', $this->auth->encodePsswd($aData['security_answer_1']));
		$this->db->set('user_security_answer_2', $this->auth->encodePsswd($aData['security_answer_2']));
		$this->db->update("user");

		return TRUE;
	}


}

/* End of file usermodel.php */
/* Location: ./application/models/usermodel.php */
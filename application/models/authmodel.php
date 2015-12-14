<?php
class Authmodel extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	//Intoarce array cu codurile rolurilor asociate user-ului specificat
	function getRolesByUserId($iUserId) {
            
		$sSql = 'SELECT rol.rol_nume FROM user_rol
				INNER JOIN rol
				ON user_rol.rol_id = rol.rol_id
				WHERE user_rol.id_user = ?';
		$oQuery = $this->db->query($sSql, array($iUserId));
		$aResult = array();
		foreach ($oQuery->result_array() as $row) {
			$aResult[] = $row['rol_nume'];
		}

		return $aResult;
	}

	//Intoarce array cu permisiunile user-ului
	function getPermsByUserId($iUserId) {
            
		$aResult = array();
		//Extrage roluri user
		$aRoles = $this->getRolesByUserId($iUserId);
		//Extrage permisiuni din rolurile asociate
		foreach ($aRoles as $iRoleId) {
			$aResult = array_merge($aResult, $this->getPermsByRoleId($iRoleId));
		}
		//Extrage permisiuni asociate direct user-ului
		//si le suprapune celor calculate anterior
                /*
		$sSql = 'SELECT permisiune.perm_cod, user_permisiune.up_valoare
				FROM user_permisiune
				INNER JOIN permisiune
				ON user_permisiune.perm_id = permisiune.perm_id
				WHERE permisiune.perm_activ = 1 AND user_permisiune.id_user = ?';
                echo $sSql;
                exit();
		$oQuery = $this->db->query($sSql, array($iUserId));
		$aTemp = array();
		foreach ($oQuery->result_array() as $row) {
			$aTemp[$row['perm_cod']] = $row['up_valoare'];
		}
		$aResult = array_merge($aResult, $aTemp);
                */
             
		return $aResult;
	}

	//Intoarce array cu permisiunile rolului
	function getPermsByRoleId($iRoleId) {
		$sSql = 'SELECT permisiune.perm_cod, rol_permisiune.rp_valoare
				FROM rol_permisiune
				INNER JOIN permisiune
				ON rol_permisiune.perm_id = permisiune.perm_id
				INNER JOIN rol ON rol.rol_id = rol_permisiune.rol_id
				WHERE permisiune.perm_activ = 1 AND rp_valoare = 1 AND rol.rol_nume = ?';
		$oQuery = $this->db->query($sSql, array($iRoleId));
		$aResult = array();
		foreach ($oQuery->result_array() as $row) {
			$aResult[$row['perm_cod']] = $row['rp_valoare'];
		}

		return $aResult;
	}

	//Intoarce numele permisiunii cu cheia precizata (daca exista) sau string gol
	function getPermName($iPermId) {
            return;
		$sSql = 'SELECT permisiune.perm_nume FROM permisiune
				WHERE permisiune.perm_cod = ? LIMIT 1';
		$oQuery = $this->db->query($sSql, array($iPermId));
		if ($oQuery->num_rows() > 0) {
			$row = $oQuery->row_array();

			return $row['perm_nume'];
		}

		return '';
	}

	//Adauga inregistrare noua in tabela log_acces
	function insertLog($iUserId, $sCodOperatie, $sObsOperatie, $sIp, $sBrowser, $sUri, $aContext) {
		$sSql = 'INSERT INTO log_acces
				(id_user, log_operatie, log_operatie_obs, log_acces_ip,
				log_acces_browser, log_acces_data, log_acces_uri, log_acces_post)
				VALUES (?, ?, ?, ?, ?, ?, ?, ?)
				';
		$bResult = $this->db->query($sSql, array(
			$iUserId,
			$sCodOperatie,
			$sObsOperatie,
			$sIp,
			$sBrowser,
			date('Y-m-d H:i:s'),
			$sUri,
			$aContext
		));

		return $bResult;
	}

	//Modifica data ultimei activitati a user-ului dat
	function updateUserLastActivity($iUserId) {
		$sSql = 'UPDATE user SET user_activitate = ? WHERE id_user= ? LIMIT 1';
		$bResult = $this->db->query($sSql, array(
			date('Y-m-d H:i:s'),
			$iUserId
		));

		return $bResult;
	}

	//Verifica daca perechea username/parola exista in tabela user
	//Intoarce id_user la success, FALSE la eroare
	function getUserId($sUserName, $sPsswd, $bActiv = FALSE) {

		$sActiv = "";
		if ($bActiv) {
			$sActiv = " AND user_activ = 1 ";
		}

		$sSql = "SELECT id_user from users
				WHERE (email = ?)
				AND (password = ?)  LIMIT 1";
                             
		$oQuery = $this->db->query($sSql, array(
			$sUserName,
			$sPsswd
		));

                if($oQuery)
		if ($oQuery->num_rows()) {
			$row = $oQuery->row_array();
			//verificare pentru care au alocati constructor daca partenerul este activ

			return $row['id_user'];

		}

		return FALSE;
	}

	//Intoarce sesiunile utilizatorului $iUserId
	function getSessionsByUserId($iUserId) {
		$sSql = 'SELECT session_id FROM '.config_item('sess_table_name').' WHERE id_user=?';
            
		$oQuery = $this->db->query($sSql, array($iUserId));
                 if($oQuery)
		return $oQuery->result_array();
                 return false;
	}

	//Sterge sesiunile utilizatorului $iUserId
	//Intoarce numarul inregistrarilor sterse
	function deleteSessions($iUserId) {
		$sSql = 'DELETE FROM '.config_item('sess_table_name').' WHERE id_user=?';
		$oQuery = $this->db->query($sSql, array($iUserId));

		return $this->db->affected_rows();
	}

	//Seteaza campul id_user in inregistrarea $sSessionId din tabela de sesiuni
	//Intoarce numarul inregistrarilor afectate
	function updateSessionUserId($sSessionId, $iUserId) {
		$sSql = 'UPDATE '.config_item('sess_table_name').' SET id_user = ? WHERE session_id = ? LIMIT 1';
		$oQuery = $this->db->query($sSql, array(
			$iUserId,
			$sSessionId
		));

		return $this->db->affected_rows();
	}

	//Intoarce inregistrarea cu user id precizat din tabela user
	function getUser($iUserId) {
		$sSql = 'SELECT id_user,email,lastname,firstname
				FROM users
				 WHERE id_user = ? LIMIT 1';
                
		$oQuery = $this->db->query($sSql, array($iUserId));
                
		$aRes = array();
		if ($oQuery->num_rows()) {
			$aRes = $oQuery->row_array();
		}

		return $aRes;
	}

	//Update-aza parola user-ului dat si intoarce rezultatul actiunii
	function updateUserPassword($iUserId, $sNewPassword) {
		$sSql = 'UPDATE `user` SET user_parola = ? WHERE id_user = ? LIMIT 1';
		$bResult = $this->db->query($sSql, array(
			$sNewPassword,
			$iUserId
		));

		return $bResult;
	}

	/**
	 * Functie care seteaza de cate ori a esuat userul in logare
	 */
	function setIncercareLogin($sUserName, $bTipIncercare) {

            return false;
		$iIncercare = 0;
		$aUser = $this->db->get_where("user", array("user_nume" => $sUserName))->row_array();
		if (isset($aUser["user_nr_login"])) {
			$iIncercare = (int)$aUser["user_nr_login"];
		} else
			return;

		if ($bTipIncercare === TRUE) {
			$iIncercare = 0;
		} else {
			$iIncercare ++;
		}

		$this->db->where("user_nume", $sUserName);
		$this->db->set("user_nr_login", $iIncercare);
		$this->db->update("user");

		if ($iIncercare > $this->config->item("number_of_safe_logins")) {
			$this->db->where("user_nume", $sUserName);
			$this->db->set("user_activ", 0);
			$this->db->update("user");
		}


		if ($iIncercare == 6) {
			$this->load->library('email');
			$this->email->from('portalparteneri@gdfsuez.ro', 'Portal Parteneri');
			$this->email->to('danieldarie@gdfsuez.ro');
			$this->email->cc('Brad.Simionescu@gdfsuez.ro');
			$this->email->subject('Inactivare user');
			$this->email->message(' Userul '.$sUserName." a fost inactivat automat. Ip ". $sIp = $this -> input -> ip_address());
			$this->email->send();
		}

		return;
	}

}

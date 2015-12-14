<?php
class Accesmodel extends CI_Model {
   function __construct() {
      parent::__construct();
   }

   /**
    * Functie care aduce datele despre useri
    */
   function getData($aPost) {

      $this -> db -> start_cache();
      $sSort = (isset($aPost["sort"]) && $aPost["sort"] != "") ? $aPost["sort"] : "zt_nume";
      $sSir = (isset($aPost["dir"]) && $aPost["dir"] != "") ? $aPost["dir"] : "ASC";
      $iRol = (isset($aPost["rol_id"])) ? $aPost["rol_id"] : 0;
      $sStart = (isset($aPost["start"]) && $aPost["start"] != "") ? $aPost["start"] : "0";
      $sLimit = (isset($aPost["limit"]) && $aPost["limit"] != "") ? $aPost["limit"] : "50";

      //quick search
      if (isset($aPost["query"]) && ($aPost["query"] != "") && isset($aPost["fields"])) {
         $sWhere = getQuickWhere(json_decode($aPost["fields"]), $aPost["query"]);
         $this -> db -> where("1", 1, false);
         $this -> db -> where($sWhere, null, false);
      }

      $this -> db -> order_by($sSort, $sSir);
      $this -> db -> limit($sLimit, $sStart);
      $this -> db -> where("perm_activ", 1);
      $this -> db -> select("permisiune.perm_id,perm_cod,perm_nume, rp_valoare, perm_tip");
      $this -> db -> join("rol_permisiune", "rol_permisiune.perm_id=permisiune.perm_id and rol_permisiune.rol_id= {$iRol}", "left");
      $query = $this -> db -> get("permisiune");      
      
      $totalCount = $this -> db -> count_all_results("permisiune");
      $this -> db -> flush_cache();
      $this -> db -> stop_cache();
      $aData = $query -> result_array();

      $data = array(
         'totalCount' => $totalCount,
         'data' => $aData
      );
      return $data;
   }

   /**
    * Functie de salvare permisiune pentru un rol
    * @param  $aData
    * @return bool|string
    */
   function savePermisie($aData) {
      $aTemp = array();
      $aPermRol = json_decode($aData["data"], TRUE);

      //verificare daca s-a trimis o inreg sau bulk
      if (isset($aPermRol["rp_valoare"])) {
         $aTemp[] = $aPermRol;
      }
      else {
         $aTemp = $aPermRol;
      }

      foreach ($aTemp as $aRol) {
         if ($aRol["rp_valoare"] != "") {
            $this -> db -> where("rol_id", $aData["rol_id"]);
            $this -> db -> where("perm_id", $aRol["perm_id"]);
            $this -> db -> delete("rol_permisiune");

            if ($aRol["rp_valoare"] != 0) {
               $this -> db -> insert("rol_permisiune", array(
                  "rol_id" => $aData["rol_id"],
                  "perm_id" => $aRol["perm_id"],
                  "rp_valoare" => $aRol["rp_valoare"]
               ));
            }
         }
      }
      return TRUE;
   }

   /**
    * Verifies if a given user has accees to specified record ( adm - has full acces, user - only to his records , opr - none)
    * @return boolean
    * @param int $aUserId - a user id ( not necessary the user logged in )
    * @param array $aUserRole array with roles ( not necessary the user's roles session )
    * @param int $iRecordId - the record's id
    */
   function checkUserMethodAccess($iUserId, $aUserRole, $iRecordId) {

      $this -> db -> flush_cache();
      $aData = $this -> db -> get_where("doc", array("doc_id" => $iRecordId)) -> row_array();

      if ($aData["user_id"] == $iUserId) {
         return TRUE;
      }
      return FALSE;
   }


   function checkUserLucrareAcces($iPartener, $sCrc) {
      $aQuery = $this -> db -> get_where("lucrare", array("constructor" => $iPartener, "crc"=> $sCrc));
      if ($aQuery->num_rows() > 0 ) {
         return TRUE;
      }
      return FALSE;
   }


	/**
	 * returnare mesaj avertizare
	 * @return bool/string
	 */
	function getMesajAvertizare() {

		$sNow = date("Y-m-d H:i:s");
		$this->db->where("mes_start <=", $sNow);
		$this->db->where("mes_end >=" , $sNow);
		$this->db->where("mes_tip", "avertizare");
		$aMesaj = $this->db->get("nom_shutdown")->row_array();

		if (count($aMesaj) > 0) {
			return $aMesaj["mes_text"];
		}

		return FALSE;
	}
}

/* End of file accesmodel.php */
/* Location: ./application/models/accesmodel.php */

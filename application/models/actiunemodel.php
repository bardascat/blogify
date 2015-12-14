<?php
class Actiunemodel extends CI_Model {
   function __construct() {
      parent::__construct();
   }

   /**
    * returnare inregistrare actiune in baza  codului din campul ACTIUNE
    * @param string $sCod
    */
   function getActiuneByCod($sCod) {
      $this -> db -> where("actiune_cod", $sCod);
      $aQuery = $this -> db -> get("nom_actiune");
      //e($this->db->last_query());
      if ($aQuery -> num_rows == 1) {
         return $aQuery -> row_array();
      }
      return FALSE;
   }
 

}
?>
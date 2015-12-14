<?php
class Operatiemodel extends CI_Model {
   function __construct() {
      parent::__construct();
   }

   /**
    * inserare operatie
    * @param string $sActiune - cod operatie SAP - vezi cumunicare MLAG
    * @param array $aData array-ul final transformat pe baza actiunii;
    * @param bool $bEroare
    * @param string $sSistem - XI /WEB
    * @param string $sComentarii
    * @param int $iUser - id user
    */
   function insertOperatie($sActiuneCod, $aData, $bEroare = 0, $sSistem = null, $sComentarii = null, $iUser, $aIdoc, $sNotite) {
      
      $aTemp = explode("-", $sActiuneCod);
      $sActiune = $aTemp[0];
      
      $aTemp = array(
         "actiune_cod" => $sActiuneCod,
         "actiune" => $sActiune,
         "operatie_date" => is_array($aData) ? json_encode($aData) : null,
         "operatie_eroare" => $bEroare,
         "operatie_data" => date("Y-m-d H:i:s"),
         "crc" => isset($aData["crc"]) ? $aData["crc"] : null,
         "operatie_sistem" => $sSistem,
         "operatie_user" => $iUser,
         "operatie_comentarii" => $sComentarii,
         "operatie_notite" => $sNotite,
         "operatie_idoc" => is_object($aIdoc) && (isset($aIdoc -> Z1ISU_ML_HEADER -> DOCREF)) ? $aIdoc -> Z1ISU_ML_HEADER -> DOCREF : null,
         "operatie_idoc_date" => is_object($aIdoc) ? json_encode($aIdoc) : null
      );
      $this -> db -> insert("log_operatie", $aTemp);
   }

}
?>
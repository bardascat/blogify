<?php

class Acces_user {
   var $CI;

   function __construct() {
      $this -> CI = &get_instance();
      $this -> CI -> load -> model('accesmodel');
   }

   /**
    * Verifies if a given user has accees to specified record ( adm - has full
    * acces, user - only to his records , opr - none)
    * @return boolean
    * @param array $aUser - a given array with user data ( not necessary the user's
    * data session )
    * @param array $aUserRole session stored user role
    * @param int $iRecordId - the record's id
    */
   function checkUserAccesLucrare($sCrc) {

      $aUserRole = $this -> CI -> session -> userdata("user_roles");
      $aSessionData = $this -> CI -> auth -> getUserDetails();
      
      if (count($aUserRole) == 0) {
         $aResponse = array(
            "error" => true,
            "success" => false,
            "type" => "Validare",
            "description" => "Nu aveti roluri"
         );
         die(json_encode($aResponse));
      }

      //some initial role checking
      if ((in_array("admin", $aUserRole)) || (in_array("operator", $aUserRole)) ||  in_array("expert", $aUserRole) ) {
         return TRUE;
      }
   
      if (in_array("constructor", $aUserRole) &&(count($aUserRole)==0 )) {    
         if (!isset($aSessionData["partener_id"]) || $aSessionData["partener_id"] == "") {
            $aResponse = array(
               "error" => true,
               "success" => false,
               "type" => "Validare",
               "description" => "Nu aveti asociat id de partener."
            );
            die(json_encode($aResponse));
         }
         else {
            $bAccess = $this -> CI -> accesmodel -> checkUserLucrareAcces($aSessionData['partener_id'], $sCrc);
            if ($bAccess === FALSE) {
               $aResponse = array(
                  "error" => true,
                  "success" => false,
                  "type" => "Validare",
                  "description" => "Nu aveti aces la lucrare"
               );
               die(json_encode($aResponse));
            }
         }
         return FALSE;
      }
   }
}
?>
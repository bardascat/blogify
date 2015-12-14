<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Copiaza cheile precizate din $_POST in array-ul $aRes
function copyPost(&$aRes, $aKey = null) {
    
	$CI = &get_instance();
	if (empty($aKey)) {
		$aKey = array_keys($_POST);
	}
	foreach ($aKey as $sKey) {
		if (isset($_POST[$sKey])) {
		   $sTemp = trim($CI->input->post($sKey));
         if($sTemp == "") {
            $sTemp = NULL;
         }
			$aRes[$sKey] = trim($sTemp);
		}
	}
}
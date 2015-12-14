<?php  if (! defined('BASEPATH'))
	exit('No direct script access allowed');

//Print JSON-encoded message array

function show_mes($sDescriere, $bIsError = FALSE, $sTip = 'general',  $aContextData = NULL ) {
	print json_encode(array('error'       => ($bIsError == TRUE),
	                        'type'        => $sTip,
	                        "success"      => ! $bIsError,
	                        'description' => $sDescriere,
	                        'data'        => $aContextData
	));
}

function show_er($sDescriere, $bIsError = TRUE, $sTip = 'general',  $aContextData = NULL ) {
	print json_encode(array('error'       => ($bIsError == TRUE),
	                        'type'        => $sTip,
	                        "success"      => ! $bIsError,
	                        'description' => $sDescriere,
	                        'data'        => $aContextData
	));
	die();
}

function e($aData, $mes = NULL) {
	log_message("error", $mes.print_r($aData, TRUE));
}

function p($aData) {
	echo "<pre>".print_r($aData, TRUE)."</pre>";
}

?>
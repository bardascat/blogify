<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Verifica daca request-ul este de tip AJAX
function is_ajax() {
	if (!isset ($_SERVER['HTTP_X_REQUESTED_WITH'])) {
		return FALSE;
	}
	if ($_SERVER['HTTP_X_REQUESTED_WITH'] != "XMLHttpRequest") {
		return FALSE;
	}
	return TRUE;
}
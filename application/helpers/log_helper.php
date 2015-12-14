<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Functie care forteaza download-ul unei arhive cu fisiere din log
//Apelata fara parametri intoarce tot folderul logs
//Apelata cu parametru, intoarce fisierul (arhivat) din data specificata, daca el exista
function download_log($iTime = FALSE) {
	$CI = &get_instance();
	$CI->load->library('zip');
	//Find log path
	$sLogFolder = (config_item('log_path')!='')?config_item('log_path'): APPPATH.'logs/';
	if ($iTime === FALSE){
		//Zip & send entire log folder
		$CI->zip->read_dir($sLogFolder, FALSE);
		$CI->zip->download('log.zip');
		return;
	}
	$sFile = 'log-'. date('Y-m-d', $iTime) .'.php';
	$CI->zip->read_file($sLogFolder.'/'.$sFile);
	$CI->zip->download('log.zip');
	return;
}

function logx_message($level = 'error', $message, $php_error = FALSE) {
	log_message($level, $message, $php_error);
	if (config_item('logx_enable') === TRUE) {
		$to = config_item('logx_to');
		$from = config_item('base_url');
		$subject = $level.' on '.$from;
		$text = $message . "\r\n" . $php_error;
		$headers = 'From: '.$from. "\r\n";
		@mail($to, $subject, $text, $headers);
	}
}
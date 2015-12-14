<?php
if (! defined('BASEPATH'))
	exit('No direct script access allowed');

function generatePassword($length = 9, $strength = 0) {
	$vowels = 'aeu';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength & 2) {
		$vowels .= "AEUYOU";
	}
	if ($strength & 4) {
		$consonants .= '23456789';
	}
	if ($strength & 8) {
		$vowels .= "AEUYOU";
		$consonants .= '23456789';
		$consonants .= '@$=';
	}

	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i ++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		}
		else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}

	return $password;
}


function getXlsNumber($sVal, $iDecimals = 2) {

	if ($sVal == "") {
		return 0;
	}

	return number_format($sVal, $iDecimals, ',', '.');
}

function getXlsNumberCel($sVal, $iDecimals = 2) {

    if ($sVal == "") {
        return 0;
    }


     $iNumber =     number_format($sVal, $iDecimals, '.', '');
   // $iNumber = rtrim($iNumber, '0');

    return $iNumber;
}


/**
 * @param $data array
 * @return string
 * header names => html header
 */
function getRaportHeader($data) {
	$r = "";
	$r .= "<table border =1 ><tr>";
	foreach ($data as $value) {
		$r .= "<td>".$value."</td>";
	}
	$r .= "</tr>\n";

	return $r;
}

/**
 * @param $data array
 * @return string
 * header names => html body
 */
function getRaportBody($aData, $aHeaderKeys) {
	$r = "";
	foreach ($aData as $rec) {
		$r .= "<tr>";
		foreach ($aHeaderKeys as $key => $value) {
			$r .= "<td>".$rec[$key]."</td>";
		}
		$r .= "</tr>\n";
	}

	return $r;
}

function getFooter() {
	return "</table>";
}

?>
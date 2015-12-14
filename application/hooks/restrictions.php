<?php
if (! defined('BASEPATH'))
	exit('No direct script access allowed');

class Restrictions {
	var $CI;

	function Restrictions() {
		$this->CI = & get_instance();
	}

	function index() {
            return;
		$aUserDetails = $this->CI->auth->getUserDetails();
		if (! in_array($aUserDetails["user_marca"], $this->CI->config->item("acces_permisiuni")) && (in_array($this->CI->uri->segment(1), array("permisiune", "acces", "rol")))) {
			show_er("Nu ai ce cauta aici!");
		}
	}

}

?>
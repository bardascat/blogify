<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['post_controller_constructor'][] = array(
        'class'    => 'Portaldown',
        'function' => 'index',
        'filename' => 'portaldown.php',
        'filepath' => 'hooks'
        );

$hook['post_controller_constructor'][] = array(
	'class'    => 'Restrictions',
	'function' => 'index',
	'filename' => 'restrictions.php',
	'filepath' => 'hooks'
);

$hook['post_system'][] = array(
	'class' => 'QueryLogHook',
	'function' => 'log_queries',
	'filename' => 'QueryLogHook.php',
	'filepath' => 'hooks'
);

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */
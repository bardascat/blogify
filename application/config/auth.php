<?php
//Config incarcat automat pentru clasa Auth

//Mesaj trimis in array JSON, cheie 'description' la expirarea sesiunii
$config['msg_nosession'] = 'Ne pare rau, dar sesiunea a expirat.';
$config['msg_accessrestricted'] = 'Ne pare rau, dar nu aveti acces la pagina ceruta.';

//Calea catre pagina de login (folosita la redirectare cind expira sesiunea)
$config['login_page'] = '';


//Calea catre pagina de acces interzis (folosita la redirectare cind accesul e interzis)
$config['access_restricted_page'] = 'sessions/accesInterzis';

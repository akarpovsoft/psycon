<?php

//$dbhost         = 'chat.psychic-contact.com';
$dbhost         = '66.178.176.109';
$dbusername     = 'psychi_chat';
$dbuserpassword = 'Des1gn';
$default_dbname = 'psychi_chatdata';

/*
$dbhost         = 'belahost.com';
$dbusername     = 'design_univer';
$dbuserpassword = 'js738hgTWhd';
$default_dbname = 'design_psy';
*/

$RR_HOST        = "db.psychic-contact.com";
$ssl_addr = "https://www.psychic-contact.com/chat";
$http_addr = "http://www.psychic-contact.com/chat";
$http_canada = "http://www.psychic-contact.ca/chat";
$https_canada = "https://www.psychic-contact.ca/chat";
$http_url=$http_addr;
$DOCUMENT_ROOT =  "/usr/local/psa/home/vhosts/psychic-contact.com/httpdocs/chat/";
$regards_mail = "<br><br>Regards,<br>Psychic Contact<br>www.psychic-contact.com/chat";

$out_addr = "http://www.psychic-contact.com/out/";

//$headers .= "From: Psychic Chat <$email_from>\r\n";
//$headers .="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";

@include($app_path."debug.php");

$HTTP_BASE_URL=$http_addr;
$HTTPS_BASE_URL=$ssl_addr;

?>

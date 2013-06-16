<?php

//$dbhost         = 'chat.psychic-contact.com';
$dbhost         = 'localhost';
$dbusername     = 'psychi_chat';
$dbuserpassword = 'Des1gn';
$default_dbname = 'psychi_chatdata';


$server = 'http://psychic_com/chat';
$http_path =  "http://psychic_com/advanced/";
$app_addr = "http://psychic_com/advanced/";
$RR_HOST        = "db.psychic-contact.com";
$ssl_addr = "http://psychic_com/ssl/advanced/";
$http_addr = "http://psychic_com/advanced/";
$http_canada = "http://psychic_com/advanced/";
$https_canada = "https://psychic_com/advanced/";
$http_url=$http_addr;
$DOCUMENT_ROOT =  "d:/projects/psychic_contact/com/httpdocs/advanced";
$regards_mail = "<br><br>Regards,<br>Psychic Contact<br>www.psychic-contact.com/chat";

$affiliate_script_path = "http://dev.psychic-contact.com/affpro";
//$headers .= "From: Psychic Chat <$email_from>\r\n";
//$headers .="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";

@include($app_path."debug.php");

$HTTP_BASE_URL=$http_addr;
$HTTPS_BASE_URL=$ssl_addr;
//days from period start when readers can submit invoices
define('INV_SUBM_DAYS',5);
?>

<?php


$IP_mail = getenv('REMOTE_ADDR');
$UserAgent_mail = getenv('HTTP_USER_AGENT');
$date_mail = date("g:i a M, j", time());
require_once($app_path.'inc/mail/htmlMimeMail.php');

define(SMTP_HOST, 'psychic-contact.com');
define(SMTP_PORT, '25');
define(SMTP_HELO, 'psychic-contact.com');
define(SMTP_AUTH, 'true');
define(SMTP_USER, 'javachat');
define(SMTP_PASSWORD, 'G0Live');
define(MAIL_SEND_TYPE, 'smtp');

$adm_email="javachat@psychic-contact.com";
//$adm_email = "design@belahost.com";

$headers = "From: Astral Contact<$adm_email>\r\nContent-Type: text/html;\r\n charset=\"UTF-8\"\r\n";
/*
if($headers_txt > 0)
{
        $headers.="Content-Type: text/plain\r\n";
}
else
{
        $headers.="Content-Type: text/html\r\n";
}
*/
function mail2($to, $subject, $message, $additional_headers='')
{	
	global $lang, $adm_email;
	$additional_headers = $lang['From'].": ".$lang['main_page_title']."<$adm_email>\r\nContent-Type: text/html; charset=UTF-8\r\n\r\n";
	mail($to, $subject, $message, $additional_headers);
}

function sendMail($to, $subject, $message, $headers='')
{
    global $adm_email, $headers_txt;
	mail($to, $subject, $message, $headers);
/*

    $mail = new htmlMimeMail;
    if($headers_txt < 1)
    {
        $mail->setHtml($message);
    }
    else
    {
        $mail->setText($message);
    }
    $mail->setFrom($lang['main_page_title']."  <$adm_email>");
    $mail->setSubject($subject);
    $mail->setReturnPath($adm_email);
    $mail->setCRLF("\n");
    $mail->setSMTPParams(SMTP_HOST, SMTP_PORT, SMTP_HELO, SMTP_AUTH, SMTP_USER, SMTP_PASSWORD);
    //$result = $mail->send(array($recepient), 'smtp');
    $result = $mail->send(array($to), MAIL_SEND_TYPE);
    */

}
//#############################################
//
//To Disable any e-mail just make it blank (ex. $new_sign_msg ="";).
//
//#############################################

//New Sign UP (chatsignup_form.php)
//$new_sign_msg ="UserID: $Row[3]<br>Password: $Row[2]<br>IP: $IP_mail<br>$UserAgent_mail";

//Reader Logs Off (monitor_logoff.php)
$reader_offline_msg = "$operator[login] ".$lang['email_messages_msg_2']." $date_mail<br>".$http_addr."chatourreaders.php<br>IP: $IP_mail<br>$UserAgent_mail";

//CC Authorization (chataddfunds2.php at https)
    //Success
$cc_auth_ok_msg =$lang['The_client']." (".$lang['Username'].": $client_login) ".$lang['authorized']." \$$amount ($minutes_db ".$lang['minutes'] ."+ $added_free_time ".$lang['free_minutes'].").<br>$date_mail<br>".$lang['IP'].": $IP_mail<br>$UserAgent_mail";
   //Fallen
$cc_auth_bad_msg =$lang['The_client']." (".$lang['Username'].": $client_login) ".$lang['not_authorized']." \$$amount. ".$lang['email_messages_msg_1']."<br>$date_mail<br>".$lang['IP'].": $IP_mail<br>$UserAgent_mail";

?>

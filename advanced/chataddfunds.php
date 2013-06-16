<?php
require_once("config/config.php");
session_start();

$login_operator_id = $HTTP_COOKIE_VARS[UserID_c];
$adds_pwd = "Rh3fgdjmsfgjUb";
$conn = mysql_connect($dbhost, $dbusername, $dbuserpassword);
mysql_select_db($default_dbname);
$strsql = "SELECT * FROM T1_1 WHERE rr_record_id='$login_operator_id'";

$rs = mysql_query($strsql, $conn) or die(mysql_error());
while ($row = mysql_fetch_assoc($rs)){
$password = $row["password"];
}
mysql_free_result($rs);
mysql_close($conn);
$time_now = time();

$password = "${adds_pwd}${password}$time_now";
$pwd = md5($password);
$add_ons="";
if(isset($HTTP_GET_VARS[session])){
$add_ons="&session=$HTTP_GET_VARS[session]&reader=$HTTP_GET_VARS[reader]";
}
Header("Location: $ssl_addr/chataddfunds1.php?login_operator_id=$login_operator_id&pwd=$pwd&time=${time_now}$add_ons");

?>

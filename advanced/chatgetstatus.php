<?php
require_once("common/database.php");

Header("Cache-Control: no-cache");
Header("Pragma: no-cache");
Header ("Content-type: image/jpeg");

$operator_id = addslashes($_GET['operator_id']);
$dblink = dbhandle();

$tablename        = storageGetUserTableByFormID($account_id, "Operators", &$storage_id);

$tableChatSessions        = storageGetUserTableByFormID($account_id, "ChatSessions", &$storage_id);
$modify_stat = @mysql_query("UPDATE T1_1 set status = 'offline' where type = 'reader' and (time_to_sec(now()) - time_to_sec(rr_lastaccess)) > 15");

if('wp' == $_GET['statusfor'])
{
  $strsql = "SELECT Wp_status from wp_readers where Reader_id = '$operator_id' and Wp_status = 'off'";
  $rs = mysql_query($strsql) or die(mysql_error());
  while ($row = mysql_fetch_assoc($rs)){
  $_GET['stat'] = 'offline';
  }
  mysql_free_result($rs);
}
else if(strlen($_GET['statusfor']) < 1)
{
  $strsql = "SELECT Wp_status from wp_readers where Reader_id = '$operator_id' and Wp_status = 'wponly'";
  $rs = mysql_query($strsql) or die(mysql_error());
  while ($row = mysql_fetch_assoc($rs)){
  $_GET['stat'] = 'offline';
  }
  mysql_free_result($rs);
}
$Result = @mysql_query("SELECT status FROM $tablename where rr_record_id='$operator_id' AND ((to_days(now())=to_days(laststatusupdate) and (time_to_sec(now()) - time_to_sec(laststatusupdate) < 10)) or status like 'break%')");
$Row = @mysql_fetch_row($Result);

if (empty($Row))
{
        $Row[0] = "offline";
}

if('offline' == $_GET['stat'])
{
   $status = 'offline';
}
else
{
    $status = $Row[0];
}

$Result = @mysql_query("SELECT rr_record_id FROM $tableChatSessions where reader_id='$operator_id' AND (to_days(now())=to_days(laststatusupdate) and (time_to_sec(now()) - time_to_sec(laststatusupdate) < 10))");
$count = @mysql_num_rows($Result);
if ($count>0)
{
        $status = "busy";
}
$readimg = true;
switch ($status)
{
        case "online":
                if('offline' == $_GET['stat'])
                {
                  $src = "images/status_offline.png";
                }
                else
                {
                  $src = "images/status_online.png";
                }
        break;

        case "busy":
                //$src = "images/status_busy.png";
$strsql = "SELECT Balance from Ping_chat where Reader = '$operator_id' order by Reader_ping desc limit 0, 1";
$rs = mysql_query($strsql) or die(mysql_error());
while ($row = mysql_fetch_assoc($rs)){
$Balance_rest = $row[Balance];
$Balance_rest_min = round($Balance_rest/60);
}
mysql_free_result($rs);

if($Balance_rest_min > 99)
{
$Balance_rest_min = 0;
        }
                 drawimg($Balance_rest_min);
        break;

        case "break10":
        //$src = "images/status_break10.jpg";
        drawimg('10', 'images/status_break_dyn.jpg');
        break;

        case "break9":
        //$src = "images/status_break9.jpg";
        drawimg('9', 'images/status_break_dyn.jpg');
        break;

        case "break8":
        //$src = "images/status_break8.jpg";
        drawimg('8', 'images/status_break_dyn.jpg');
        break;

        case "break7":
        //$src = "images/status_break7.jpg";
        drawimg('7', 'images/status_break_dyn.jpg');
        break;

        case "break6":
        //$src = "images/status_break6.jpg";
        drawimg('6', 'images/status_break_dyn.jpg');
        break;

        case "break5":
        //$src = "images/status_break5.jpg";
        drawimg('5', 'images/status_break_dyn.jpg');
        break;

        case "break4":
        //$src = "images/status_break4.jpg";
        drawimg('4', 'images/status_break_dyn.jpg');
        break;

        case "break3":
        //$src = "images/status_break3.jpg";
        drawimg('3', 'images/status_break_dyn.jpg');
        break;

        case "break2":
        //$src = "images/status_break2.jpg";
        drawimg('2', 'images/status_break_dyn.jpg');
        break;

        case "break1":
        //$src = "images/status_break1.jpg";
        drawimg('1', 'images/status_break_dyn.jpg');
        break;

        case "offline":
        default:
        //drawimg(2);

                $src = "images/status_offline.png";
        break;
}

//readfile( $DOCUMENT_ROOT.$src ) ;
if('true' == $readimg)
{
readfile( $src ) ;
}


mysql_close( $dblink ) ;

function drawimg($period = '0', $img = 'images/status_busy_dyn.jpg')
{
global $readimg;
$readimg = false;

$h_begin = 20;


$name    = "Back in $period mins";


if(!isset($quality)){
$quality = 100;
}

$r = 255;
$g = 255;
$b = 255;


//Header("Content-type: image/jpeg");
$im = imagecreatefromjpeg ("$img");
$w= imagesx($im);
$h= imagesy($im);
$tx_col = imagecolorallocate($im, $r, $g, $b);

$beginw_name=$w_begin;
$beginh_name=$h_begin;
$szf = 7.5;
$ang=0;
if(isset($bold)){
$font = "fonts/ariblk.ttf";
}
else{
$font = "fonts/ariblk.ttf";
}

$sz=imagettfbbox($szf, $angl, $font, $name);

$beginw_name = $sz[4]/2;
$beginw_name = $w/2-$beginw_name;

//$beginh_name = $sz[1]/2;
//$beginh_name = $h/2-$beginh_name;
$beginh_name = $beginh_name;
if($period > 0)
{
imagettftext($im, $szf, $ang, $beginw_name, $beginh_name, $tx_col, $font, $name);
}
Imagejpeg($im, '', $quality);
ImageDestroy($im);
        }
?>
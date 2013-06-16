<?php

require_once('database.php');
//require_once('dbaccount.php');
//require_once('dbsession.php');
require_once('common.php');
require_once('config/config.php');
require_once('ReadersOnline_class.php');

//online only sign
$online_only=$_GET['oo'];

//width of displayed grid (in columns count)
$display_columns=$_GET['dc'];
if(!isset($display_columns))
	$display_columns=1;

//reader's category
$category=$_GET['cat'];

// name of style pacakage
$style=$_GET['st'];
if(!isset($style))
	$style='standard';
	
// HTML id of div to resize	
$resize_element=$_GET['re'];
	
//background color
$bgcolor=$_GET['bg'];


//reader group
$group_id = $_GET['gr'];
if(!isset($group_id ))
	$group_id = 1; #default standard group
	
//Affiliate id
$affiliate_id = $_GET['aff_id'];
if(!isset($affiliate_id ))
	$affiliate_id = 1; #default

//no body tags and refresh
$nobody = $_GET['nb'];

//force online type shows users who is online even if them not showing in group
$force_online = ($_GET['fo'] == 1)? true : false;  


$conn = mysql_connect($dbhost, $dbusername, $dbuserpassword);
mysql_select_db($default_dbname);


$i = 1;


$readerObj = new ReadersOnline();
$readerObj->setGlobalDbConnection($conn);

$Readers=$readerObj->availableReaders($online_only, $category, $group_id, $force_online);

include("styles/".$style.'.inc.php');

if(!isset($nobody))
{
?>
<html>
<head>
<title>Reader's list</title>
<link type="text/css" rel="stylesheet" href="styles/<?php echo htmlentities($style);?>.css">

</head>
<body <?php if( isset($bgcolor))echo 'bgcolor="#'. htmlentities($bgcolor) .'"'; ?>>
<?php
}

DisplayReadersList($Readers,$cellpadding,$display_columns,$affiliate_id );

if(!isset($nobody))
{
	echo "</body>
</html>";
}
?>



<?php
require_once ($app_path."common/common.php");

if (isset($ONLOAD))
{
	$load_unload=" onload=\"$ONLOAD\"";
}
if (isset($ONUNLOAD))
{
	$load_unload.=" onunload=\"$ONUNLOAD\"";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $lang['main_page_title']; ?></title>

<?php
    $style_prfx = (isset($_GET['style'])? htmlentities($_GET['style']) : 'default');
?>
<link href="<?php echo $http_addr; ?>/css/stylesheet.css" rel="stylesheet" type="text/css" />
<SCRIPT language=JavaScript src="<?php echo $http_addr;?>common.js" type=text/javascript></SCRIPT>
</head>
<body <?php echo $load_unload; ?>>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Psychic-contact.net - Registration</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">

<link href="<?php echo Yii::app()->params['http_addr']; ?>css/psynet_styles.css" rel="stylesheet" type="text/css" media="screen" />
<style type="text/css">
body
{
	font-family: Arial, Helvetica, sans-serif;
        font-size: 0;
	color: black;
        background-repeat: repeat;
	background-image: url(<?php echo Yii::app()->params['http_addr'];?>'/images/psynet';
	background-color:<?php echo $data['bgcolor']; ?>;
        margin-top: 0;
	margin-bottom:0;
	margin-left:0;
	margin-right: 0;
}
#logo {
    width: <?php echo $data['logowidth']; ?>px;
    height: <?php echo $data['logoheight']; ?>px;
    background: url(<?php echo Yii::app()->params['http_addr'] ?>images/psynet/header.jpg) no-repeat;
}
</style>
</head>
    <body>
<div id="main" style="margin: 0 auto; padding-left: 0px;">
    <table width="892" height="125" cellpadding="0" cellspacing="0" style="margin-left: 0px;">
          <tr>
            <td width="354"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/psynet/logo_1.jpg" width="354" height="125"></td>
            <td width="176"><a href="#"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/psynet/logo_2.jpg" width="176" height="125" border="0"></a></td>
            <td><img src="<?php echo Yii::app()->params['http_addr']; ?>images/psynet/logo_3.jpg" width="362" height="125"></td>
          </tr>
        </table>
    <table cellpadding="0" cellspasing="0" width="100%" style="padding-left: 15px;">
        <tr>
            <td valign="top" width="100%" align="center">
                <div id="content">
                    <table width="100%" height="100%" cellpadding="0" cellspacing="0">
                      <tr>
                        <td height="52" align="center" colspan="2" background="<?php echo Yii::app()->params['http_addr']; ?>images/psynet/w2_1.gif" bgcolor="#5c0001" style="padding-left:10px">
                            <h1><?php echo $this->pageTitle; ?></h1>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2"  bgcolor="#f4d992" style="border-left:solid #c0a160 1px; border-right:solid #c0a160 1px; padding:10px; color: black; font-size: 11px;">
                            <?php echo $content; ?>
                        </td>
                      </tr>
                      <tr>
                        <td height="17" background="<?php echo Yii::app()->params['http_addr']; ?>images/psynet/w2_2.gif" bgcolor="#f4d992"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/psynet/w2_3.gif" width="1" height="17"></td>
                        <td align="right" background="<?php echo Yii::app()->params['http_addr']; ?>images/psynet/w2_2.gif" bgcolor="#f4d992"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/psynet/w2_3.gif" width="1" height="17"></td>
                      </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</div>
    </body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="Description" content="<?php echo Yii::app()->params['siteName']; ?> Online psychics and Spiritual Psychic Advisors offering professional Psychic Online Readings,Tarot Readings, Live Online Psychic Chat and Email Readings." />
    <meta name="Keywords" content="<?php echo Yii::app()->params['siteName']; ?> Chat, Online Psychic Readings, Free Psychic chat Readings, chat online, psychic readers, psychic, psychics, online, online psychic readings, psychic reading, online psychic chat, free psychic reading, online psychic reading, Email Readings, psychic tarot reading, online tarot Readings, free psychics online "/>
    <meta name="Rating" content="General"/>
    <meta name="author" content="PsychicContact"/>
    <meta name="robots" content="index,follow"/>
    <meta name="robots" content="all"/>
    <meta name="copyright" content="Copyright 2002-2010 <?php echo Yii::app()->params['siteName']; ?> Inc">
    <meta name="Classification" content="business">
    <meta name="Language" content="en-us">

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/common.js"></script>
<script type="text/javascript" src="/advanced/js/jquery-1.4.4-tuned.js"></script>
<script type="text/javascript" src="/advanced/js/jquery.json-2.2.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" />

<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body text=#000000 vLink=#333366 aLink=#333366 link=#333366 bgColor="#BF9DBF" background="<?php echo Yii::app()->params['ssl_addr']; ?>images/bg.gif" topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<table width="838" border="0" cellspacing="0" cellpadding="0" align="center" style="height:100% ">
  <tr>
    <td valign="top" height="343px" align='center' bgcolor="#ffffff">
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td colspan="6">
                    <img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/source/head.jpg">
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/source/line.jpg">
                </td>
            </tr>
            <tr valign="top">
                <td>
                    <img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/source/PsychicReadings.jpg">
                </td>
                <?php $this->widget('SslTopMenu'); ?>
            </tr>
        </table>
   </td>
 </tr>
 <tr>
   <td valign="top" align="left" bgcolor="#FFFFFF"><table border="0" width="810" id="table1" cellspacing="0" cellpadding="0">
        <tr>
            <td width="202" valign="top">
                <?php $this->widget('SslLeftMenu') ?>
           </td>
           <td valign="top" class="maintexttd">
                <?php echo $content; ?>
           <br>
           </td>
    </tr>
    </table>
  </td>
</tr>
  <tr>
    <td height="5" width="838" valign="top" class="footer" align="center">
                <span>
                    <table>
                        <tr>
                            <?php $this->widget('BottomMenu'); ?>
                        </tr>
                    </table>
                <?php echo Yii::t('lang', 'Copyright_1998'); ?> <?php echo date('Y'); ?> <?php echo Yii::t('lang', 'NS_Jayson'); ?> </span>
    </td>
  </tr>
</table>
</body>
</html>
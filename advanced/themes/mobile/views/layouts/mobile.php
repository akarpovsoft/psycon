<HTML><HEAD>
<TITLE><?php echo Yii::t('lang', 'NS'); ?> - <?php echo Yii::t('lang', 'Overview'); ?></TITLE>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<meta name="Description" content="<?php echo Yii::app()->params['siteName']; ?> Online psychics and Spiritual Psychic Advisors offering professional Psychic Online Readings,Tarot Readings, Live Online Psychic Chat and Email Readings." />
<meta name="Keywords" content="<?php echo Yii::app()->params['siteName']; ?> Chat, Online Psychic Readings, Free Psychic chat Readings, chat online, psychic readers, psychic, psychics, online, online psychic readings, psychic reading, online psychic chat, free psychic reading, online psychic reading, Email Readings, psychic tarot reading, online tarot Readings, free psychics online "/>
<meta name="Rating" content="General"/>
<meta name="author" content="PsychicContact"/>
<meta name="robots" content="index,follow"/>
<meta name="robots" content="all"/>
<meta name="copyright" content="Copyright 2002-2010 <?php echo Yii::app()->params['siteName']; ?> Inc">
<meta name="Classification" content="business">
<meta name="Language" content="en-us">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
<SCRIPT language=JavaScript src="<?php echo Yii::app()->params['site_domain']; ?>/chat/common.js" type="text/javascript"></SCRIPT>
<link type="text/css" rel="stylesheet" href="<?php echo Yii::app()->params['http_addr'];?>/css/style.css">
</HEAD>

<BODY text=#000000 vLink=#333366 aLink=#333366 link=#333366 topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
    <table width="300" border="0" cellspacing="0" cellpadding="0" align="left" bgcolor="white">
        <tr>
            <td valign="top" align="left">
            <?php $this->widget('HybridMenu'); ?>
            </td>
        </tr>
        <tr>
            <td valign="top" align="left" style="padding-left: 5px;">
            <?php echo $content; ?>
            </td>
        </tr>
    </table>
</BODY>
</HTML>
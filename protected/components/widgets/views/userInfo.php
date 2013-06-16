<script type="text/javascript">
    function openQuest()
    {
        w2 = window.open("<?php echo Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_NRR_QUEST_NEW); ?>","Questionnaire","width=600,height=600,toolbar=0,location=0,status=0,menubar=0,scrollbars=1,resizable=1");
        window.w2.focus();
    }

    function openHelp()
    {
        w2 = window.open("<?php echo Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_CHATBALANCE); ?>","Questionnaire","width=300,height=150,toolbar=0,location=0,status=0,menubar=0,scrollbars=1,resizable=1");
        window.w2.focus();
    }

    function OpenMonitorWindow()
    {
//        var date = new Date() ;
//	unique = getUnique() ;
//	url = "<?php echo Yii::app()->params['site_domain'] ?>/chat/chatmonitor.php?sid=<?php echo Yii::app()->user->id?>&username=<?php echo $username; ?>&unique="+unique;
//	newwin = window.open(url, "monitorchatwindow", "scrollbars=yes,menubar=no,resizable=1,location=no,width=600,height=360,left=200,top=200");
//	newwin.focus() ;
//	update_status();
        
        var date = new Date() ;
        url = "<?php echo Yii::app()->params['http_addr']; ?>chat/monitor/pre";
        newwin = window.open(url, "monitorchatwindow", "scrollbars=yes,menubar=no,resizable=1,location=no,width=600,height=360,left=200,top=200");
        newwin.focus() ;
    }
    <?php if(Yii::app()->user->type == 'reader'): ?>
    function getReaderStatus(reader_id){
        jQuery.post('<?php echo Yii::app()->params['http_addr']; ?>users/loadReaderStatus', { 'reader_id' : reader_id }, function(html){
            jQuery('#status').html(html);
        }, 'html');
    }
var mytimer = setInterval("getReaderStatus(<?php echo Yii::app()->user->id; ?>)",30000) ;
<?php endif; ?>
</script>
<?php if($type == 'new'): ?>
<div class="client_details">
    <p class="member"><strong><?php echo Yii::t('lang','Name'); ?>:</strong> <?php echo $username; ?>
    <br /><strong><?php echo Yii::t('lang','Email'); ?>:</strong> <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
    <br /><strong>Status:</strong> Client </p> 

    <p style="float: left">
         <strong>Balance:</strong> <?php echo $balance; ?>&nbsp;Minutes&nbsp;
         <br /><a href="javascript:openHelp()">What is this?</a>		
    <br /><br /><input type="button" value="NRR" onclick="openQuest();">
    </p><div class="clear"></div>
</div>
<?php else: ?>
<table cellspacing=0 cellpadding=0 border=0 width="100%">
    <tr>
        <td width="50%">
            <table cellspacing=0 cellpadding=0 border=0 width="100%">
                <tr>
                    <td align=left>
                        <span class=TextMedium>
                            <b><?php echo Yii::t('lang','Name'); ?>:</b> <?php echo $username; ?><br>
                            <b><?php echo Yii::t('lang','Email'); ?>:</b> <a class=LinkMedium href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a><br>
                        </span>
                        <table cellspacing=0 cellpadding=0 border=0>
                            <tr>
                                <td nowrap valign="middle">
                                    <b><?php echo Yii::t('lang','Status'); ?>:</b> <?php echo $status; ?>
                                    <!-- THIS BLOCK MUST BE CHANGED!!!! -->
                                    <?php if($status == 'Reader'): ?>
                                    <div id="status">
                                        <script type="text/javascript">
                                            getReaderStatus(<?php echo Yii::app()->user->id; ?>);
                                        </script>                                        
                                    </div>
                                    <?php endif; ?>
                                    <!-- ENDBLOCK -->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php if($status == 'Reader'): ?>
                <tr>
                    <td>
                        <br><br><?php echo Yii::t('lang', 'Welcome_back'); ?> <b> <?php echo $username; ?>!</b><br><br>
                        <?php echo Yii::t('lang', 'As_soon_as_you_are_ready'); ?><br><br>
                    </td>
                </tr>
                <tr>
                    <td>
                            <?php $this->widget('PendingEmailAndNrr'); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="javascript:OpenMonitorWindow()"> <?php echo Yii::t('lang', 'Open_Chat_Monitor_Window'); ?></a><br><br>
                    </td>
                </tr>
                <?php endif; ?>
            </table>
        </td>
        <td>

            <?php if($account_page) : ?>
            <table width="100%" cellspacing=0 cellpadding=0 border=0 bgcolor="#ccccc33">
                <tr>
                    <td width="1"><img src="images/pixel.gif" height="1" width="1" border="0"></td>
                    <td><img src="images/pixel.gif" height="1" width="1" border="0"></td>
                    <td width="1"><img src="images/pixel.gif" height="1" width="1" border="0"></td>
                </tr>
                <tr>
                    <td width="1"><img src="images/pixel.gif" height="1" width="1" border="0"></td>
                    <td width="100%">
                            <?php if(($status == 'Client')||($status == 'Administrator')||($status == 'gift_chat')):?>
                        <table width="100%" cellspacing="0" cellpadding="4" border="0" bgcolor="#ffffcc">
                            <tr>
                                <td class="pptextbold" style="color: black;"><?php echo Yii::t('lang','Balance'); ?>:</td>
                                <td class="pptext" style="color: black;" align=right><?php echo $balance; ?>&nbsp;<?php echo Yii::t('lang','minutes'); ?>&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="pptext" colspan=2><a class=LinkSmall href="javascript:openHelp()"><?php echo Yii::t('lang','What_is_this'); ?></a></td>
                            </tr>
                                    <?php if(($status == 'Client')): ?>
                            <tr>
                                <td colspan=2 align="right">
                                    <input type="button" value="NRR" onclick="openQuest();">
                                </td>
                            </tr>
                                    <?php endif; ?>
                        </table>
                            <?php endif; ?>
                    </td>
                    <td width="1"><img src="images/pixel.gif" height="1" width="1" border="0"></td>
                </tr>
                <tr>
                    <td width="1"><img src="images/pixel.gif" height="1" width="1" border="0"></td>
                    <td><img src="images/pixel.gif" height="1" width="1" border="0"></td>
                    <td width="1"><img src="images/pixel.gif" height="1" width="1" border="0"></td>
                </tr>
            </table>
            <?php endif; ?>

        </td>
    </tr>
</table>
<?php endif; ?>
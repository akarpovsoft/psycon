<script src="../js/prototype.js" type="text/javascript"></script>
<script type="text/javascript">
function getReaderStatus(reader_id){
        jQuery.post('<?php echo Yii::app()->params['http_addr']; ?>users/loadReaderStatus', { 'reader_id' : reader_id }, function(html){
            jQuery('#status').html(html);
        }, 'html');
    }
var mytimer = setInterval("getReaderStatus(<?php echo $reader->rr_record_id; ?>)",60000);

</script>
<table border="0" cellpadding="0" cellspacing="0" width="550">
    <tbody>
        <tr>
            <td background="<?php echo Yii::app()->params['http_addr']; ?>images/index_files/boxldevelop1.gif" width="25" height="31" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/transp.gif" width="25" height="31"></td>
            <td width="100%">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <td background="<?php echo Yii::app()->params['http_addr']; ?>images/index_files/boxdevelop1.gif" valign="top" nowrap="nowrap">
                                <table background="" border="0" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr height="11">
                                            <td width="1"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/transp.gif" width="1" height="11"></td>
                                        </tr>
                                        <tr>
                                            <td nowrap="nowrap"><span class="TextButton"><font color="#42595a"><b><?php echo Yii::t('lang','More_info_about') ?> <?php echo $reader->name; ?></b></font></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td background="<?php echo Yii::app()->params['http_addr']; ?>images/index_files/boxudevelop1.gif" width="18" height="31" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/transp.gif" width="18" height="31"></td>
                            <td background="<?php echo Yii::app()->params['http_addr']; ?>images/index_files/boxuu1.gif" width="100%" height="31" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/transp.gif" height="31"></td>
                        </tr>
                </table>
            </td>
            <td width=14 height=31 nowrap background="<?php echo Yii::app()->params['http_addr']; ?>images/index_files/boxrdevelop1.gif"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/transp.gif" width=14 height=31></td>
            <td nowrap width=1 ><img src="<?php echo Yii::app()->params['http_addr']; ?>images/transp.gif" width=1></td>
            <td width=1 nowrap><img src="<?php echo Yii::app()->params['http_addr']; ?>images/transp.gif" ></td>
        </tr>
        <tr>
            <td width=25 nowrap background="<?php echo Yii::app()->params['http_addr']; ?>images/index_files/boxl1.gif"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/transp.gif" width=25></td>
            <td  bgcolor="#F7F7F7">
                    <table border="0" width="580">
                        <tbody>
                            <tr>
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <?php echo $avatar ?><br>
                                    <div id="status">
                                        <script type="text/javascript">
                                            getReaderStatus(<?php echo $reader->rr_record_id; ?>)
                                        </script>
                                    </div>
                                    <?php if($customer == 'Administrator'): ?>
                                    <br>
                                    <table border="0" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr height="6">
                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_topleft.gif" width="5" height="6" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5" height="6"></td>
                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_top.gif" height="6" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" height="6"></td>
                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_topright.gif" width="5" height="6" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5" height="6"></td>
                                            </tr>
                                            <tr>
                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_left.gif" width="5" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5"></td>
                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_bg.gif" nowrap="nowrap">
                                                    <a class="TextButton" href="<?php echo Yii::app()->params['site_domain'].'/chat/reader_edit.php'; ?>?id=<?php echo $reader->rr_record_id; ?>">Edit/Delete this info </a></td>
                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_right.gif" width="5" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5"></td>
                                            </tr>
                                            <tr height="5">
                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_bottomleft.gif" width="5" height="5" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5" height="5"></td>
                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_bottom.gif" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif"></td>
                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_bottomright.gif" width="5" height="5" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5" height="5"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <?php else: ?>
                                    <?php if($online && $customer == 'client'): ?>
                                    <br>
                                    <table border="0" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr height="6">
                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_topleft.gif" width="5" height="6" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5" height="6"></td>
                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_top.gif" height="6" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" height="6"></td>
                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_topright.gif" width="5" height="6" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5" height="6"></td>
                                            </tr>
                                            <tr>
                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_left.gif" width="5" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5"></td>
                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_bg.gif" nowrap="nowrap">
                                                    <a class="TextButton" href="<?php echo Yii::app()->params['site_domain'].'/chat/chatstart.php'; ?>?chat=<?php echo $reader->rr_record_id; ?>">&nbsp; &nbsp; &nbsp; Chat&nbsp; &nbsp; &nbsp; </a></td>
                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_right.gif" width="5" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5"></td>
                                            </tr>
                                            <tr height="5">
                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_bottomleft.gif" width="5" height="5" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5" height="5"></td>
                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_bottom.gif" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif"></td>
                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_bottomright.gif" width="5" height="5" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5" height="5"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <?php endif; ?> 
                                    <?php endif; ?> 
                                </td>
                                <td valign="top" width="100%">
                                    <table border="0">
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td><span class="TextTiny"><?php echo $reader->area; ?></span></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td width="100%">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tbody>
                                                            <tr height="5">
                                                                <td><img src="../images/transp.gif"></td>
                                                            </tr>
                                                            <tr height="1">
                                                                <td bgcolor="#cecece" width="100%" nowrap="nowrap"><img src="../images/transp.gif"></td>
                                                            </tr>
                                                            <tr height="1">
                                                                <td bgcolor="white" width="100%" nowrap="nowrap"><img src="../images/transp.gif"></td>
                                                            </tr>
                                                            <tr height="5">
                                                                <td><img src="../images/transp.gif"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><span class="TextSmall"><?php echo $reader->comments; ?></span></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td width="100%">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tbody>
                                                            <tr height="5">
                                                                <td><img src="../images/transp.gif"></td>
                                                            </tr>
                                                            <tr height="1">
                                                                <td bgcolor="#cecece" width="100%" nowrap="nowrap"><img src="../images/transp.gif"></td>
                                                            </tr>
                                                            <tr height="1">
                                                                <td bgcolor="white" width="100%" nowrap="nowrap"><img src="../images/transp.gif"></td>
                                                            </tr>
                                                            <tr height="5">
                                                                <td><img src="../images/transp.gif"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <marquee behavior="scroll" direction="up" scrollamount="1" height="150" width="500" onmouseover="this.scrollAmount=0" onmouseout="this.scrollAmount=1">
                                                    <?php foreach($testimonials as $t): ?>
                                                        <b><?php echo $t->tm_date; ?></b>&nbsp;&nbsp;<b><?php echo $t->tm_member; ?></b><br>            
                                                        <?php echo nl2br($t->tm_text); ?><br><br>
                                                    <?php endforeach; ?>
                                                    </marquee>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <?php if($reader->rr_record_id == Yii::app()->user->id || $customer == 'Administrator'): ?>
                                    <br><br>
                                    Online time: <b><?php echo $totalOnline['hours_online']; ?></b> hours <?php echo $totalOnline['minutes_online']; ?> minutes since <?php echo $totalOnline['since_time']; ?><br>
                                    <?php endif; ?>
                                    <br><br>
                                    <?php echo Yii::t('lang', 'chatourreaders_one_txt1_1'); ?>: <b><?php echo date("l M d, Y"); ?></b><br>
                                    <?php echo Yii::t('lang', 'chatourreaders_one_txt1_1_1'); ?>:<br>
                                    <b><?php echo date("l M d, Y", time()+(86400*$emailperiod)); ?></b><br><br>
                                    <?php if($spec != '0.00'): ?>
                                        <?php echo Yii::t('lang', 'Special_Email_reading_is'); ?>
                                        <a href="<?php echo Yii::app()->params['ssl_addr']; ?>pay/emailreading?reader_id=<?php echo $reader->rr_record_id ?>"
                                           class="LinkMedium">
                                            available
                                        </a> ($<?php echo $spec; ?>)<br><br>
                                        <?php echo $reader->special_reading; ?>
                                    <?php endif; ?><br><br>
                                    <iframe src="http://www.facebook.com/plugins/like.php?href=www.psychic-contact.com&amp;layout=standard&amp;show_faces=false&amp;width=200&amp;action=like&amp;font=verdana&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:400px; height:80px;" allowTransparency="true"></iframe>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" width="100%">
                                    &nbsp;
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td width="100%"></td>
                                                <td width="10" nowrap="nowrap"></td>
                                                <td>
                                                    <table border="0" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr height="6">
                                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_topleft.gif" width="5" height="6" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5" height="6"></td>
                                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_top.gif" height="6" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" height="6"></td>
                                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_topright.gif" width="5" height="6" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5" height="6"></td>
                                                            </tr>
                                                            <tr>
                                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_left.gif" width="5" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5"></td>
                                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_bg.gif" nowrap="nowrap">
                                                                    <a class="TextButton" href="<?php echo Yii::app()->params['http_addr'].'site/ourreaders'; ?>" onmouseover="window.status='  << Back  '; return true;" onclick="window.status=''; return true;" onmouseout="window.status=''; return true;">&nbsp;&nbsp;&nbsp;&nbsp;&lt;&lt; <?php echo Yii::t('lang', 'Back'); ?> &nbsp;&nbsp;&nbsp;&nbsp;</a></td>
                                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_right.gif" width="5" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5"></td>
                                                            </tr>
                                                            <tr height="5">
                                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_bottomleft.gif" width="5" height="5" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5" height="5"></td>
                                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_bottom.gif" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif"></td>
                                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_bottomright.gif" width="5" height="5" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5" height="5"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <?php if(!Yii::app()->user->isGuest): ?>
                                                <td>
                                                    <table border="0" cellpadding="0" cellspacing="0">
                                                        <tbody><tr height="6">
                                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_topleft.gif" width="5" height="6" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5" height="6"></td>
                                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_top.gif" height="6" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" height="6"></td>
                                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_topright.gif" width="5" height="6" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5" height="6"></td>
                                                            </tr>
                                                            <tr>
                                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_left.gif" width="5" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5"></td>
                                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_bg.gif" nowrap="nowrap">
                                                                    <a class="TextButton" href="<?php echo Yii::app()->params['http_addr'].'users/mainmenu?favorite='.$reader->rr_record_id; ?>" onmouseover="window.status='Set as my Favorite'; return true;" onclick="window.status=''; return true;" onmouseout="window.status=''; return true;">&nbsp;&nbsp;<?php echo Yii::t('lang', 'Set_as_my_Favorite'); ?>&nbsp;&nbsp;</a></td>
                                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_right.gif" width="5" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5"></td>
                                                            </tr>
                                                            <tr height="5">
                                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_bottomleft.gif" width="5" height="5" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5" height="5"></td>
                                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_bottom.gif" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif"></td>
                                                                <td background="<?php echo Yii::app()->params['http_addr'] ?>images/button_bottomright.gif" width="5" height="5" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr'] ?>images/transp.gif" width="5" height="5"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <?php endif; ?>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
            </td>
            <td nowrap width=14 nowrap background="<?php echo Yii::app()->params['http_addr']; ?>images/index_files/boxright1.gif"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/transp.gif" width=14></td>
            <td nowrap width=1 ><img src="<?php echo Yii::app()->params['http_addr']; ?>images/transp.gif" width=1></td>
        </tr>
        <tr>
            <td background="<?php echo Yii::app()->params['http_addr']; ?>images/index_files/boxlddevelop1.gif" width="15" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/transp.gif" width="15" height="12"></td>
            <td background="<?php echo Yii::app()->params['http_addr']; ?>images/index_files/boxdddevelop1.gif" width="100%" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/transp.gif" height="12"></td>
            <td background="<?php echo Yii::app()->params['http_addr']; ?>images/index_files/boxrddevelop1.gif" width="14" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/transp.gif" width="14" height="12"></td>
            <td width="1" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/transp.gif" width="1" height="12"></td>
            <td width="1" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/transp.gif" width="1" height="12"></td>
        </tr>
    </tbody>
</table>

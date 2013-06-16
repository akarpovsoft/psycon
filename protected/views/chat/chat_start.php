<script type="text/javascript">
    function check(){
        x = 0;
        error_msg = "<?php echo Yii::t('lang', 'Some_errors'); ?>:\n\n"
        if(document.getElementById("reader").value == "0"){
            x = 1;
            error_msg += "* <?php echo Yii::t('lang', 'Reader_is_not_checked'); ?>\n";
        }
        if(document.getElementById("subject").value == ""){
            x = 1;
            error_msg += "* <?php echo Yii::t('lang', 'Chat_topic_cannot_be_blank'); ?>\n";
        }
        if(x == 1){
            alert(error_msg);
            return false;
        }
    }
    
</script>
<?php echo Yii::t('lang', 'Start_Chat'); ?>
<hr>
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
                                            <td nowrap="nowrap"><span class="TextButton"><font color="#42595a"><b><?php echo Yii::t('lang', 'Chat_Start'); ?></b></font></span></td>
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
                <?php if(isset($no_one_readers_online)): ?>
                    <br><center><h3><?php echo Yii::t('lang', 'Sorry_but_there_is_no_readers_currently_online'); ?></h3></center><br>
                <?php else: ?>
                <?php echo CHtml::beginForm('', 'post', array('onSubmit' => 'return check();')); ?>
                    <input name="client_id" value="<?php echo $client->rr_record_id ?>" type="hidden">
                    <?php echo Yii::t('lang', 'chatstart_msg_8'); ?><br><br>
                    <?php if(!isset($main_group_reader)): ?>
                        <?php echo (isset($avaliable)) ?  Yii::t('lang', 'chatstart_msg_6_1').' - "'.$avaliable.'"'.Yii::t('lang', 'is_online ').'!<br><br>' : Yii::t('lang', 'Your_favorite_reader_is_currently_unavaliable').'!<br><br>'; ?>
                    <?php endif; ?>
                    <b><font color="#ff0000"><?php echo Yii::t('lang', 'chatstart_msg_9_1'); ?><br>
                            <br>
                            <?php echo Yii::t('lang', 'If_you_experience_problems_accessing'); ?>
                        </font></b><br><br>
                    <b>
                        <?php echo Yii::t('lang', 'FREE_10MINS_FIRST_TIME'); ?>
                    <br><br>

                    <b><?php echo Yii::t('lang', 'REPEAT_CLIENTS'); ?>:</b><br>

                    <span style="color: rgb(0, 0, 255); font-style: italic; font-weight: bold;">"<?php echo Yii::t('lang', 'what_if_I_dont_use_any'); ?>"</span>
                    <br><br>
                    <?php echo Yii::t('lang', 'Answer'); ?>:
                    <br><br>
                     <?php echo Yii::t('lang', 'If_you_purchase_new_chat_time'); ?>
                    <br><br>

                    <?php echo Yii::t('lang', 'You_have'); ?> <b><?php echo round($client->balance); ?></b> <?php echo Yii::t('lang', 'chatstart_msg_9_3'); ?><br>
                    <?php echo $client->name; ?>
                    <?php echo Yii::t('lang', 'chatstart_msg_9_4_mod'); ?>
                    <br><br>
                    <input value="<?php echo $client->balance; ?>" checked="checked" id="time" name="time" type="radio" /> <?php echo Yii::t('lang', 'ALL_OF_MY_STORED_TIME'); ?> (<?php echo $client->balance; ?> <?php echo Yii::t('lang', 'mins'); ?>)<br>
                    <?php
                    $client_balance = round($client->balance);
                    if($client_balance >= 60)
                            echo '<input value="60" name="time" type="radio"> 60 '.Yii::t('lang', 'mins').'<br>';
                    if ($client_balance >= 45)
                            echo '<input value="45" name="time" type="radio"> 45 '.Yii::t('lang', 'mins').'<br>';
                    if ($client_balance >= 30)
                    	echo '<input value="30" name="time" type="radio"> 30 '.Yii::t('lang', 'mins').'<br>';
                    if($client_balance >= 15)
                            echo '<input value="15" name="time" type="radio"> 15 '.Yii::t('lang', 'mins').'<br>';
                    if($client_balance >= 5)
                            echo '<input value="5" name="time" type="radio"> 5 '.Yii::t('lang', 'mins').'[TEST OPTION]<br>';
                    ?>
                    <br>
                    <table border="0" width="100%">
                        <tbody><tr>
                                <td nowrap="nowrap">
                                    <span class="TextSmall"><b><?php echo Yii::t('lang', 'Balance'); ?>:</b></span>
                                </td>
                                <td width="15" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/transp.gif"></td>
                                <td>

                                    <span class="TextMedium"><?php echo $client->balance; ?>&nbsp;Minutes</span>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap="nowrap">
                                    <span class="TextSmall"><b><?php echo Yii::t('lang', 'Reader'); ?>:</b></span>
                                </td>

                                <td width="15" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/transp.gif"></td>
                                <td>
                                    <span class="TextMedium">
                                        <select class="SelectBox" name="reader" id="reader">
                                            <option value="0">--  <?php echo Yii::t('lang', 'Please_Select'); ?> --</option>
                                            <?php if(isset($main_group_reader)): ?>
                                            <option value="<?php echo $main_group_reader->rr_record_id; ?>" selected><?php echo $main_group_reader->name; ?>
                                            <?php else: ?>
                                            <?php foreach($readers_online as $reader): ?>
                                            <option value="<?php echo $reader['rr_record_id']; ?>"
                                            <?php
                                            if($reader['rr_record_id'] == $with)
                                                echo 'selected';
                                            else
                                                echo ($reader['rr_record_id'] == $favorite_reader) ? 'selected' : '' ?>
                                            >
                                            <?php echo $reader['name']; ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td nowrap="nowrap">
                                    <span class="TextSmall"><b><?php echo Yii::t('lang', 'Chat_Topic'); ?>:</b></span>
                                </td>
                                <td width="15" nowrap="nowrap"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/transp.gif"></td>
                                <td>
                                    <input class="InputBoxFront" maxlength="50" name="subject" id="subject">
                                </td>

                            </tr>
                            <tr>

                                <td colspan="3">
                                    <br>
                                    <b><font color="#ff0000"> 
                                    <?php echo Yii::t('lang', 'chatstart_msg_12_mod'); ?>
                                    </font></b></td>
                            </tr>
                        </tbody></table>

                    <table border="0" width="100%">
                        <tbody><tr>
                                <td>
                                    <span style="font-size: 12px; font-weight: bold; color: rgb(128, 0, 255);">
				                    <?php echo Yii::t('lang', 'chatstart_msg_13'); ?>
                                    </span>
                                    <span style="font-size: 12px; font-weight: bold; color: rgb(0, 0, 0);">
				                    Jayson Lynn<br>
                                    </span>
                                </td>

                                <td align="right"><br><br>
                                	<input type="submit" id="start" name="start" <?php if ((round($client->balance))<=0) { echo 'disabled="disabled"'; } ?> value="<?php echo Yii::t('lang', 'Chat_Now'); ?> >>" />
                            	</td>
                            </tr>

                        </tbody></table>
                    <p><b><a href="<?php echo Yii::app()->params['site_domain']; ?>/chat/iesettings/"> <?php echo Yii::t('lang', 'Having_PROBLEMS'); ?>.</a></b></p>
                    <p><b><a href="javascript:macusr()"><?php echo Yii::t('lang', 'Mac_users_here'); ?></a></b></p>
                <?php echo CHtml::endForm(); ?>
            <?php endif; ?>
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
<script language="JavaScript">
	function macusr(){
    	alert('<?php echo Yii::t('lang', 'chatstart_msg_11'); ?>');
    }
    function testXHR() {
    	try {
    		tst = new window.XMLHttpRequest();
    		tst = null;
    	} catch (err) {
	    	el=document.getElementById("start");
	    	el.disabled=true;
	    	alert('Your browser doesn\'t support native Ajax (XMLHtppRequest) object.Unfortunately chat can\'t be started.If you use IE 8, please turn on "Native XMLHTTP support" checkbox in Advanced Settings');
    	}
    }
    
    testXHR();
</script>

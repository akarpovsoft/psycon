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
        if(document.forms["Chat"].oldchat.checked || document.forms["Chat"].nopopup_debug.checked)
        {
            document.forms["Chat"].action = "<?php echo Yii::app()->params['site_domain'] ?>/chat/chatstart0.php";
            document.forms["Chat"].method = "GET";
            document.forms["Chat"].submit();
        }
    }
    
</script>
<?php if(isset($no_one_readers_online)): ?>
    <br><center><h3><?php echo Yii::t('lang', 'Sorry_but_there_is_no_readers_currently_online'); ?></h3></center><br>
<?php else: ?>
<?php echo CHtml::beginForm(ChatHelper::moduleUrl().'/client/chatRequest', 'post', array('name' => 'Chat', 'onSubmit' => 'return check();')); ?>
    <input name="client_id" value="<?php echo $client->getId(); ?>" type="hidden">
    <input type=hidden name=account_id value="1">
    <input type="hidden" name="start" value="1">
    <input type="hidden" name="debug" value="1">
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

    "<?php echo Yii::t('lang', 'what_if_I_dont_use_any'); ?>"
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
    ?>
    <br>
    <table border="0" width="100%">
        <tbody><tr>
                <td nowrap="nowrap">
                    <?php echo Yii::t('lang', 'Balance'); ?>:
                </td>
                <td width="15" nowrap="nowrap"><img src="<?php echo ChatHelper::baseUrl(); ?>images/transp.gif"></td>
                <td>
                    <?php echo $client->balance; ?>&nbsp;Minutes
                </td>
            </tr>
            <tr>
                <td nowrap="nowrap">
                    <?php echo Yii::t('lang', 'Reader'); ?>:
                </td>
                <td width="15" nowrap="nowrap"><img src="<?php echo ChatHelper::baseUrl(); ?>images/transp.gif"></td>
                <td>
                    <?php //var_dump($favorite_reader->value); die(); ?>
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
                                echo ($reader['rr_record_id'] == $favorite_reader->value) ? 'selected' : '' ?>
                            >
                            <?php echo $reader['name']; ?>
                            </option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                </td>
            </tr>

            <tr>
                <td nowrap="nowrap">
                    <?php echo Yii::t('lang', 'Chat_Topic'); ?>:
                </td>
                <td width="15" nowrap="nowrap"><img src="<?php echo ChatHelper::baseUrl(); ?>images/transp.gif"></td>
                <td>
                    <input class="InputBoxFront" maxlength="50" name="subject" id="subject">
                </td>
            </tr>
            <tr>
                <td style="font-size: 13px;">
                    <input type="checkbox" name="oldchat"> Old chat
                </td>
            </tr>
            <tr>
                <td colspan="2" style="vertical-align: middle; font-size: 13px;">
                    <input name="nopopup_debug" value="yes" type="checkbox"> <font color="#FF0000">[Emergency Chat Room] Use only if the regular chat room is failing.</font><br>
                        PLEASE NOTE: We are upgrading our server &amp; the Emergency Chat may not record all of your chat- please try to use our regular chat instead<br>
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
                        <?php echo Yii::t('lang', 'chatstart_msg_13'); ?>
                        Jayson Lynn<br>
                </td>

                <td align="right"><br><br>
                        <input type="submit" id="start_chat" name="start_chat" <?php if ((round($client->balance))<=0) { echo 'disabled="disabled"'; } ?> value="<?php echo Yii::t('lang', 'Chat_Now'); ?> >>" />
                </td>
            </tr>

        </tbody></table>
    <p><b><a href="<?php echo Yii::app()->params['site_domain']; ?>/chat/iesettings/"> <?php echo Yii::t('lang', 'Having_PROBLEMS'); ?>.</a></b></p>
    <p><b><a href="javascript:macusr()"><?php echo Yii::t('lang', 'Mac_users_here'); ?></a></b></p>
<?php echo CHtml::endForm(); ?>
<?php endif; ?>            
<script language="JavaScript">
	function macusr(){
    	alert('<?php echo Yii::t('lang', 'chatstart_msg_11'); ?>');
    }
    function testXHR() {
    	try {
    		tst = new window.XMLHttpRequest();
    		tst = null;
    	} catch (err) {
	    	el=document.getElementById("start_chat");
	    	el.disabled=true;
	    	alert('Your browser doesn\'t support native Ajax (XMLHtppRequest) object.Unfortunately chat can\'t be started.If you use IE 8, please turn on "Native XMLHTTP support" checkbox in Advanced Settings');
    	}
    }
    
    testXHR();
</script>

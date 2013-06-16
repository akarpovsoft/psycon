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
    <?php if(isset($no_one_readers_online)): ?>
        <br><center><h3><?php echo Yii::t('lang', 'Sorry_but_there_is_no_readers_currently_online'); ?></h3></center><br>
    <?php else: ?>
        <p align="left">
    <?php echo CHtml::beginForm(Yii::app()->params['http_addr'].'chat/client/chatRequest', 'post', array('onSubmit' => 'return check();')); ?>
        <input name="client_id" value="<?php echo $client->getId(); ?>" type="hidden">
        <?php if(!isset($main_group_reader)): ?>
            <?php echo (isset($avaliable)) ?  Yii::t('lang', 'chatstart_msg_6_1').' - "'.$avaliable.'"'.Yii::t('lang', 'is_online ').'!<br><br>' : Yii::t('lang', 'Your_favorite_reader_is_currently_unavaliable').'!<br><br>'; ?>
        <?php endif; ?>
        <?php echo Yii::t('lang', 'You_have'); ?> <b><?php echo round($client->balance); ?></b> <?php echo Yii::t('lang', 'chatstart_msg_9_3'); ?><br>
        <?php echo $client->name; ?>
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
                                    echo ($reader['rr_record_id'] == $favorite_reader->value) ? 'selected' : '' ?>
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
            </tbody>
        </table>
        <table border="0" width="100%">
            <tbody>
                <tr>
                    <td align="right"><br><br>
                            <input type="submit" id="start" name="start" <?php if ((round($client->balance))<=0) { echo 'disabled="disabled"'; } ?> value="<?php echo Yii::t('lang', 'Chat_Now'); ?> >>" />
                    </td>
                </tr>
            </tbody>
        </table>        
    <?php echo CHtml::endForm(); ?>
<?php endif; ?>
</p>
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


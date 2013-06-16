<body onload="pageInit()">	
    <div id="main" class="container">
        <div id="header">
            <table>
                <tr>
                    <td rowspan="2" class="banner"><?php echo Yii::t('lang', 'NS'); ?> <b><font color="red">(CHAT IN TEST MODE)</font></b></td>
                    <td class="freeTimePadding"><?php echo Yii::t('lang', 'Free_time'); ?>:</td><td id='L_chatTime'><?php echo Yii::t('lang', 'Time_Remaining'); ?>:</td>
                </tr>
                <tr>
                    <td><input readonly="readonly" type="text" name="freeTime"  id="freeTime" maxlength="7" size="6"/></td>
                    <td><input readonly="readonly" type="text" name="chatTime" id="chatTime" maxlength="7" size="6"/></td>
                </tr>
            </table>
        </div>
		<div id="waiting_end_session" class="waitingEndSessionClient">
				<span>
					<b>Session ending</b><br>
				<img src="<?php echo ChatHelper::baseUrl();?>images/loading_21_21.gif"
				</span>
			</div>
        <div id="chatlog" class="chatlog">
        </div>
		<table class="infoTable" cellpadding="0" cellspacing="5">
			<tr>
				<td><?php echo Yii::t('lang', 'common_chat_msg_2'); ?>:</td>
				<td class="boldtext"><?php echo $userName;?></td>
			</tr>
			<tr>
				<td><?php echo Yii::t('lang', 'Topic'); ?>:</td>
				<td class="boldtext"><?php echo $topic;?></td>
			</tr>
								<tr>
				<td><?php echo Yii::t('lang', 'Gender'); ?>:</td>
				<td class="boldtext"><?php echo $gender;?></td>
			</tr>
								<tr>
				<td><?php echo Yii::t('lang', 'Username'); ?>:</td>
				<td class="boldtext"><?php echo $clientNick;?></td>
			</tr>
								<tr>
				<td><?php echo Yii::t('lang', 'DOB'); ?>:</td>
				<td class="boldtext"><?php echo $dob;?></td>
			</tr>
								<tr>
				<td><?php echo Yii::t('lang', 'Location'); ?>:</td>
				<td class="boldtext"><?php echo $location;?></td>
			</tr>
			<tr>
				<td><?php echo Yii::t('lang', 'Sign_up_date_short'); ?>:</td>
				<td class="boldtext"><?php echo $signUpDate;?></td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="button" id="bantc" name="bantc" value="<?php echo Yii::t('lang', 'Ban_this_client'); ?>" onclick="Chat.userBan()"/>
					<input type="button" id="persban" name="persban" value="<?php echo Yii::t('lang', 'Personal_Ban'); ?>" onclick="Chat.personalBan()"/>
				</td>
			</tr>
			<tr>
				<td class="endsessbtn">
            		<input type="button" id="endsession" name="endsession" value="<?php echo Yii::t('lang', 'End_session'); ?>" onclick="Chat.endSession()"/>
				</td>
				<td>
				</td>
			</tr>
		</table>
        <div id="footer">
			<span class="typing" style="width: 100%; color: red;"></span><br>
            <textarea cols="" rows="" type="text" name="message" onkeydown="if(!Chat.typingInterval && !Chat.typing && event.keyCode != 13) { Chat.typingInterval = setTimeout('Chat.sendTypingMsg()', 3000); } if(event.keyCode == 13){ return false;}" id="message" class="inputField"></textarea>
            <input type="button" name="send" value="<?php echo Yii::t('lang', 'Send'); ?>" class="sendButton" onclick="Chat.sendMessage($('#message').val());"/><br /><br />
            <table cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <input type="button" id="alt" name="alt" value="<?php echo Yii::t('lang', 'Add_lost_time'); ?>" onclick="Chat.addLostTime()"/>
                    </td>
                    <td>
                        <input type="button" id="pause" name="pause" value="<?php echo Yii::t('lang', 'Stand_By'); ?>" onclick="Chat.pause(this)"/>
                    </td>
                    <td align="center">
                        Sound<br />
                        <input type="radio" id="sound_switcher" name="sound_switcher" value="on" onclick="changeSoundMode('on')" <?php echo ($sound_mode == 'on') ? 'checked' : ''; ?>> On 
                        &nbsp; <input type="radio" id="sound_switcher" name="sound_switcher" value="off" onclick="changeSoundMode('off')" <?php echo ($sound_mode == 'off') ? 'checked' : ''; ?>> Off
                    </td>
                </tr>
            </table>
        </div>
    </div>
<?php
if($debug) 
	$display = 'block';
else 
	$display = 'none';
?>
	<div id="debug"  style="margin-top:1000px; margin-left: 10px; display: <?php echo $display;?>">
		<div style="width: 750px; ">
			<span style="width: 50px; "><?php echo Yii::t('lang', 'Debug log'); ?>:</span>
			<span style="float: right;"> <input type="button" onclick="javascript:$('#debug_log').html('');" value="Clear" style="width: 100px;"></span>
		</div>
		<div id="debug_log" class="chatlog">

		</div>
	</div>
        <?php if($sound_mode == 'on'): ?>
	<div id="sound" style="display: inline; position: absolute;float: left; left: -999;">
            <?php echo $sound; ?>
        </div>
	<?php endif;?>
</body>
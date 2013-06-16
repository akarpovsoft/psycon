<body onload="pageInit()">
    <center>
    <table width="300" border="0" cellspacing="0" cellpadding="0" align="center" style="height:100%" bgcolor="white">
<tr>    
<td>
    <div id="main" class="container">
        <div id="header">
            <table>
                <tr>
                    <td colspan="2" class="mobileBanner"><?php echo Yii::t('lang', 'NS'); ?> <b><font color="red">(CHAT IN TEST MODE)</font></b></td>
                </tr>
                <tr>
                    <td class="freeTimePadding"><?php echo Yii::t('lang', 'Time_Remaining'); ?>:</td>
                    <td><input readonly="readonly" type="text" name="timeRemaining" id="timeRemaining" maxlength="7" size="6"/></td>
                </tr>
                <tr>
                    <td><?php echo Yii::t('lang', 'Total_Time_Remaining'); ?>:</td>
                    <td><input readonly="readonly" type="text" name="totalTimeRem" id="totalTimeRem" maxlength="7" size="6"/></td>
                </tr>
                <tr>
                    <td>
                        <input type="button" id="endsession" name="endsession" value="<?php echo Yii::t('lang', 'End_session'); ?>" onclick="Chat.endSession()"/>
                    </td>
                </tr>
            </table>
        </div>
		<div id="waiting_end_session" class="mobileWaitingEndSession">
				<span>
					<b>Session ending</b><br>
				<img src="<?php echo ChatHelper::baseUrl();?>images/loading_21_21.gif"
				</span>
			</div>
        <div id="chatlog" class="mobileChatlog">
        </div>
        <div id="footer">
			<span class="typing" style="width: 100%; color: red;"></span><br>
            <textarea cols="" rows="" name="message" onkeydown="if(!Chat.typingInterval && !Chat.typing && event.keyCode != 13) Chat.typingInterval = setTimeout('Chat.sendTypingMsg()', 3000); if(event.keyCode == 13){ return false;}" id="message" class="mobileInputField"></textarea>
            <input type="button" name="send" value="<?php echo Yii::t('lang', 'Send'); ?>" class="mobileSendButton" onclick="Chat.sendMessage($('#message').val());"/><br /><br />
<?php 

if($timeToAdd>0) { ?>            
            <select id="selAddTime" name="selAddTime">
<?php 
if($timeToAdd>=5) { ?>            
                <option id="5">5 <?php echo Yii::t('lang', 'minutes'); ?></option>
<?php } if($timeToAdd>=10) { ?>                
                <option id="10">10 <?php echo Yii::t('lang', 'minutes'); ?></option>
<?php } if($timeToAdd>=15) { ?>                
                <option id="15">15 <?php echo Yii::t('lang', 'minutes'); ?></option>
<?php } if($timeToAdd>=30) { ?>                
                <option id="30">30 <?php echo Yii::t('lang', 'minutes'); ?></option>
<?php } if($timeToAdd>=45) { ?>                
                <option id="45">45 <?php echo Yii::t('lang', 'minutes'); ?></option>
<?php } if($timeToAdd>=60) { ?>                
                <option id="60">60 <?php echo Yii::t('lang', 'minutes'); ?></option>
<?php } ?>                
            </select>
            <input type="button" name="addtime" value="<?php echo Yii::t('lang', 'Add_time'); ?>" onclick="Chat.addTime(getElementById('selAddTime').options[getElementById('selAddTime').selectedIndex].id);"/>
<?php } ?>            
            <input type="button" name="bmt" value="<?php echo Yii::t('lang', 'Buy_more_time'); ?>" onclick="Chat.buyMoreTime()"/>
        </div>
    </div>
</td>
</tr>
</table>
    </center>
</body>
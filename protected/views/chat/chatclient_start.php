<body onload="pageInit()">	
    <div id="main" class="container">
        <div id="header">
            <table>
                <tr>
                    <td rowspan="2" class="banner"><?php echo Yii::t('lang', 'NS'); ?></td>
                    <td class="freeTimePadding"><?php echo Yii::t('lang', 'Time_Remaining'); ?>:</td><td><?php echo Yii::t('lang', 'Total_Time_Remaining'); ?>:</td>
                </tr>
                <tr>
                    <td><input readonly="readonly" type="text" name="timeRemaining" id="timeRemaining" maxlength="7" size="6"/></td>
                    <td><input readonly="readonly" type="text" name="totalTimeRem" id="totalTimeRem" maxlength="7" size="6"/></td>
                </tr>
            </table>
        </div>
		<div id="waiting_end_session" class="waitingEndSessionClient">
				<span>
					<b>Session ending</b><br>
				<img src="<?php echo Yii::app()->params['http_addr'];?>images/loading_21_21.gif"
				</span>
			</div>
        <div id="chatlog" class="chatlog">
			
        </div>
		<table class="infoTable" cellpadding="0" cellspacing="5">
			<tr>
				<td class="endsessbtn">
            		<input type="button" id="endsession" name="endsession" value="<?php echo Yii::t('lang', 'End_session'); ?>" onclick="Chat.endSession()"/>
				</td>
			</tr>
		</table>
        <div id="footer">
			<span class="typing" style="width: 100%; color: red;"></span><br>
            <textarea cols="" rows="" name="message" onkeydown="if(!Chat.typing) Chat.sendTypingMsg(); if(event.keyCode == 13){ return false;}" id="message" class="inputField"></textarea>
            <input type="button" name="send" value="<?php echo Yii::t('lang', 'Send'); ?>" class="sendButton" onclick="Chat.sendMessage($('#message').val());"/><br /><br />
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
<?php
if($debug) 
	$display = 'block';
else 
	$display = 'none';
?>
	<div id="debug"  style="margin-top:50px; margin-left: 10px; display: <?php echo $display;?>">
		<div style="width: 750px; ">
			<span style="width: 50px; "><?php echo Yii::t('lang', 'Debug log'); ?>:</span>
			<span style="float: right;"> <input type="button" onclick="javascript:$('#debug_log').html('');" value="Clear" style="width: 100px;"></span>
		</div>
		<div id="debug_log" class="chatlog">

		</div>
	</div>

</body>
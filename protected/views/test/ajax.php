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

            <input type="button" name="bmt" value="<?php echo Yii::t('lang', 'Buy_more_time'); ?>" onclick="Chat.buyMoreTime()"/><br>
            <input type="button" name="check" value="Check" onclick="Chat.check()"/><br>
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
<div class="chat_page">
<p>
<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="left">
            <h2>Psychic Contact Chat</h2>
        </td>
        <td align="right">
            <input type="image" id="endsession" src="<?php echo ChatHelper::baseUrl(); ?>images/end_session_button-trans.png" onClick="Chat.endSession();">
        </td>
    </tr>
</table>   
</p>

<div class="clear"></div>
  <div class="chat_block">
      
  <div id="chatlog" class="chatlog"></div>
  
  <input type="text" name="message" onkeydown="if(!Chat.typingInterval && !Chat.typing && event.keyCode != 13) Chat.typingInterval = setTimeout('Chat.sendTypingMsg()', 3000); if(event.keyCode == 13){ return false;}" id="message" class="textinput">
  <input type="image" src="<?php echo ChatHelper::baseUrl(); ?>images/send_button_silver-trans.png" name="send" alt="Start Chat" class="submitbutton" onclick="Chat.sendMessage($('#message').val());" />  
  
  <div class="time">
    	<p>
            <?php if($timeToAdd>0.2): ?>            
            <input type="image" src="<?php echo ChatHelper::baseUrl(); ?>images/add_time_button-trans.png" alt="Add Time" name="addtime" onclick="Chat.addTime(getElementById('selAddTime').options[getElementById('selAddTime').selectedIndex].id);">
            <select id="selAddTime" name="selAddTime">
            <?php if($timeToAdd<1) { ?>                                
                            <option id="<?php echo $timeToAdd; ?>"><?php echo $timeToAdd; ?> <?php echo Yii::t('lang', 'minutes'); ?></option>
            <?php } if($timeToAdd>=1) { ?>                            
                            <option id="1">1 <?php echo Yii::t('lang', 'minutes'); ?></option>
            <?php } if($timeToAdd>=2) { ?>            
                            <option id="2">2 <?php echo Yii::t('lang', 'minutes'); ?></option>
            <?php } if($timeToAdd>=3) { ?>            
                            <option id="3">3 <?php echo Yii::t('lang', 'minutes'); ?></option>
            <?php } if($timeToAdd>=4) { ?>            
                            <option id="4">4 <?php echo Yii::t('lang', 'minutes'); ?></option>
            <?php } if($timeToAdd>=5) { ?>            
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
            <?php endif; ?>
            
            <input name="bmt" type="image" src="<?php echo ChatHelper::baseUrl(); ?>images/buy_more_time_button-trans.png" alt="Buy More Time" onclick="Chat.buyMoreTime()">            
      	</p>
        <p>        	
            <?php echo Yii::t('lang', 'Time_Remaining'); ?><input readonly="readonly" type="text" name="timeRemaining" id="timeRemaining" maxlength="7" size="6"/> <?php echo Yii::t('lang', 'Total_Time_Remaining'); ?> <input readonly="readonly" type="text" name="totalTimeRem" id="totalTimeRem" maxlength="7" size="6" />
   	</p>
        <div class="clear"></div>
        <div id="waiting_end_session" class="waitingEndSessionClient">
                <span>
                <b>Session ending</b><br>
                <img src="<?php echo ChatHelper::baseUrl();?>images/loading_21_21.gif"
                </span>
        </div>
    </div>  
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

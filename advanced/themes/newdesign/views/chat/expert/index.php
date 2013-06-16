<div class="chat_page">
<p><a href=""><img src="<?php echo ChatHelper::baseUrl(); ?>images/end_session_button-trans.png" alt="End Session" /></a></p>
<h2>Psychic Contact Chat</h2>
<div class="clear"></div>
  <div class="chat_block">
  <div id="chatlog" class="chatlog"></div>
  <input type="text" name="message" onkeydown="if(!Chat.typingInterval && !Chat.typing && event.keyCode != 13) Chat.typingInterval = setTimeout('Chat.sendTypingMsg()', 3000); if(event.keyCode == 13){ return false;}" id="message" class="textinput">
  <input type="image" src="<?php echo ChatHelper::baseUrl(); ?>images/start_chat_button_silver-trans.png" name="send" alt="Start Chat" class="submitbutton" onclick="Chat.sendMessage($('#message').val());" />  
  <div class="time">
    	<p>
            <input type="image" src="<?php echo ChatHelper::baseUrl(); ?>images/stand_by_button-trans.png" id="pause" name="pause" onclick="Chat.pause(this);" />  
            <input type="image" src="<?php echo ChatHelper::baseUrl(); ?>images/add_lost_time_button-trans.png" id="alt" name="alt" onclick="Chat.addLostTime();" />  
      	</p>
        <p>
        	Free Time <input readonly="readonly" type="text" name="freeTime"  id="freeTime" maxlength="7" size="6" /> 
                Total Time Remaining <input readonly="readonly" type="text" name="chatTime" id="chatTime" maxlength="7" size="6" />
   	</p>
  <div class="clear"></div>
  </div> 
  <div id="waiting_end_session" class="waitingEndSessionClient">
            <span>
            <b>Session ending</b><br>
            <img src="<?php echo ChatHelper::baseUrl();?>images/loading_21_21.gif"
            </span>
  </div>
    <div class="chat_details">
    	<p><span class="details"><?php echo $clientNick;?></span><?php echo Yii::t('lang', 'common_chat_msg_2'); ?>: </p><div class="clear"></div>
        <p><span class="details"><?php echo $topic;?></span><?php echo Yii::t('lang', 'Topic'); ?>: </p><div class="clear"></div>
    	<p><span class="details"><?php echo $gender;?></span><?php echo Yii::t('lang', 'Gender'); ?>: </p><div class="clear"></div>
    	<p><span class="details"><?php echo $userName;?></span><?php echo Yii::t('lang', 'Username'); ?>: </p><div class="clear"></div>
    	<p><span class="details"><?php echo $dob;?></span><?php echo Yii::t('lang', 'DOB'); ?>: </p><div class="clear"></div>
    	<p><span class="details"><?php echo $location;?></span><?php echo Yii::t('lang', 'Location'); ?>: </p><div class="clear"></div>
    	<p><span class="details"><?php echo $signUpDate;?></span><?php echo Yii::t('lang', 'Sign_up_date_short'); ?>: </p><div class="clear"></div>
        
        <p>
            <input type="image" src="<?php echo ChatHelper::baseUrl(); ?>images/ban_this_client_button-trans.png" id="bantc" name="bantc" onclick="Chat.userBan()" />
            <input type="image" src="<?php echo ChatHelper::baseUrl(); ?>images/personal_ban_button-trans.png" id="persban" name="persban" onclick="Chat.personalBan()" />
      	</p>
        <div class="clear"></div>
    </div> 
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
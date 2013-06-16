<script type="text/javascript">
	var timeout = 0;
	var errorsCounter = 0;
	var runAjax = function(settings)
	{
		settings.type		= 'GET';
		settings.dataType	= 'json';
		settings.cache		= false;
		settings.timeout	= 100000;
		$.ajax(settings);
	};

	function getChatStatus()
	{
		timeout++;

		if(timeout >= 20)
			top.window.location.href = "<?php echo Yii::app()->params['http_addr'].'chat/waitingFail'; ?>";

		runAjax({
			data    : {
				'request_id':  <?php echo $request_id; ?>,
                                'reader_id': <?php echo $reader_id; ?>                                
			},
			url     : '<?php echo Yii::app()->params['http_addr']; ?>chat/chatStartChecking',
			success : function(json){
                                if(json.chat == 'offline')
                                        top.window.location.href = "<?php echo Yii::app()->params['http_addr'] ?>chat/waitingFail";
				else if(json.chat == 'start')
					top.window.location.href = "<?php echo Yii::app()->params['http_addr'] ?>chat/ChatClient?sessionKey="+json.sessionKey;
			},
			error	: function(XMLHttpRequest, errorType)
			{
				errorsCounter++;
				if(errorsCounter == 3){
					alert('Seems connection is lost or server is unavailable.Please check internet connection');
    				errorsCounter = 0;
				}
			}
		});
	}

	var mytimer = setInterval("getChatStatus()",3000) ;
        
</script>
<br><br><br>
<span class="ppbigtext"><br>
    <b><?php echo Yii::t('lang', 'Contacting_Reader'); ?><IMG src="<?php echo Yii::app()->params['http_addr']; ?>images/period_ani.gif" border="0" width="20" height="12" align="baseline"></b></span><br><br>
<span class=TextMedium><?php echo Yii::t('lang', 'chatstart_process_3'); ?><br><br>
    <a target=_top href="<?php echo Yii::app()->params['http_addr']; ?>chat/removeRequest" class=LinkMedium><?php echo Yii::t('lang', 'chatstart_process_4_1'); ?></a> <?php echo Yii::t('lang', 'chatstart_process_4_2'); ?><br></span>
<br/><br/>
<a target=_top href="javascript:goHelpNC();" class=LinkMedium><?php echo Yii::t('lang', 'chatroom_not_connecting'); ?>?</a>
<br><br><br><br><br><br><br>
<div id="status"></div>
<br><br><br><br><br><br><br><br><br><br>

  <br><br><center><a href="<?php echo Yii::app()->params['http_addr']; ?>users/mainmenu"><?php echo Yii::t('lang', 'Your_account'); ?></a></center></td>

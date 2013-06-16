<?php if(isset($waiting_fail)): ?>
<script type="text/javascript">

	var timer = setInterval("redirect()",30000) ;

	function redirect() {
		window.document.location.href = "<?php echo Yii::app()->params['http_addr'] ?>users/mainmenu";
	}
</script>
<div style="text-align: center;">
         <?php echo Yii::t('lang', 'It_appears_you_have_have'); ?> 
         <a href="<?php echo Yii::app()->params['http_addr'] ?>users/mainmenu"><?php echo Yii::t('lang', 'here'); ?></a> <br />
         <?php echo Yii::t('lang', 'NOTE_If_you_were_not_able'); ?>
</div>
<?php else: ?>
<script type="text/javascript">
	var timeout = 0;

	var runAjax = function(settings)
	{
		settings.type		= 'GET';
		settings.dataType	= 'json';
		settings.cache		= false;
		settings.timeout	= 10000;
		$.ajax(settings);
	};

	function getChatStatus()
	{
		timeout++;

		if(timeout >= 20)
			top.window.location.href = "<?php echo Yii::app()->params['http_addr'].'chat/chatstart?waiting_fail=1&reader_id='.$reader_id.'&client_id='.$client_id; ?>";

		jQuery.ajaxSetup({
			'xhr':function() {
				return window.ActiveXObject ? new ActiveXObject('Microsoft.XMLHTTP') : new XMLHttpRequest();
			}
		});

		runAjax({
			data    : {
				'reader_id'	:  <?php echo $reader_id; ?>,
				'client_id'	:  <?php echo $client_id; ?>,
				'subject'	: '<?php echo urlencode($subject); ?>',
				'request_id':  <?php echo $request_id; ?>
			},
			url     : '<?php echo Yii::app()->params['http_addr']; ?>chat/chatStartChecking',
			success : function(json){
				if(json.chat == 'start')
					top.window.location.href = "<?php echo Yii::app()->params['http_addr'] ?>chat/ChatClient?sessionKey="+json.sessionKey;
			},
			error	: function(XMLHttpRequest, errorType)
			{
				try {
					handleAjaxError({ place :"ChatStartChecking Error", error :errorType, responseText : XMLHttpRequest.responseText});
				} catch (err) {
					handleAjaxError({ place :"ChatStartChecking Error"});
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
    <a target=_top href="<?php echo Yii::app()->params['http_addr']; ?>chat/chatstart?waiting_fail=1<?php echo '&reader_id='.$reader_id.'&client_id='.$client_id; ?>" class=LinkMedium><?php echo Yii::t('lang', 'chatstart_process_4_1'); ?></a> <?php echo Yii::t('lang', 'chatstart_process_4_2'); ?><br></span>
<br/><br/>
<a target=_top href="javascript:goHelpNC();" class=LinkMedium><?php echo Yii::t('lang', 'chatroom_not_connecting'); ?>?</a>
<br><br><br><br><br><br><br>
<div id="status"></div>
<br><br><br><br><br><br><br><br><br><br>

  <br><br><center><a href="<?php echo Yii::app()->params['http_addr']; ?>users/mainmenu"><?php echo Yii::t('lang', 'Your_account'); ?></a></center></td>
<?php endif; ?>

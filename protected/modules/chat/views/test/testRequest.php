<script type="text/javascript" src="<?php echo Yii::app()->params['http_addr']; ?>js/jquery-1.5.2.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['http_addr']; ?>/js/jquery.json-2.2.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['http_addr']; ?>/js/util.js"></script>
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
		jQuery.ajaxSetup({
			'xhr':function() {
				return window.ActiveXObject ? new ActiveXObject('Microsoft.XMLHTTP') : new XMLHttpRequest();
			}
		});

		runAjax({
			data    : {
				'request_id':  <?php echo $request_id; ?>
			},
			url     : '<?php echo Yii::app()->params['http_addr']; ?>chat/test/chatStartChecking',
			success : function(json){
				if(json.chat == 'start')
					window.location.href = "<?php echo Yii::app()->params['http_addr'] ?>chat/test/ChatClient?sessionKey="+json.sessionKey;
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
<center>
    <br><br><br><br><h1>WAITING FOR READER'S ACCEPT</h1>
</center>
<script type="text/javascript" src="<?php echo Yii::app()->params['http_addr']; ?>js/jquery-1.4.4-tuned.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['http_addr']; ?>/js/jquery.json-2.2.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['http_addr']; ?>/js/util.js"></script>
<SCRIPT type='text/javascript' language=JavaScript src="<?php echo Yii::app()->params['http_addr']; ?>/js/common.js" ></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">

	var timeoutHandler = setTimeout("checkForUpdates()", 3000);

	var runAjax = function(settings)
	{
		settings.type		= 'POST';
		settings.dataType	= 'json';
		settings.cache		= false;
		settings.timeout	= 10000;
		$.ajax(settings);
	};

function checkForUpdates()
	{
		runAjax({
			url     : "<?php echo Yii::app()->params['http_addr']; ?>test/testCheck",
			success : function(json) {
				checkForUpdatesOnSuccess(json);
				timeoutHandler = setTimeout("checkForUpdates()", 3000);
			},
			error	: function(XMLHttpRequest, errorType)
			{
				try {
					handleAjaxError({place :'Connection error.Please reload monitor(checkNewClient)', error :errorType, responseText :XMLHttpRequest.responseText});
				} catch (err) {
					handleAjaxError({place :'Connection error.Please reload monitor(checkNewClient)'});
				}

				timeoutHandler = setTimeout("checkForUpdates()", 3000);
			}
		});
    }

var checkForUpdatesOnSuccess = function(json)
{
        if(json != null)
        {
                if(json.update == 'update')
                {
                        window.location.href = "<?php echo Yii::app()->params['http_addr']; ?>chat/startChatting?client_id=9615&request_id="+json.request_id+"&reader_id=19310";
                }
        }
}
</SCRIPT>

<center>
    <br><br><br><br><h1>WAITING FOR CLIENT'S REQUEST</h1>
</center>
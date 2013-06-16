<html>
<head>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['http_addr']; ?>css/style.css" />
<script type="text/javascript" src="<?php echo Yii::app()->params['http_addr']; ?>js/jquery-1.5.2.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['http_addr']; ?>js/jquery.json-2.2.js"></script>
<SCRIPT LANGUAGE="JavaScript">

	var timeoutHandler = setTimeout("checkForUpdates()", 3000);
	var errorsCounter = 0;
        
	var runAjax = function(settings) 
	{
		settings.type		= 'POST';
		settings.dataType	= 'json';
		settings.cache		= false;
		settings.timeout	= 100000;
		$.ajax(settings);
	};


    function windowSize(){
        window.resizeTo(600, 400);
    }
    
    function ReLoad(){
	//alert('ok');
	    clearTimeout(timeoutHandler);
            window.location.href="<?php echo Yii::app()->params['http_addr']; ?>chat/readerMonitor?rld=1";
    }

    function OpenChatWindow(client_id, reader_id, session_id, subject)
    {            
    	
        <?php if($active->chat_type == 7): ?>
            window.location.href = "<?php echo Yii::app()->params['http_addr']; ?>chat/startChatting?request_id="+session_id;
        <?php else: ?>
            var date = new Date();
            var unique = date.getTime();
            url = "<?php echo Yii::app()->params['site_domain']; ?>/chat/new_chat/chatreader_start.php?client_id="+client_id+"&session_id="+session_id+"&reader="+reader_id+"&unique="+unique+"&subject="+subject;
            newwin = window.open(url, "chatwindow", "scrollbars=yes,menubar=no,resizable=1,location=no,width=520,height=670,left=300,top=30");
            newwin.focus();
        <?php endif; ?>
    }

    function debugOpen(){
        if(document.getElementById('debug_log').style.display == 'none')
            document.getElementById('debug_log').style.display = 'block';
        else
            document.getElementById('debug_log').style.display = 'none';
    }

    function addDebugInfo(type, data)
    {
    	output = $.toJSON(data);	
    	$('#debug_log').append("<br>type: "+type+"; data: " +output);
    }
    

    function checkForUpdates()
	{
		runAjax({			
			data    : {
				'client'		: <?php echo (!empty($active->client_id)) ? $active->client_id : 0; ?>,
				'current_status': '<?php echo $type.$mins; ?>',
				'reader_id'		:  <?php echo $reader_id; ?>
			}, 
			url     : "<?php echo Yii::app()->params['http_addr']; ?>chat/checkNewClient",			
			success : function(json) { 
				checkForUpdatesOnSuccess(json);
				timeoutHandler = setTimeout("checkForUpdates()", 3000);
                                errorsCounter = 0;
			},
			error	: function(XMLHttpRequest, errorType)
			{
				errorsCounter++;
				try {
					addDebugInfo('error', "Connection error(checkNewClient)\r\n error :"+errorType+"\r\n responseText :"+XMLHttpRequest.responseText);
				} catch (err) {
					addDebugInfo('error', "Connection error(checkNewClient)");
				}
				if(errorsCounter == 3){
					alert('Connection lost.Either internet connection is lost or server is unavailable');
    				errorsCounter = 0;
				}
				timeoutHandler = setTimeout("checkForUpdates()", 3000);
			} 
		});
    }

	var checkForUpdatesOnSuccess = function(json)
	{
		if(json != null)
		{
                    //alert(json.rStatus+" "+json.cStatus);
			if(json.update == 'update')
                        {
				ReLoad();
				return;
			}
		}
	}

    function changeReaderStatus(status, click)
	{
            if(typeof click != 'undefined'){
                document.getElementById('spinner').innerHTML = '<img src="<?php echo Yii::app()->params['http_addr']; ?>images/loading_21_21.gif" align="right">';
            }

		runAjax({
			data    : {
				'status' : status
			},
			url     : "<?php echo Yii::app()->params['http_addr']; ?>chat/readerStatusChange",
			success : function () {},
			error : function(XMLHttpRequest, errorType)
			{
				try {
					addDebugInfo('error', "Connection error(readerStatusChange)\r\n error :"+errorType+"\r\n responseText :"+XMLHttpRequest.responseText);
				} catch (err) {
					addDebugInfo('error', "Connection error(readerStatusChange)");
				}
			}
		});
    }

<?php if($type == 'break'): ?>
    var break_mins = <?php echo $mins - 1; ?>;
    function changeBreakStatus(){
        if(break_mins <= 0){
            changeReaderStatus('online');
            return;
        }
        changeReaderStatus('break'+break_mins);
        break_mins--;
    }

setInterval("changeBreakStatus()", 60000);
<?php endif; ?>
</SCRIPT>
</head>
<body onload="windowSize()">
<?php $this->widget('Requesters'); ?>
<div id="sound">
<?php         //@TODO : make sound as widget
   if(is_object($active))
      echo "<OBJECT classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" WIDTH=\"1\" HEIGHT=\"1\" id=\"ring\" ALIGN=\"\"> <PARAM NAME=movie VALUE=\"".Yii::app()->params['site_domain']."/chat/ring.swf\"> <PARAM NAME=quality VALUE=high> <PARAM NAME=bgcolor VALUE=#FFFFFF> <EMBED src=\"".Yii::app()->params['site_domain']."/chat/ring.swf\" quality=high bgcolor=#FFFFFF  WIDTH=\"1\" HEIGHT=\"1\" NAME=\"ring\" ALIGN=\"\" TYPE=\"application/x-shockwave-flash\" PLUGINSPAGE=\"http://www.macromedia.com/go/getflashplayer\"></EMBED></OBJECT>";
//   if($active->type == 'Administrator')
//      echo "<OBJECT classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" WIDTH=\"1\" HEIGHT=\"1\" id=\"admin_ring\" ALIGN=\"\"> <PARAM NAME=movie VALUE=\"".Yii::app()->params['site_domain']."/chat/sounds/ringin.swf\"> <PARAM NAME=quality VALUE=high> <PARAM NAME=bgcolor VALUE=#FFFFFF> <EMBED src=\"".Yii::app()->params['site_domain']."/chat/sounds/ringin.swf\" quality=high bgcolor=#FFFFFF  WIDTH=\"1\" HEIGHT=\"1\" NAME=\"ring\" ALIGN=\"\" TYPE=\"application/x-shockwave-flash\" PLUGINSPAGE=\"http://www.macromedia.com/go/getflashplayer\"></EMBED></OBJECT>";
?>
</div>
<div id="spinner" align="right" style="margin-right: 30px;"></div><br>
<?php $this->widget('ReaderBreak', array('status' => $type, 'minutes' => $mins)); ?><br><br>

<a href="javascript:ReLoad();"><?php echo Yii::t('lang', 'Refresh_the_monitor_window'); ?></a>

<?php $this->widget('PendingEmailAndNrr'); ?><br>

<a href="<?php echo Yii::app()->params['http_addr']; ?>chat/monitorLogout"><?php echo Yii::t('lang', 'Log_Off'); ?></a><br>

<a href="javascript:debugOpen()" style="color:#ffffff">debug window</a><br>
<textarea id="debug_log" cols="50" rows="10" style="display: none;"></textarea>
</body>
</html>
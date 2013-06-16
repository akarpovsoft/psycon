<?php if($type == 'logout'): ?>
<?php echo Yii::t('lang', 'monitor_logoff_msg_1'); ?>
<?php else: ?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['http_addr']; ?>/css/style.css" />
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


    function ReLoad(sound){
	//alert('ok');
		clearTimeout(timeoutHandler);
		
        if(sound == 1)
            window.location.href="<?php echo Yii::app()->params['http_addr']; ?>chat/readerMonitor?sound=1";
        else if(sound == 2)
            window.location.href="<?php echo Yii::app()->params['http_addr']; ?>chat/readerMonitor?sound=2";
        else
            window.location.href="<?php echo Yii::app()->params['http_addr']; ?>chat/readerMonitor";
    }

    function OpenChatWindow(client_id, reader_id, session_id, subject)
    {
        <?php if($chatType == 7): ?>
            url = "<?php echo Yii::app()->params['http_addr']; ?>chat/startChatting?client_id="+client_id+"&request_id="+session_id+"&reader_id="+reader_id;
        <?php else: ?>
            var date = new Date();
            var unique = date.getTime();
            url = "<?php echo Yii::app()->params['site_domain']; ?>/chat/new_chat/chatreader_start.php?client_id="+client_id+"&session_id="+session_id+"&reader="+reader_id+"&unique="+unique+"&subject="+subject;
        <?php endif; ?>
        newwin = window.open(url, "chatwindow", "scrollbars=yes,menubar=no,resizable=1,location=no,width=1050,height=500,left=200,top=200");
        newwin.focus() ;
    }

    function checkForUpdates()
	{
        var clients = [ <?php echo implode(',', $active); ?> ];

		runAjax({			
			data    : {
				'clients'		: clients,
				'current_status': '<?php echo $type.$mins; ?>',
				'reader_id'		:  <?php echo $reader_id; ?>
			}, 
			url     : "<?php echo Yii::app()->params['http_addr']; ?>chat/checkNewClient",			
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
			if(json.update == 'update') {
				if(json.type == 'insert'){
					ReLoad(1);
					return;
				}
				if(json.type == 'admin'){
					ReLoad(2);
					return;
				}
				else {
					ReLoad();
					return;
				}
			}
			else
			{
				changeReaderStatus('<?php echo $type.$mins; ?>');				
			}
		}
	}

    function changeReaderStatus(status)
	{
            if(status == 'break5click') {
                status = 'break5';
                document.getElementById('break5spin').innerHTML = '<img src="<?php echo Yii::app()->params['http_addr']; ?>images/loading_21_21.gif" align="right">';
            }
            if(status == 'break10click') {
                status = 'break10';
                document.getElementById('break10spin').innerHTML = '<img src="<?php echo Yii::app()->params['http_addr']; ?>images/loading_21_21.gif" align="right">';
            }
            if(status == 'onlineClick') {
                status = 'online';
                document.getElementById('onlineSpin').innerHTML = '<img src="<?php echo Yii::app()->params['http_addr']; ?>images/loading_21_21.gif">';
            }

            runAjax({
			data    : {
				'status' : status,
				'reader_id' : <?php echo $reader_id; ?>
			},
			url     : "<?php echo Yii::app()->params['http_addr']; ?>chat/readerStatusChange",
			success : function () {},
			error : function(XMLHttpRequest, errorType)
			{
				try {
					handleAjaxError('Connection error.Please reload monitor(readerStatusChange)', errorType, XMLHttpRequest.responseText);
				}
				catch (err) {
					handleAjaxError('Connection error.Please reload monitor(readerStatusChange)', '', '');
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
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$req,
    'template'=>'{items}',
    'cssFile' => false,
    'columns' => array(
        array(
            'class' => 'CLinkColumn',
            'urlExpression' => '"javascript:OpenChatWindow(".$data->client_id.",".$data->reader_id.",".$data->rr_record_id.",\"".htmlspecialchars(urlencode($data->subject))."\")"',
            'labelExpression' => '$data->credit_card->firstname',
            'label' => 'Client'
        ),
        array(
            'type' => 'raw',
            'htmlOptions' => array('align' => 'right'),
            'name' => 'Subject',
            'value' => '$data->subject'
        )
      ),
    'emptyText' => Yii::t('lang', 'No_Pending_Requests')
    ));
?>
<div id="sound">
    <?php echo $sound; ?>
</div>
<div id="spinner">

</div>
<br><br>
<?php if($type == 'break'): ?>
<?php echo Yii::t('lang', 'chatmonitor_break'); ?> (<font id="mins"><?php echo $mins; ?></font> <?php echo Yii::t('lang', 'mins'); ?>).
<a href="javascript:changeReaderStatus('onlineClick')"><?php echo Yii::t('lang', 'Go_online'); ?>!</a>&nbsp;&nbsp;<font id="onlineSpin"></font>
<?php endif; ?>
<?php if($type == 'busy'): ?>
<?php echo Yii::t('lang', 'The_Reader_is_Busy'); ?>
<?php endif; ?>
<?php if($type == 'online'): ?>
<script>
    changeReaderStatus('online');
</script>
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%">
  <tr>
    <td width="30%">
        <table>
            <tr>
                <td>
                    <a href="javascript:changeReaderStatus('break5click')"><?php echo Yii::t('lang', 'Take_5_mins_break'); ?></a>
                </td>
                <td>
                    <font id="break5spin"></font>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="javascript:changeReaderStatus('break10click')"><?php echo Yii::t('lang', 'Take_10_mins_break'); ?></a>
                </td>
                <td>
                    <font id="break10spin"></font>
                </td>
            </tr>
        </table>
    </td>
  </tr>
</table>
<?php endif; ?>
<br><br><a href="javascript:ReLoad();"><?php echo Yii::t('lang', 'Refresh_the_monitor_window'); ?></a>
<?php if (($emailPendings > 0) || ($nrr > 0)) { echo '<br>-------------------------------------------<br>'; } ?>
<?php if($emailPendings > 0): ?>
<br><font color="red"><b><?php echo Yii::t('lang', 'You_have'); ?> <?php echo $emailPendings; ?> <?php echo Yii::t('lang', 'pending'); ?>  <a href="#" target="_top"><?php echo Yii::t('lang', 'Email_readings'); ?></a> </b></font><br>
<?php endif; ?>
<?php if($nrr > 0): ?>
<br><font color="red"><b><?php echo Yii::t('lang', 'You_have'); ?> <?php echo $nrr; ?> <?php echo Yii::t('lang', 'new_litle'); ?> <a href="#" target="_top"> <?php echo Yii::t('lang', 'NRR_Request_Forms'); ?> </a></b></font><br>
<?php endif; ?>
<?php if (($emailPendings > 0) || ($nrr > 0)) { echo '<br>-------------------------------------------<br>'; } ?>

<a href="<?php echo Yii::app()->params['http_addr']; ?>chat/readerMonitor?logout=1"><?php echo Yii::t('lang', 'Log_Off'); ?></a>
<?php endif; ?>
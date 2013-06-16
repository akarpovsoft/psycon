<center>
<a href="<?php echo Yii::app()->params['http_addr']; ?>">Home</a> |
<a href="<?php echo Yii::app()->params['http_addr']; ?>users/mainmenu">Your account</a><br>
</center>
<div style="margin-left: 25px" align="left">
<?php echo Yii::t('lang', 'History'); ?>
</div>
<hr>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
        <td align="center">
            <SPAN class=TextMedium>
                <?php echo Yii::t('lang', 'chat_php_text1'); ?>
                <br>
                <span style="color: rgb(136, 0, 0);">
                    <?php echo Yii::t('lang', 'Please_note_that_if_you'); ?>
                    <br>
                    <?php echo Yii::t('lang', 'Please_use_FF_or_Chrome'); ?>
                </span>
                <BR>
                <BR>
            </SPAN>
        </td>
    </tr>
</table>
<script src="<?php echo Yii::app()->params['http_addr']; ?>/js/prototype.js" type="text/javascript"></script>
<SCRIPT LANGUAGE="JavaScript">
    <!--
    function OpenChatWindow(fileopen,reader_id)
    {
        w2 = window.open("<?php echo Yii::app()->params['http_addr']; ?>users/openLog?session_id="+fileopen+"&reader_id="+reader_id,"Logs","width=500,height=500,toolbar=0,location=0,status=0,menubar=0,scrollbars=1,resizable=1");
        window.w2.focus();
    }

    function deleteLog(session)
    {
        pars = "session_id="+session;
        var url = "<?php echo Yii::app()->params['http_addr'].'users/chathistoryDel' ?>";
        alert(url);
        var myAjax = new Ajax.Request(
        url,
        {
            method: 'get',
            parameters: pars,
            onComplete: function(oResponse) {
                eval( "var p=" + oResponse.responseText );
                if (p.status == 'ok') {
                    $('del_log').innerHTML = '<font color="grey">Deleted</font>';
                } else {
                    if (p.error) {
                        alert(p.error);
                    } else {
                        alert('Error request!');
                    }
                }
            },
            onFailure: function(p) {
                alert('Operation is failed!');
            }
        }
    );
    }
    //-->
</SCRIPT>
<?php echo CHtml::beginForm(); ?>
<table border="0" cellpadding="0" cellspacing="1">
    <tbody>
        <tr>
            <td width="15">
                    <select size="1" name="month">
                        <option value=""><?php echo Yii::t('lang', 'All_months'); ?> </option>
                        <?php
                        $mon = PsyConstants::getName(PsyConstants::MONTH);
                        for($i=1;$i<=12;$i++):
                        ?>
                        <option value="<?php echo $i ?>" <?php echo ((isset($_POST['month']))&&($i == $_POST['month'])) ? 'selected' : '' ?>>
                            <?php echo Yii::t('lang', $mon[$i]); ?>
                        </option>
                        <?php endfor; ?>
                    </select>
            </td>
            <td>
                <select size="1" name="period">
                    <option value=""><?php echo Yii::t('lang', 'AllstartBig'); ?> </option>
                    <option value="1-15" <?php echo ((isset($_POST['period'])) && ($_POST['period'] == '1-15')) ? 'selected' : '' ?>>1-15</option>
                    <option value="16-31" <?php echo ((isset($_POST['period'])) && ($_POST['period'] == '16-31')) ? 'selected' : '' ?>>16-31</option>
                </select>
            </td>
            <?php for($i = 0;$i<3;$i++): ?>
            <td width="60">
                <?php echo date('Y')-$i; ?> <input style="vertical-align: bottom;" value="<?php echo date('Y')-$i; ?>" <?php echo ((isset($_POST['filter_year'])) && ($_POST['filter_year'] == date('Y')-$i)) ? 'checked="checked"' : '' ?> name="filter_year" type="radio">
            </td>
            <?php endfor; ?> 
            <td>
                <input type="submit" name="filter" value="<?php echo Yii::t('lang', 'Search'); ?>">
            </td>
        </tr>
    </tbody>
</table>
<?php echo CHtml::endForm(); ?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider'=>$data,
            'columns' => array(
                    array(
                            'name' => 'Date',
                            'value' => '$data->Date'
                    ),
                    array(
                            'name' => Yii::t('lang', 'Subject'),
                            'type' => 'raw',
                            'value' => '"<img src=\"../images/operatoricon_small.gif\" align=\"absmiddle\" border=\"0\" width=\"20\" height=\"17\">
                             <a href=\"Javascript:OpenChatWindow(".$data->Session_id.",".$data->Reader_id.")\">".$data->Subject."</a>"'
                    ),
                    array(
                            'name' => 'Session #',
                            'type' => 'raw',
                            'value' => '"<a href=\"".Yii::app()->params[http_addr]."users/sessionInfo?session_id=".$data->Session_id."\">".$data->Session_id."</a>"',
                    ),
                    array(
                            'name' => Yii::t('lang', 'Reader_name'),
                            'value' => '$data->Reader_name'
                    ),
                    array(
                            'name' => Yii::t('lang', 'Client_name'),
                            'value' => '$data->Client_name'
                    ),
                    array(
                            'name' => Yii::t('lang', 'Duration'),
                            'value' => 'round($data->Duration/60, 3)',
                    ),
                    array(
                            'name' => Yii::t('lang', 'Paid_time'),
                            'value' => '$data->Paid_time'
                    ),
                    array(
                            'name' => Yii::t('lang', 'Free_time'),
                            'value' => '$data->Free_time'
                    ),
                    array(
                            'name' => Yii::t('lang', 'Due_to_reader'),
                            'value' => '$data->Due_to_reader'
                    ),
                    array(
                            'name' => Yii::t('lang', 'Rate'),
                            'value' => '$data->Rate." (USD)"'
                    )
            ),
    ));

?>
<p align="left">&nbsp; &nbsp; &nbsp; &nbsp;<?php echo Yii::t('lang', 'Total_chat_duration'); ?> : <b><?php echo floor($total/60); ?><?php echo Yii::t('lang', 'min'); ?>. <?php echo floor($total%60); ?><?php echo Yii::t('lang', 'sec'); ?>.</b></p>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <td class="ppheading" width="100%"><?php echo Yii::t('lang', 'Our_clients'); ?> </td>
        </tr>
        <tr>
            <td><img src="<?php echo Yii::app()->params['http_addr']; ?>images/pixel.gif" width="2" height="2"></td>
        </tr>
    </tbody>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <td><img src="<?php echo Yii::app()->params['http_addr']; ?>images/pixel.gif" width="6" height="6"></td>
        </tr>
        <tr>
            <td bgcolor="#999999"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/pixel.gif" width="1" height="2"></td>
        </tr>
        <tr>
            <td><img src="<?php echo Yii::app()->params['http_addr']; ?>images/pixel.gif" width="6" height="6"></td>
        </tr>
    </tbody>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <td align="left" nowrap="nowrap">
                <span class="TextMedium">
                    <b><?php echo Yii::t('lang', 'Name'); ?>:</b> <?php echo $client->name; ?><br>
                    <b><?php echo Yii::t('lang', 'Email'); ?> :</b> <a class="LinkMedium" href="mailto:<?php echo $client->emailaddress; ?>"><?php echo $client->emailaddress; ?></a><br>
                    <b><?php echo Yii::t('lang', 'Status'); ?> :</b> <?php echo $client->type; ?><br>
                </span>
            </td>
        </tr>
    </tbody>
</table>
<br>
<?php echo CHtml::beginForm(); ?>
<?php echo Yii::t('lang', 'notifyreaders_msg_1'); ?> 

<br><form action="/chat/notifyreaders.php" method="post">
    <br>
    <input name="when" type="radio" value="15mins" checked> <?php echo Yii::t('lang', 'Within_the_next'); ?> 
    <input name="when" type="radio" value="custom"> <?php echo Yii::t('lang', 'Make_an_appointment'); ?> 
    <br><br>
    <b><font color="#138A22"><?php echo Yii::t('lang', 'The_current_time_is'); ?>  08:43 am</font></b>
    <br><br>
    <table border="0" width="200" cellspacing="0" cellpadding="0" name="chattime">
        <tr>
            <td>
                <select size="1" name="month_main">
                    <?php 
                    $mon = PsyConstants::getName(PsyConstants::MONTH);
                    for($i=1;$i<=12;$i++): 
                    ?>
                    <option value="<?php echo ($i<10) ? '0'.$i : $i ?>"><?php echo Yii::t('lang', $mon[$i]); ?></option>
                    <?php endfor; ?>
                </select>
            </td>
            <td>
                <select size="1" name="day_main">
                    <?php for($i=1;$i<=31;$i++): ?>
                        <option value="<?php echo ($i<10) ? '0'.$i : $i ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </td>
            <td>
                <select size="1" name="year_main">
                    <option value="2010">2010</option>
                    <option value="2011">2011</option>
                </select>
            </td>
        </tr>
    </table>

    <input name="time_main" type="text" value="<?php echo date("h:i a"); ?>">
    <br><br>
    <b><?php echo Yii::t('lang', 'ALTERNATE_date_and_time'); ?>:</b>
    <br>
    <table border="0" width="200" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                <select size="1" name="month_alt">
                    <?php
                    $mon = PsyConstants::getName(PsyConstants::MONTH);
                    for($i=1;$i<=12;$i++):
                    ?>
                    <option value="<?php echo ($i<10) ? '0'.$i : $i ?>"><?php echo Yii::t('lang', $mon[$i]); ?></option>
                    <?php endfor; ?>
                </select>

            </td>
            <td>
                <select size="1" name="day_alt">
                    <?php for($i=1;$i<=31;$i++): ?>
                        <option value="<?php echo ($i<10) ? '0'.$i : $i ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </td>
            <td>
                <select size="1" name="year_alt">
                    <option value="2010">2010</option>
                    <option value="2011">2011</option>
                </select>
            </td>
        </tr>
    </table>

    <input name="time_alt" type="text" value="<?php echo date("h:i a"); ?>">
    <br><br>
    <?php foreach($readers as $reader): ?>
    <input name="readers[]" type="checkbox" value="<?php echo $reader->rr_record_id ?>" id="reader_<?php echo $reader->rr_record_id ?>">
    <?php echo $reader->name; ?><br>
    <?php
        $js_all_on  .= "getdata.form.reader_".$reader->rr_record_id.".checked=true;\n";
	$js_all_off .= "getdata.form.reader_".$reader->rr_record_id.".checked=false;\n";
    ?>
    <?php endforeach; ?>
<SCRIPT LANGUAGE="JavaScript">
<!--
function allreaders(getdata)
{
    if(getdata.form.setall.checked == true)
    {
    <?php echo $js_all_on; ?>
    }
    else
    {
     <?php echo $js_all_off; ?>
    }
}
//-->
</SCRIPT>
----------------
<br>
<input name="setall" type="checkbox" value="yes" onClick="javasript:allreaders(this);"><?php echo Yii::t('lang', 'All_Readers'); ?> <br><br>
<input type="submit" name="send" value="<?php echo Yii::t('lang', 'Send_Notification'); ?> "><br><br>
<?php echo CHtml::endForm(); ?>
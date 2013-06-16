<?php if($status == 'break'): ?>
<?php echo Yii::t('lang', 'chatmonitor_break'); ?> (<font id="mins"><?php echo $minutes; ?></font> <?php echo Yii::t('lang', 'mins'); ?>).
<a href="javascript:changeReaderStatus('online',1)"><?php echo Yii::t('lang', 'Go_online'); ?>!</a></font>
<?php endif; ?>
<?php if($status == 'busy'): ?>
<?php echo Yii::t('lang', 'The_Reader_is_Busy'); ?>
<?php endif; ?>
<?php if($status == 'online'): ?>
<script>
    changeReaderStatus('online');
</script>
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%">
  <tr>
    <td width="30%">
        <table>
            <tr>
                <td>
                    <a href="javascript:changeReaderStatus('break5',1)"><?php echo Yii::t('lang', 'Take_5_mins_break'); ?></a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="javascript:changeReaderStatus('break10',1)"><?php echo Yii::t('lang', 'Take_10_mins_break'); ?></a>
                </td>
            </tr>
        </table>
    </td>
  </tr>
</table>
<?php endif; ?>

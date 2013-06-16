<?php
if($nopayment)
{
	echo
         Yii::t('lang', 'end_session_msg_8_1') .$readerCouldMake_money." ". Yii::t('lang', 'for') .$readerCouldMake_mins." ".Yii::t('lang', 'paid_mins').".<br>"
		.Yii::t('lang', 'Rate').": \$".$rate." ".Yii::t('lang', 'per_minute')."<br>"
		.Yii::t('lang', 'Including_BMT').": $bmt<br>"
		.Yii::t('lang', 'Free_time').": ".round($freeTime,2)." ".Yii::t('lang', 'mins').".<br>"
		.Yii::t('lang', 'Clients_username').": ".$clientName."<br>"
		.Yii::t('lang', 'Affiliate').": ".$affiliate;
}

else
{
	echo
		 Yii::t('lang', 'The_Reader_makes')." \$"
		.round($dueToReader, 2)
		." ".Yii::t('lang', 'for')." ".($paidTime+$halfPaidtime)
		." ".Yii::t('lang', 'paid_mins').(($halfPaidtime>0)?('('.$halfPaidtime.' mins paid in half)'):'').".<br>"
		.Yii::t('lang', 'Rate').": \$".$rate." ".Yii::t('lang', 'per_minute')."<br>"
		.Yii::t('lang', 'Including_BMT').": $bmt<br>".Yii::t('lang', 'Free_time').": ".round($freeTime,2)." ".Yii::t('lang', 'mins').".<br>"
		.Yii::t('lang', 'Clients_username').": ".$clientName."<br>"
		.Yii::t('lang', 'Affiliate').": ".$affiliate;
}

?>
<br>
<?php echo Yii::t('lang', 'You_can:'); ?> <br><br>
1. <a href="<?php echo Yii::app()->params['http_addr']; ?>support/feedBack?session_key=<?php echo $session_key; ?>"><?php echo Yii::t('lang', 'end_session_msg_13'); ?></a>.<br>

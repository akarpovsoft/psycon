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

<script>
    function parentOpen(href)
    {
        if(window.opener)
            window.opener.location.href = href;
        else
            window.location.href = href;
    }
</script>
<?php if (($emails > 0) || ($nrr > 0)): ?>
    <br>-------------------------------------------<br>
    <?php if($emails > 0): ?>
        <br><font color="red">
            <b>
                <?php echo Yii::t('lang', 'You_have'); ?>
                <?php echo $emails; ?>
                <?php echo Yii::t('lang', 'pending'); ?>
                <a href="javascript:parentOpen('<?php echo Yii::app()->params['http_addr']; ?>emailreadings/pending')" target="_top"><?php echo Yii::t('lang', 'Email_readings'); ?></a>
            </b>
        </font>
        <br>
    <?php endif; ?>
    <?php if($nrr > 0): ?>
        <br>
        <font color="red">
            <b>
                <?php echo Yii::t('lang', 'You_have'); ?>
                <?php echo $nrr; ?>
                <?php echo Yii::t('lang', 'new_litle'); ?>
                <a href="javascript:parentOpen('<?php echo Yii::app()->params['http_addr']; ?>users/nrrQuest')" target="_top"> <?php echo Yii::t('lang', 'NRR_Request_Forms'); ?> </a>
            </b>
        </font>
        <br>
    <?php endif; ?>
    <br>-------------------------------------------<br>
<?php endif; ?>

<?php echo Yii::t('lang', 'Message_Center'); ?>
<hr>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <td align="left" nowrap="nowrap">
                <span class="TextMedium">
                    <b><?php echo Yii::t('lang', 'Name'); ?>:</b> <?php echo $client->name; ?><br>
                    <b><?php echo Yii::t('lang', 'Email'); ?>:</b> <a class="LinkMedium" href="mailto:<?php echo $client->emailaddress; ?>"><?php echo $client->emailaddress; ?></a><br>
                    <b><?php echo Yii::t('lang', 'Status'); ?>:</b> <?php echo $client->type; ?><br>
                </span>
            </td>
        </tr>
    </tbody>
</table>
<?php echo CHtml::beginForm(); ?>
<br>
<?php if(empty($banned)): ?>
<b><a href="reply?id=<?php echo $message->ID; ?>"><?php echo Yii::t('lang', 'Reply'); ?></a></b>
<br>
<?php endif; ?>
<br>
<?php echo Yii::t('lang', 'From'); ?>: <b><?php echo $message->From_name; ?></b><br>
<b><?php echo $message->Subject ?></b><br>
<b><?php echo date("M j, Y g:i a", strtotime($message->Date)); ?></b><br><br>
<?php echo $message->Body; ?>
<br><br>
<?php if($download == 'yes'): ?>
<a href="download?id=<?php echo $message->ID; ?>"><?php echo Yii::t('lang', 'view_message_txt_3'); ?></a>
<br><br>
<?php endif; ?>
<input type="submit" name="del_message" value="<?php echo Yii::t('lang', 'Delete'); ?>" />
<?php echo CHtml::endForm(); ?>

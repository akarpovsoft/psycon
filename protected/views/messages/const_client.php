<?php echo Yii::t('lang', 'Messages'); ?>
<hr>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody><tr>
            <td align="left" nowrap="nowrap">
                <span class="TextMedium">
                    <b><?php echo Yii::t('lang', 'Name'); ?>: </b><?php echo $sender->name; ?><br>
                    <b><?php echo Yii::t('lang', 'Email'); ?>:</b> <a class="LinkMedium" href="mailto:<?php echo $sender->emailaddress; ?>"><?php echo $sender->emailaddress; ?></a><br>
                </span>
            </td>
        </tr>
    </tbody>
</table>
<br>
<?php if(isset($errors)): ?>
    <?php $this->widget('ErrorMessage', array('message' => $errors)); ?>
<?php endif; ?>
<?php if(isset($success)): ?>
    <?php $this->widget('SuccessMessage', array('message' => Yii::t('lang', 'Your_message_has_been_send'))); ?>
<?php endif; ?>
<br>
<?php echo CHtml::beginForm('','post',array('enctype'=>'multipart/form-data')); ?>
<input type="hidden" name="from_user" value="<?php echo $sender->rr_record_id; ?>">
<input name="from_name" value="<?php echo $sender->name; ?>" type="hidden">
<input name="to" value="<?php echo $receiver->rr_record_id; ?>" type="hidden">
<table style="border-collapse: collapse;" border="0" bordercolor="#111111" cellpadding="0" cellspacing="0" width="70%">
    <tbody><tr>

            <td colspan="2" width="100%"></td>
        </tr>
        <tr>
            <td width="50%"><?php echo Yii::t('lang', 'To_email'); ?>: </td>
            <td width="50%">
                <b><?php echo ($receiver->rr_record_id == 1) ? 'Admin' : $receiver->credit_cards->firstname; ?></b>
            </td>
        </tr>
        <tr>
            <td width="50%"><?php echo Yii::t('lang', 'User_Name'); ?>:</td>
            <td width="50%">
                <b><?php echo ($receiver->rr_record_id == 1) ? '' : $receiver->login; ?></b>
            </td>
        </tr>
        <tr>
            <td width="50%"><?php echo Yii::t('lang', 'From'); ?>:</td>
            <td width="50%">
                <b><?php echo $sender->name; ?></b>
            </td>
        </tr>
        <tr>
            <td width="50%"><?php echo Yii::t('lang', 'Subject'); ?>:</td>
            <td width="50%">
                <?php if($receiver->rr_record_id == 1): ?>
                    <select size="1" name="subject">
                        <?php if($message->Subject == 'sma'): ?>
                            <option value="Submit magazine article" selected>Submit magazine article</option>
                        <?php endif; ?>
                        <option value="Client related">Client related</option>
                        <option value="Chat Room Tech related">Chat Room Tech related</option>
                        <option value="Chat Room Tech related (Logging on)">Chat Room Tech related (Logging on)</option>
                        <option value="Chat Room Tech related (Logging off)">Chat Room Tech related (Logging off)</option>
                        <option value="Chat Room Tech related (Failed chat)">Chat Room Tech related (Failed chat)</option>
                        <option value="Schedule related">Schedule related</option>
                        <option value="Invoice related">Invoice related</option>
                        <option value="Profile related">Profile related</option>
                        <option value="Other">Other</option>
                    </select>
                <?php else: ?>
                    <input class="InputBoxFront" size="40" maxlength="50" name="subject" value="">
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td width="50%"><?php echo Yii::t('lang', 'Body'); ?>:</td>
            <td width="50%">
                <textarea rows="5" name="text" cols="34">
                </textarea>
            </td>
        </tr>
        <tr>
            <td width="50%"></td>
            <td width="50%">
                <br><font color="red" size="2"><?php echo Yii::t('lang', 'Attachment_feature_is_for_uploading'); ?></font><br><br>
                <?php echo CHtml::activeFileField($message, 'attach'); ?>
            </td>
        </tr>
    </tbody></table>
<input value="<?php echo Yii::t('lang', 'Send'); ?>" type="submit" name="send">
<?php echo CHtml::endForm(); ?>
<hr>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody><tr>
            <td align="left" nowrap="nowrap">
                <span class="TextMedium">
                    <b><?php echo Yii::t('lang', 'Name'); ?>: </b><?php echo $client->name; ?><br>
                    <b><?php echo Yii::t('lang', 'Email'); ?>:</b> <a class="LinkMedium" href="mailto:<?php echo $client->emailaddress; ?>"><?php echo $client->emailaddress; ?></a><br>
                </span>
            </td>
        </tr>
    </tbody>
</table>
<br>
<?php if(isset($errors)): ?>
<table bgcolor="#ffffcc" border="0" cellpadding="4" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <td colspan="3" align="center"><b><?php echo Yii::t('lang', 'Message_sending_errors'); ?></b></td>
        </tr>
        <tr>
            <td><img src="../images/iconinformation.gif" border="align=absmiddle" width="40" height="40" ></td>
            <td><img src="images/transp.gif" width="5" height="1"></td>
            <td class="pperrorbold" width="100%">
                    <?php if(is_array($errors)) {
                        foreach($errors as $error)
                            echo ' - '.$error[0].'<br />';
                    } else {
                        echo $errors.'<br />';
                    }
                    ?>
            </td>
        </tr>
    </tbody>
</table>
<?php endif; ?>
<?php if(isset($success)): ?>
<table bgcolor="#ffffcc" border="0" cellpadding="4" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <td colspan="3" align="center"><b><?php echo Yii::t('lang', 'Successfull_message_sending'); ?></b></td>
        </tr>
        <tr>
            <td><img src="../images/checkmark_big.gif" border="align=absmiddle" width="40" height="40" ></td>
            <td><img src="images/transp.gif" width="5" height="1"></td>
            <td class="pperrorbold" width="100%">
                 <?php echo Yii::t('lang', 'Your_message_has_been_send'); ?>
            </td>
        </tr>
    </tbody></table>
<?php endif; ?>
<br>
<?php echo CHtml::beginForm('','post',array('enctype'=>'multipart/form-data')); ?>
<input type="hidden" name="from_user" value="<?php echo $client->rr_record_id; ?>">

<table style="border-collapse: collapse;" border="0" bordercolor="#111111" cellpadding="0" cellspacing="0" width="70%">
    <tbody><tr>

            <td colspan="2" width="100%"></td>
        </tr>
        <tr>
            <td width="50%"><?php echo Yii::t('lang', 'To_Reader'); ?></td>
            <td width="50%">
                <select size="1" name="to">
                    <?php foreach($readers as $reader): ?>
                    <option value="<?php echo $reader->rr_record_id; ?>"><?php echo $reader->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td width="50%"><?php echo Yii::t('lang', 'From_Name'); ?>:</td>
            <td width="50%"><b><?php echo $client->credit_cards->firstname; ?></b><input name="from_name" value="<?php echo $client->credit_cards->firstname; ?>" type="hidden"></td>
        </tr>
        <tr>
            <td width="50%"><?php echo Yii::t('lang', 'Subject'); ?>:</td>
            <td width="50%"><input class="InputBoxFront" size="40" maxlength="50" name="subject" value="<?php echo $_POST['subject'] ?>"></td>
        </tr>
        <tr>
            <td width="50%"><?php echo Yii::t('lang', 'Body'); ?>:</td>
            <td width="50%"><textarea rows="5" name="text" cols="28"><?php echo $_POST['text']; ?></textarea></td>
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
<?php echo Yii::t('lang', 'Change_your_Information'); ?>
<hr>
<?php echo CHtml::beginForm(); ?>
<input type="hidden" name="client_id" value="<?php echo $client->rr_record_id; ?>" />
<table>
    <tbody>
        <tr>
            <td valign="top">
                <img src="<?php echo Yii::app()->params['http_addr']; ?>images/oneoperatoricon_small.gif" border="0" width="17" height="17">
            </td>
            <td nowrap="nowrap" style="margin-left: 10px">
                <span class="TextSmall">
                    <?php echo Yii::t('lang', 'First_Name'); ?>:<br><small>(<?php echo Yii::t('lang', 'Credit_Card_Billing_Name'); ?>)</small>
                </span>
            </td>
            <td>
                <b><?php echo $client->credit_cards->firstname; ?></b>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td nowrap="nowrap">
                <span class="TextSmall">
                    <?php echo Yii::t('lang', 'Last_Name'); ?>:<br><small>(<?php echo Yii::t('lang', 'Credit_Card_Billing_Name'); ?>)</small>
                </span>
            </td>
            <td>
                <b><?php echo $client->credit_cards->lastname; ?></b>
            </td>
        </tr>
        <tr>
            <td></td>
            <td nowrap="nowrap">
                <span class="TextSmall">
                    <?php echo Yii::t('lang', 'Login'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td>
                <b><?php echo $client->login ?></b>
            </td>
        </tr>
        <tr>
            <td></td>
            <td nowrap="nowrap">
                <span class="TextSmall">
                    <?php echo Yii::t('lang', 'Password'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td>
                <input class="InputBoxFront" type="password" size="20" maxlength="50" name="password" value="<?php echo $client->password ?>">
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td width="100%">
                <hr style="color: silver" noshade>
            </td>
        </tr>
        <tr>
            <td></td>
            <td nowrap="nowrap">
                <span class="TextSmall">
                    <?php echo Yii::t('lang', 'forget_email'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td>
                <span class="TextTiny"><?php echo Yii::t('lang', 'client_edit_txt_1'); ?><br></span>
                <input class="InputBoxFront" size="20" maxlength="50" name="emailaddress" value="<?php echo $client->emailaddress; ?>">
            </td>
        </tr>
        <tr>
            <td></td>
            <td nowrap="nowrap">
                <span class="TextSmall">
                    <?php echo Yii::t('lang', 'address'); ?>:
                </span>
            </td>
            <td><?php $client->credit_cards->billingaddress ?><br><?php $client->credit_cards->billingcity ?><br> <?php echo Yii::t('lang', 'Zip'); ?>: <?php echo $client->credit_cards->billingzip ?> <br> <?php $client->credit_cards->billingstate ?> <br> <?php $client->credit_cards->billingcountry ?></td>
        </tr>
        <tr>
            <td></td>
            <td nowrap="nowrap">
                <span class="TextSmall">
                    <?php echo Yii::t('lang', 'DOB'); ?>:
                </span>
            </td>
            <td>
                <?php echo $client->month ?> <?php echo $client->day ?>, <?php echo $client->year ?>
            </td>
        </tr>
        <tr>
            <td colspan="5" width="100%" align="right">
                <hr><br>
                <input type="submit" name="save" value="Save">
            </td>
        </tr>
    </tbody>
</table>
<?php echo CHtml::endForm(); ?>

<?php if($page == 'success'): ?>
<table bgcolor="#aaaaaa" border="0" cellpadding="1" cellspacing="0" width="100%">
    <tbody><tr>
            <td>

                <table bgcolor="#ffffcc" border="0" cellpadding="4" cellspacing="0" width="100%">
                    <tbody><tr>
                            <td><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/checkmark_big.gif" border="align=absmiddle" width="40" height="40"></td>

                            <td><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="5" height="1"></td>
                            <td class="pperrorbold" width="100%">
                                <?php echo Yii::t('lang', 'Forgotten_user'); ?> 
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<?php else: ?>
<table bgcolor="#aaaaaa" border="0" cellpadding="1" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <td>
                <table bgcolor="#ffffcc" border="0" cellpadding="4" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <td>
                                <img src="<?php echo Yii::app()->params['http_addr']; ?>images/iconinformation.gif" border="align=absmiddle" width="40" height="40">
                            </td>
                            <td>
                                <img src="<?php echo Yii::app()->params['http_addr']; ?>images/transp.gif" width="5" height="1">
                            </td>
                            <td class="pperrorbold" width="100%">
                                <?php if($page == 'error'): ?>
                                <?php echo Yii::t('lang', 'Sorry_but_this_login'); ?> 
                                <?php else: ?>
                                <?php echo Yii::t('lang', 'The_Login_Password_combination'); ?> 
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<br>
<?php echo CHtml::beginForm(); ?>
<table border="0" width="350">
    <tbody>
        <tr>
            <td colspan="3">
                <span class="TextSmall">
                <?php echo Yii::t('lang', 'ilogin_msg_32'); ?>
                <br><br>
                </span>
            </td>
        </tr>
        <tr>
            <td valign="top" nowrap="nowrap">
                <span class="TextMedium">
                <?php echo Yii::t('lang', 'ilogin_msg_33'); ?>
                </span>
            </td>
            <td nowrap="nowrap">
                <input class="InputBoxSmall" size="30" name="EmailOrLogin">
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input value="<?php echo Yii::t('lang', 'Send_Login_Details'); ?>" type="submit" name="forgot_password">
            </td>
        </tr>
    </tbody>
</table>
<span class="TextSmall">
<br><br>
<?php echo Yii::t('lang', 'ilogin_msg_34_1'); ?> <b><a class="LinkSmall" onfocus="blur();" href="<?php echo Yii::app()->params['http_addr'] ?>site/contact"><?php echo Yii::t('lang', 'Contact_us'); ?></a></b>. <?php echo Yii::t('lang', 'ilogin_msg_35'); ?>
</span>
<br>
<?php echo CHtml::endForm(); ?>
<?php endif; ?>

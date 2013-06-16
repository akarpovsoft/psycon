<?php if($page == 'success'): ?>
<table bgcolor="#aaaaaa" border="0" cellpadding="1" cellspacing="0" width="100%">
    <tbody><tr>
            <td>

                <table bgcolor="#ffffcc" border="0" cellpadding="4" cellspacing="0" width="100%">
                    <tbody><tr>
                            <td><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/checkmark_big.gif" border="align=absmiddle" width="40" height="40"></td>

                            <td><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="5" height="1"></td>
                            <td class="pperrorbold" width="100%">
                                Forgotten user data has been send to your email address.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<?php else: ?>
<?php if($page == 'enter'): ?>
<table bgcolor="#aaaaaa" border="0" cellpadding="1" cellspacing="0" width="100%">
    <tbody><tr>
            <td>

                <table bgcolor="#ffffcc" border="0" cellpadding="4" cellspacing="0" width="100%">
                    <tbody><tr>
                            <td><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/iconinformation.gif" border="align=absmiddle" width="40" height="40"></td>

                            <td><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="5" height="1"></td>
                            <td class="pperrorbold" width="100%">You are creating more than One account!<br><br>To gain quicker access to the site and to your existing account please do the following now:<br><br>
                                1) Enter your email address(es) to see your existing account info<br><br>
                                2) If NO account info appears on the responder page, then please fill out the form below.<br><br>
                                NOTE: If we do not receive the form from you, and you attempt to create another account again- the system will block you for 24hrs.
                                We will respond to you as soon as possible after receiving the submitted form.<br> If this is a mistake, please <a href="<?php echo Yii::app()->params['http_addr'] ?>site/contact" class="LinkMedium">contact us</a></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<?php endif; ?>
<?php if($page == 'error'): ?>
<table bgcolor="#aaaaaa" border="0" cellpadding="1" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <td>
                <table bgcolor="#ffffcc" border="0" cellpadding="4" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <td><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/iconinformation.gif" border="align=absmiddle" width="40" height="40"></td>
                            <td><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="5" height="1"></td>
                            <td class="pperrorbold" width="100%">
                                Sorry, but this login or email address not found in our database.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<?php endif; ?>
<br>It appears you are trying to create a 2nd account on our site,
to help us find the problem please take a moment to fill out this
quick form- we may then be able to give you access to all
the site's features.<br><br>
<?php echo CHtml::beginForm(); ?>
    <input name="forgot_password" value="yes" type="hidden">
    <table border="0" width="350"><tbody><tr><td colspan="3">
                    <span class="TextSmall">
                        If you forgot your login details, please enter the Email address you provided on registration.
                        <br><br>The login details will be sent to this address.
                        <br><br>
                    </span>
                </td>
            </tr>
            <tr>
                <td valign="top" nowrap="nowrap">
                    <span class="TextMedium">
                        Email Address or<br>
                        Username:
                    </span>
                </td>
                <td nowrap="nowrap">
                    <input class="InputBoxSmall" size="30" name="EmailOrLogin">
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input value="Send Login Details" type="submit">
                </td>
            </tr>
        </tbody>
    </table>
    <span class="TextSmall">
        <br><br>
        If you are still having difficulties, 
        please <b><a class="LinkSmall" href="<?php echo Yii::app()->params['http_addr'] ?>site/contact">Contact us</a></b>.
        We apologize for any inconvenience this may be causing you.
    </span>
    <br>
<?php echo CHtml::endForm(); ?>
<?php endif; ?>
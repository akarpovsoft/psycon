<script language="javascript">
function show(divid)
{
	if (divid == 'chat')
	{
		shiftOpacity('email', 500);
		document.getElementById('email').style.display="none";
	}
	else if (divid == 'email')
	{
		shiftOpacity('email', 500);
		document.getElementById('email').style.display="inline";

	}
}

function opacity(id, opacStart, opacEnd, millisec) {
	//speed for each frame
	var speed = Math.round(millisec / 100);
	var timer = 0;

	//determine the direction for the blending, if start and end are the same nothing happens
	if(opacStart > opacEnd) {
		for(i = opacStart; i >= opacEnd; i--) {
			setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
			timer++;
		}
	} else if(opacStart < opacEnd) {
		for(i = opacStart; i <= opacEnd; i++)
			{
			setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
			timer++;
		}
	}
}

//change the opacity for different browsers
function changeOpac(opacity, id) {
	var object = document.getElementById(id).style;
	object.opacity = (opacity / 100);
	object.MozOpacity = (opacity / 100);
	object.KhtmlOpacity = (opacity / 100);
	object.filter = "alpha(opacity=" + opacity + ")";
}


function shiftOpacity(id, millisec) {
	//if an element is invisible, make it visible, else make it ivisible
	if(document.getElementById(id).style.opacity == 0) {
		opacity(id, 0, 100, millisec);
	} else {
		opacity(id, 100, 0, millisec);
	}
}

</script>
<table border="0" cellpadding="0" cellspacing="0" width="550">
    <tbody>
        <tr>
            <td background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxldevelop1.gif" width="25" height="31" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="25" height="31"></td>
            <td width="100%">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <td background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxdevelop1.gif" valign="top" nowrap="nowrap">
                                <table background="" border="0" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr height="11">
                                            <td width="1"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="1" height="11"></td>
                                        </tr>
                                        <tr>
                                            <td nowrap="nowrap"><span class="TextButton"><font color="#42595a"><b>Signup Certificate</b></font></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxudevelop1.gif" width="18" height="31" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="18" height="31"></td>
                            <td background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxuu1.gif" width="100%" height="31" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" height="31"></td>
                        </tr>
                </table>
            </td>
            <td width=14 height=31 nowrap background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxrdevelop1.gif"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width=14 height=31></td>
            <td nowrap width=1 ><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width=1></td>
            <td width=1 nowrap><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" ></td>
        </tr>
        <tr>
            <td width=25 nowrap background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxl1.gif"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width=25></td>
            <td  bgcolor="#F7F7F7">
                <?php if(isset($success)): ?>
                <table bgcolor="#aaaaaa" border="0" cellpadding="1" cellspacing="0" width="100%">
                    <tr>
                        <td>
                            <table bgcolor="#ffffcc" border="0" cellpadding="4" cellspacing="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td colspan="3" align="center"><b><?php echo Yii::t('lang', 'Successfull_signup') ?></b></td>
                                    </tr>
                                    <tr>
                                        <td><img src="../images/checkmark_big.gif" border="align=absmiddle" width="40" height="40" ></td>
                                        <td><img src="images/transp.gif" width="5" height="1"></td>
                                        <td class="pperrorbold" width="100%">
                                            Your new account has been approved!<br>
                                            To log on:  Please enter your new Username and Password in the logon form fields here <a href="<?php echo Yii::app()->params['http_addr']; ?>site/login"><?php echo Yii::app()->params['http_addr']; ?>site/login</a><br>
                                            If you need further assistance please go here: <a href="<?php echo Yii::app()->params['site_domain']; ?>/assistance.html"><?php echo Yii::app()->params['site_domain']; ?>/assistance.html</a><br>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td nowrap width=14 nowrap background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxright1.gif"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width=14></td>
            <td nowrap width=1 ><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width=1></td>
        </tr>
        <tr>
            <td background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxlddevelop1.gif" width="15" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="15" height="12"></td>
            <td background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxdddevelop1.gif" width="100%" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" height="12"></td>
            <td background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxrddevelop1.gif" width="14" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="14" height="12"></td>
            <td width="1" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="1" height="12"></td>
            <td width="1" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="1" height="12"></td>
        </tr>
    </tbody>
</table>
<?php else: ?>
<?php if(isset($errors)): ?>
<table bgcolor="#aaaaaa" border="0" cellpadding="1" cellspacing="0" width="100%">
    <tr>
        <td>
            <table bgcolor="#ffffcc" border="0" cellpadding="4" cellspacing="0" width="100%">
                <tbody>
                    <tr>
                        <td colspan="3" align="center"><b>There has been an error - please see below to correct</b></td>
                    </tr>
                    <tr>
                        <td><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/iconinformation.gif" border="align=absmiddle" width="40" height="40" ></td>
                        <td><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="5" height="1"></td>
                        <td class="pperrorbold" width="100%">
                                    <?php if(is_array($errors)) {
                                        foreach($errors as $error)
                                            echo " - ".$error[0].'<br />';
                                    } else {
                                        echo $errors.'<br />';
                                    }
                                    ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
<?php endif; ?>
<?php echo CHtml::beginForm(); ?>
"Welcome to Psychic Contact...<br>
To redeem your gift certificate please fill out the following form:<br><br>
<table border="0" width="480">
    <tbody>
        <tr>
            <td><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/oneoperatoricon_small.gif" border="0" width="17" height="17">&nbsp;</td>
            <td nowrap="nowrap">
                <span class="TextSmall"><?php echo Yii::t('lang', 'ourclients_txt_3') ?></span>
            </td>
            <td>
                <?php echo CHtml::activeTextField($mod, 'firstname', array('class' => 'InputBoxFront', 'size' => 40, 'maxlength' => 50)); ?>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td nowrap="nowrap">
                <span class="TextSmall"><?php echo Yii::t('lang', 'ourclients_txt_4') ?></span>
            </td>
            <td>
                <?php echo CHtml::activeTextField($mod, 'lastname', array('class' => 'InputBoxFront', 'size' => 40, 'maxlength' => 50)); ?>
            </td>
        </tr>
        <tr>
            <td></td>
            <td nowrap="nowrap">
                <span class="TextSmall"> Create a username:&nbsp;&nbsp;</span>
            </td>
            <td>
                <span class="TextTiny">to access our chatrooms or receive an Email Reading<br></span>
                <?php echo CHtml::activeTextField($mod, 'login', array('class' => 'InputBoxFront', 'size' => 40, 'maxlength' => 50)); ?>
            </td>
        </tr>
        <tr>
            <td></td>
            <td nowrap="nowrap">
                <span class="TextSmall"> Password:&nbsp;&nbsp; </span>
            </td>
            <td>
                <?php echo CHtml::activePasswordField($mod, 'password', array('class' => 'InputBoxFront', 'size' => 40, 'maxlength' => 50)); ?>
            </td>
        </tr>
        <tr>
            <td></td>
            <td nowrap="nowrap">
                <span class="TextSmall"> Email Address:&nbsp;&nbsp; </span>
            </td>
            <td>
                <span class="TextTiny">Email will be the main form of communication, please make sure you type a valid Email Address<br></span>
                <?php echo CHtml::activeTextField($mod, 'email', array('class' => 'InputBoxFront', 'size' => 40, 'maxlength' => 50)); ?>
            </td>
        </tr>
        <tr>
            <td></td>
            <td valign="top" nowrap="nowrap">
                <span class="TextSmall"> Type:&nbsp;&nbsp; </span>
            </td>
            <td>
                <input name="signup_type" value="chat" onclick="show('chat')" <?php echo ($_POST['signup_type'] == 'chat')? 'checked="checked"' : '' ?> type="radio"> I want a 20mins chat reading <br>
                <input name="signup_type" value="email" onclick="show('email')" <?php echo ($_POST['signup_type'] == 'email')? 'checked="checked"' : '' ?> type="radio"> I want a E-reading (you will be prompted to fill out your e-reading form once you log on)
                <div id="email" style="display: none; opacity: 0;">
                    <table>
                        <tbody>
                            <tr>
                                <td>Reader</td>
                                <td>
                                    <?php echo CHtml::activeDropDownList($mod, 'reader', $readers_list); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Subject</td>
                                <td>
                                    <?php echo CHtml::activeTextField($mod, 'subject', array('class' => 'InputBoxFront', 'size' => 40, 'maxlength' => 50)); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
		</div>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <input type="submit" name="register" value="Register">
            </td>
        </tr>
    </tbody>
</table>
<?php echo CHtml::endForm(); ?>
</td>
<td nowrap width=14 nowrap background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxright1.gif"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width=14></td>
<td nowrap width=1 ><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width=1></td>
</tr>
<tr>
    <td background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxlddevelop1.gif" width="15" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="15" height="12"></td>
    <td background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxdddevelop1.gif" width="100%" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" height="12"></td>
    <td background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxrddevelop1.gif" width="14" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="14" height="12"></td>
    <td width="1" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="1" height="12"></td>
    <td width="1" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="1" height="12"></td>
</tr>
</tbody>
</table>
<br>
<center>
    <!-- BEGIN SiteSeal -->
        <script language="javascript" src="https://cgnsecurity.com/ss/getCgnSS.php?d1=522601312&d2=1295275807" type="text/javascript"></script>
    <!-- END SiteSeal -->
</center>
<?php endif; ?>
<script type="text/javascript">
    function check(){
        x = 0;
        error_msg = "You did not complete all required fields:\n\n"
        if(document.getElementById("Signup_login").value == ""){
            x = 1;
            error_msg += "* Login is required\n";
        }
        if(document.getElementById("Signup_email").value == ""){
            x = 1;
            error_msg += "* Email address is required\n";
        }
        p_email=document.getElementById("Signup_email").value.toString();
        if (p_email!='')
        { t = p_email.indexOf('@');
          if ((p_email.indexOf('.')==-1)||(t==-1)||(t < 1)||
           (t > p_email.length - 5) || (p_email.charAt(t - 1)=='.') || (p_email.charAt(t + 1)=='.'))
              { 
                  x = 1;
                  error_msg += "* Invalid Email address\n";;
              }
}
        if(document.getElementById("Signup_firstname").value == ""){
            x = 1;
            error_msg += "* First name is required\n";
        }
        if (document.getElementById("Signup_dob_month").value == "" ||
            document.getElementById("Signup_dob_day").value == "" ||
            document.getElementById("Signup_dob_year").value == "")
        {
                x = 1;
                error_msg += "* Date Of Birth is required\n";
        }
        if(document.getElementById("Signup_gender").value == ""){
            x = 1;
            error_msg += "* Gender is required\n";
        }
        if(x == 1){
            alert(error_msg);
            return false;
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
                                            <td nowrap="nowrap"><span class="TextButton"><font color="#42595a"><b>Signup</b></font></span></td>
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
                <?php
                $msg = 'Your new account has been approved!<br>
                       To log on:  Please enter your new Username and Password in the logon form fields here <a href="'.Yii::app()->params['http_addr'].'site/login">'.Yii::app()->params['http_addr'].'site/login</a><br>
                       If you need further assistance please go here: <a href="'.Yii::app()->params['site_domain'].'/assistance.html">'.Yii::app()->params['site_domain'].'/assistance.html</a><br>';
                $this->widget('SuccessMessage', array('message' => $msg));
                ?>
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
    <form action="<?php echo Yii::app()->params['ssl_addr'] ?>site/signupSecondStep" method="post" onSubmit="return check()">
    <?php echo CHtml::activeHiddenField($mod, 'affiliate', array('value' => $affiliate)); ?>
    <?php echo CHtml::activeHiddenField($mod, 'gift', array('value' => $gift)); ?>
    <?php echo CHtml::activeHiddenField($mod, 'debug', array('value' => $debug)); ?>    
    <?php if(isset($no_freebie)): ?>
        <?php echo CHtml::activeHiddenField($mod, 'no_freebie', array('value' => 1)); ?>
    <?php endif; ?>
<table border="0" width="480">
    <tbody>
        <tr>
            <td colspan="3">
                <span class="TextMedium">
                        <?php echo Yii::t('lang', 'register_msg_15'); ?>
                </span>
                    <?php if(isset($no_freebie)): ?>
                <br><b>Thank You for Signing Up for a Psychic Contact account!<br>
                    NOTE! Please be aware that this page is for <span style="color: rgb(136, 0, 0); text-decoration: underline;">PAID Orders ONLY</span>.
                    If you would like to have your first Reading for free - Please click <a href="<?php echo Yii::app()->params['ssl_addr']; ?>site/signup">here</a>
                    (Remember- we have NO recurring billing or membership fees at all!).</b>
                    <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/oneoperatoricon_small.gif" border="0" width="17" height="17">&nbsp;</td>
            <td nowrap="nowrap">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'ourclients_txt_3') ?>
                </span>
            </td>
            <td>
                    <?php echo CHtml::activeTextField($mod, 'firstname', array('class' => 'InputBoxFront', 'size' => 40, 'maxlength' => 50)); ?>
            </td>
        </tr>
        <?php if($mod->getError('firstname')): ?>
        <tr>
            <td colspan="3">
                <?php $this->widget('InputError', array('errors' => $mod->getError('firstname'))); ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td></td>
            <td nowrap="nowrap">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Login'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td>
                <span class="TextTiny"><?php echo Yii::t('lang', 'register_msg_18'); ?><br></span>
                    <?php echo CHtml::activeTextField($mod, 'login', array('class' => 'InputBoxFront', 'size' => 40, 'maxlength' => 50)); ?>
            </td>
        </tr>
        <?php if($mod->getError('login')): ?>
        <tr>
            <td colspan="3">
                <?php $this->widget('InputError', array('errors' => $mod->getError('login'))); ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td></td>
            <td nowrap="nowrap">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'forget_email'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td>
                <span class="TextTiny"><?php echo Yii::t('lang', 'client_edit_txt_1'); ?><br></span>
                    <?php echo CHtml::activeTextField($mod, 'email', array('class' => 'InputBoxFront', 'size' => 40, 'maxlength' => 50)); ?>
            </td>
        </tr>
        <?php if($mod->getError('email')): ?>
        <tr>
            <td colspan="3">
                <?php $this->widget('InputError', array('errors' => $mod->getError('email'))); ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td></td>
            <td nowrap="nowrap">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'register_msg_20'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td>
                    <?php echo CHtml::activeDropDownList($mod, 'hear', array(
                    '' => Yii::t('lang','Please_Select'),
                    'GOOGLE' => 'GOOGLE',
                    'YAHOO' => 'YAHOO',
                    'FACE BOOK' => 'FACE BOOK',
                    'TWITTER' => 'TWITTER',
                    'DIGG' => 'DIGG',
                    'YOU TUBE' => 'YOU TUBE',
                    'BLOGGING SITE' => 'BLOGGING SITE',
                    'WORD OF MOUTH' => 'WORD OF MOUTH',
                    'EMAIL ADVERTISMENT' => 'EMAIL ADVERTISMENT',
                    'RELATED SITE (psychic)' => 'RELATED SITE (psychic)',
                    'OTHER SEARCH ENGINE' => 'OTHER SEARCH ENGINE',
                    'OTHER (NOT LISTED)' => 'OTHER (NOT LISTED)'
                    )); ?>
            </td>
        </tr>
        <?php if($mod->getError('hear')): ?>
        <tr>
            <td colspan="3">
                <?php $this->widget('InputError', array('errors' => $mod->getError('hear'))); ?>
            </td>
        </tr>
        <?php endif; ?>        
        <tr>
            <td></td>
            <td nowrap="nowrap">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Date_of_Birth'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td nowrap="nowrap">
                    <?php echo CHtml::activeDropDownList($mod, 'dob_month', array(
                    '' => Yii::t('lang','Please_Select'),
                    'Jan' => 'Jan',
                    'Feb' => 'Feb',
                    'Mar' => 'Mar',
                    'Apr' => 'Apr',
                    'May' => 'May',
                    'Jun' => 'Jun',
                    'Jul' => 'Jul',
                    'Aug' => 'Aug',
                    'Sep' => 'Sep',
                    'Oct' => 'Oct',
                    'Nov' => 'Nov',
                    'Dec' => 'Dec'
                    )); ?>
                <select class="SelectBoxStandard" name="Signup[dob_day]" id="Signup_dob_day">
                    <option value="">-- Please Select --</option>
                        <?php for($i=1;$i<32;$i++): ?>
                    <option value="<?php echo $i; ?>" <?php echo ($mod->dob_day == $i) ? 'selected' : '' ?>><?php echo $i;?></option>
                        <?php endfor; ?>
                </select>
                <select class="SelectBoxStandard" name="Signup[dob_year]" id="Signup_dob_year">
                    <option value=""><?php echo Yii::t('lang','Please_Select'); ?></option>
                        <?php for($i=date('Y')-18;$i>date('Y')-73;$i--): ?>
                    <option value="<?php echo $i; ?>" <?php echo ($mod->dob_year == $i) ? 'selected' : '' ?>><?php echo $i; ?></option>
                        <?php endfor; ?>
                </select>
            </td>
        </tr>
        <?php if(($mod->getError('dob_month'))||($mod->getError('dob_day'))||($mod->getError('dob_year'))): ?>
        <tr>
            <td colspan="3">
                <?php
                $error_str = $mod->getError('dob_month').' '.$mod->getError('dob_day').' '.$mod->getError('dob_year');
                $this->widget('InputError', array('errors' => $error_str));
                ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td></td>
            <td nowrap="nowrap">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Gender'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td>
                    <?php echo CHtml::activeDropDownList($mod, 'gender', array(
                    '' => Yii::t('lang','Please_Select'),
                    'Male' => 'Male',
                    'Female' => 'Female'
                    )); ?>
            </td>
        </tr>
        <?php if($mod->getError('gender')): ?>
        <tr>
            <td colspan="3">
                <?php $this->widget('InputError', array('errors' => $mod->getError('gender'))); ?>
            </td>
        </tr>
        <?php endif; ?>        
        <tr>
            <td></td>
            <td colspan="4" width="100%">
                <span class="textMedium"><br><b>NOTE:</b> <?php echo Yii::t('lang', 'register_msg_28') ?></span>
            </td>
        </tr>
        <tr>
            <td colspan="5" width="100%">
                &nbsp;
                &nbsp;
                <input type="submit" name="firstStep" value="Register"><input type="reset" name="reset" value="Cancel">
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

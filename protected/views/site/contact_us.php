<center>If you have problems with new design , please return to <a href="<?php echo Yii::app()->params['site_domain'] ?>/chat/index.php">old version of site</a></center>
<center>
<?php if(isset($errors)): ?>
    <?php $this->widget('ErrorMessage', array('message' => $errors)); ?>
<?php endif; ?>
<?php if(isset($success)): ?>
    <?php $this->widget('SuccessMessage', array('message' => Yii::t('lang', 'You_have_successfully_send_your_message'))); ?>
<?php endif; ?>
<table border="0" cellspacing="0" width="450" >
    <tr>
        <td height='20px'>&nbsp;</td>
    </tr>
    <tr>
        <td width="193" align='center'  height='10px'>
            <?php echo Yii::t('lang', 'This_site_is_owned'); ?> 
        </td>
    </tr>
    <tr>
        <td width="193" align='center'  height='10px'>  <?php echo Yii::t('lang', '56_Stebbins_Ct'); ?> </td>
    </tr>
    <tr>
        <td width="193" align='center'  height='10px'>  <?php echo Yii::t('lang', 'DFS_FL_32433'); ?> </td>
    </tr>
    <tr>
        <td width="193" align='center'  height='10px'>  <?php echo Yii::t('lang', '1_800_337_8465'); ?> </td>
    </tr>
    <tr>
        <td width="193" align='center'  height='10px'> <?php echo Yii::t('lang', 'javachat@psychic-contact.com'); ?> </td>
    </tr>
</table>
<?php echo CHtml::beginForm(); ?>
    <input type="hidden" name="action" value="gateway">
    <table border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="450" id="AutoNumber1">
        <tr bgcolor="#336699">
            <td width="416" colspan="2" align="center"><font color="#FFFFFF"><b>
            <?php echo Yii::t('lang', 'Contact_us'); ?></b></font></td>
        </tr>
        <tr>
            <td width="193">
                <?php echo Yii::t('lang', 'Your_Full_Name'); ?>:
            </td>
            <td width="223">
                <?php echo CHtml::activeTextField($model, 'name', array('size' => '15', 'maxlength' => '100', 'class' => 'form')); ?>
            </td>
        </tr>
        <tr>
            <td width="193">
                <?php echo Yii::t('lang', 'Your_Email_Address'); ?>:
            </td>
            <td width="223">
                <?php echo CHtml::activeTextField($model, 'email', array('size' => '15', 'maxlength' => '100', 'class' => 'form')); ?>
            </td>
        </tr>
        <tr>
            <td width="193"><?php echo Yii::t('lang', 'Your_Username'); ?> 
            <br> <small>(<?php echo Yii::t('lang', 'if_you_are_registered'); ?>)</small></td>
            <td width="223">
                <?php echo CHtml::activeTextField($model, 'login', array('size' => '15', 'maxlength' => '100', 'class' => 'form')); ?>
            </td>
        </tr>
        <tr>
            <td width="193">
            <?php echo Yii::t('lang', 'Subject'); ?>:</td>
            <td width="223">
               <?php echo CHtml::activeDropDownList($model, 'subject', array(
                          'empty' => Yii::t('lang','Please_Select'),
                          'General Help' => Yii::t('lang','General_Help'),
                          'Report Abuse' => Yii::t('lang','Report_Abuse'),
                          'Report Page Errors' => Yii::t('lang','Report_Page_Errors'),
                          'Suggestions' => Yii::t('lang','Suggestions'),
                          'Chat Question' => Yii::t('lang','Chat_Question'),
                          'Affiliate Question' => Yii::t('lang','Affiliate_Question'),
                          'Become A Reader Question' => Yii::t('lang','Become_A_Reader_Question'),
                          'Other, Not Listed' => Yii::t('lang','Other_Not_Listed')));
               ?>
            </td>
        </tr>
        <tr>
            <td width="193"> <?php echo Yii::t('lang', 'Message'); ?>:</td>
            <td width="223">
                <?php echo CHtml::activeTextArea($model,'body', array('rows' => '10', 'cols' => '30')) ?>
            </td>
        </tr>
        <?php if(isset($guest)): ?>
            <tr>
                <td width="193">
                <?php echo Yii::t('lang', 'Please_type_the_characters'); ?>
                <?php echo Yii::t('lang', 'you_see_at_the_picture'); ?>:</td>
                <td width="223">
                    <?php $this->widget('CCaptcha'); ?>
                    <input type="text" name="ver">
                </td>
            </tr>
        <?php endif; ?>
    </table>
    <table border="0" cellpadding="2" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="419">
        <tr>
            <td width="416" align="left">
                <input type="submit" value="<?php echo Yii::t('lang', 'Send'); ?>" name="send">
            </td>
        </tr>
    </table>
<?php echo CHtml::endForm(); ?>
</center>
<?php
$this->pageTitle=Yii::app()->name . ' - ' . Yii::t('lang', 'Login');
$this->breadcrumbs=array(
	Yii::t('lang', 'Login'),
);
?>
<?php if(isset($acc_err))
        echo $acc_err;
else {
?>
<center>
  <script type="text/javascript">
  function ChangeToPassField() {
    document.getElementById('LoginForm_LoginPassword').type="password";
  }
  </script>

<?php echo CHtml::beginForm('login'); ?>
<input type="hidden" name="layout_type" value="<?php echo isset($layout) ? $layout : '' ?>">
<input type="hidden" name="lang" value="<?php echo isset($lang) ? $lang : '' ?>">
<div align="center">
  <center>
  <table style="border-collapse: collapse;" border="0" bordercolor="#111111" cellpadding="0" cellspacing="0" width="350">
    <tbody><tr>
      <td width="50%">
              <?php echo Yii::t('lang', 'Username'); ?> :
      </td>
      <td width="50%">
        <?php echo CHtml::textField('LoginForm[LoginName]','',array('class'=>'InputBoxDouble', 'style' => "width: 80px", 'size' => "7", 'maxlength' => "50")) ?>
      </td>
    </tr>
    <tr>
      <td width="50%">
              <?php echo Yii::t('lang', 'Password'); ?> :
      </td>
      <td width="50%">
          <?php //echo CHtml::passwordField('LoginForm[LoginPassword]','',array('class'=>'InputBoxDouble', 'style' => "width: 80px", 'size' => "7", 'maxlength' => "50")) ?>
<input class="InputBoxDouble" style="width: 80px" size="7" maxlength="50" type="text" value="" name="LoginForm[LoginPassword]" id="LoginForm_LoginPassword" onfocus="ChangeToPassField()" >   
   </td>
    </tr>
    <tr>
      <td colspan="2" width="100%">
        <?php echo CHtml::submitButton(Yii::t('lang', 'Login')) ?>
      </td>
    </tr>
  </tbody></table>
  </center>
</div>
<br><?php echo Yii::t('lang', 'No_Account'); ?>?<br><br>
<a href="<?php echo Yii::app()->params['ssl_addr']; ?>site/signup?register=1"><?php echo Yii::t('lang', 'Signup_Here'); ?></a><br><br>
<a href="<?php echo Yii::app()->params['http_addr']; ?>site/forgotLogin"><?php echo Yii::t('lang', 'Forgot_password'); ?>?</a><br><br>
<a href="<?php echo Yii::app()->params['http_addr']; ?>site/contact"><?php echo Yii::t('lang', 'Contact_us'); ?></a><br>
<br><br>
<a href="http://www.taroflash.com" target="_blank" class="LinkSmall"><?php echo Yii::t('lang', 'FREE_Tarot_Reading'); ?></a>
<br>
<br>
<?php echo CHtml::endForm(); ?>
<iframe src="http://www.facebook.com/plugins/like.php?href=www.psychic-contact.com&amp;layout=standard&amp;show_faces=false&amp;width=200&amp;action=like&amp;font=verdana&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:400px; height:80px;" allowTransparency="true"></iframe>
</center>
<?php
}
?>

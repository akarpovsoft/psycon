<center>
<?php echo CHtml::beginForm('login'); ?>
<div align="center">
  <center>
  <table style="border-collapse: collapse;" border="0" bordercolor="#111111" cellpadding="0" cellspacing="0">
    <tbody>
    <?php if(isset($errors)): ?>
      <tr>
          <td colspan="2">
            <?php $this->widget('ErrorMessage', array('message' => $errors)); ?>
          </td>
      </tr>
    <?php endif; ?>
    <tr>
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
          <?php echo CHtml::passwordField('LoginForm[LoginPassword]','',array('class'=>'InputBoxDouble', 'style' => "width: 80px", 'size' => "7", 'maxlength' => "50")) ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" width="100%">
        <?php echo CHtml::submitButton(Yii::t('lang', 'Login')) ?>
      </td>
    </tr>
  </tbody>
  </table>
  </center>
</div>
<?php echo CHtml::endForm(); ?>
</center>
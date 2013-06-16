<?php if(isset($success)): ?>
    <?php $this->widget('SuccessMessage', array('message' => 'Thank you for registration in our blog!<br>Click <a href="'.Yii::app()->params['http_addr'].'blog/login">here</a> to login in our blog system')); ?>
<?php else: ?>
<?php if(isset($errors)): ?>
    <?php $this->widget('ErrorMessage', array('message' => $errors)); ?>
<?php endif; ?>
<center>
<h3>Blog registration</h3>
<br>
<?php echo CHtml::beginForm(); ?>
<div align="center">
  <center>
  <table style="border-collapse: collapse;" cellpadding="0" cellspacing="0" width="350">
    <tbody>
        <tr>
            <td width="50%">Login:</td>
            <td width="50%">
                <input name="login" type="text" class='InputBoxDouble' style ="width: 80px" size = "7" maxlength = "50">
            </td>
        </tr>
        <tr>
            <td width="50%">Password:</td>
            <td width="50%">
                <input name="password" type="password" class='InputBoxDouble' style ="width: 80px" size = "7" maxlength = "50">
            </td>
        </tr>
        <tr>
            <td width="50%">First name:</td>
            <td width="50%">
                <input name="first_name" type="text" class='InputBoxDouble' style ="width: 80px" size = "7" maxlength = "50">
            </td>
        </tr>
        <tr>
            <td width="50%">Last name:</td>
            <td width="50%">
                <input name="last_name" type="text" class='InputBoxDouble' style ="width: 80px" size = "7" maxlength = "50">
            </td>
        </tr>

        <tr>
            <td colspan="2" width="100%">
                <input type="submit" name="begin_reg" value="register">
            </td>
        </tr>
    </tbody>
  </table>
  </center>
</div>
<?php echo CHtml::endForm(); ?>
</center>
<?php endif; ?>
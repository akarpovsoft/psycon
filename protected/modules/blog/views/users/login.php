<?php if(isset($errors)): ?>
    <?php $this->widget('ErrorMessage', array('message' => $errors)); ?>
<?php endif; ?>
<center>
<h3>Blog login</h3>
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
            <td colspan="2" width="100%">
                <input type="submit" name="begin_log" value="Login">
            </td>
        </tr>
    </tbody>
  </table>
  </center>
</div>
<?php echo CHtml::endForm(); ?>
<br><br>
Click <a href="<?php echo Yii::app()->params['http_addr'] ?>blog/users/register">here</a> to register on our Psychic Contact blog system
</center>
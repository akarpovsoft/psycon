<?php $this->widget('UserInfo'); ?>
<?php if(isset($hula_login)): ?>
<center>
    <font color="red"><b><?php echo ($hula_login == 'success') ? 'You have successfully login to Hula.ph' : 'Login to hula.ph failed'; ?></b></font>
</center>
<?php endif; ?>
<table width="90%" align="center">
<?php $this->widget('UserMenu'); ?>
</table>
<center>
<b>
<br><br>
<a href="<?php echo Yii::app()->params['http_addr']; ?>site/login">LOG IN</a>
<?php if(Yii::app()->user->isGuest): ?>
<br><br>
<a href="<?php echo Yii::app()->params['ssl_addr']; ?>site/signup">REGISTER</a>
<?php endif; ?>
<br><br>
<a href="<?php echo Yii::app()->params['http_addr']; ?>reader/<?php echo $_GET['id']; ?>">View <?php echo $name; ?>'s Profile</a>
</b>
</center>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Psychic Contact</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="<?php echo Yii::app()->params['http_addr']; ?>css/styles.css">
<script>
    function cHeight(re)
    {
      //find the height of the internal page
      var the_height=document.getElementById(re).contentWindow.document.body.scrollHeight;

      //change the height of the iframe
      document.getElementById(re).height=the_height;
    }

    function reload()
    {
            document.getElementById('readers_list').contentWindow.location.href=document.getElementById('readers_list').contentWindow.location;
    }

var mytimer = setInterval("reload()",60000) ;
</script>
</head>
<body>
<div class='main'>	
	<div class='mainLeftWrapper'>
		<div class='mainLeft'>&nbsp;
		</div>
	</div>
	<div class='mainCenter'>	
		<div class='mainTop2'>
			<div id='logo'><img src='<?php echo Yii::app()->params['http_addr']; ?>new_images/logo.gif'></div>
			<div class='mainTopRight'>
				<a href='#' title="facebook"><img src='<?php echo Yii::app()->params['http_addr']; ?>new_images/facebook3.png'></a>
				<a href='#'  title="twitter"><img src='<?php echo Yii::app()->params['http_addr']; ?>new_images/twitter3.png'></a>
				<a href='<?php echo Yii::app()->params['site_domain']; ?>/blog/'  title="blog"><img src='<?php echo Yii::app()->params['http_addr']; ?>new_images/blog.png'></a>			
			</div>
		</div>
		<div class='menuTop'>
                    <?php $this->widget('NewTopMenu'); ?>
		</div>
		<div class='mainContent'>	
			<div id='leftContent'>
                            
                        <?php echo $content; ?>				
                            
			<div class='contentBox2'>
			<div id='loginBox'>
                            <form action="<?php echo Yii::app()->params['http_addr']; ?>site/login" method="POST">
				<div class='headerWrapper3'></div>
				<span class='header1'>Client Login</span>
				<div class='headerWrapper4'></div>
				
					<div class='formField'>
						<label>User name</label>
						<input type="text" name='LoginForm[LoginName]'>
					</div>
					<div class='formField'>
						<label>Password</label>
						<div><input type="password" name='LoginForm[LoginPassword]'></div>
					</div>
				<div id='loginButton'><a href='<?php echo Yii::app()->params['ssl_addr']; ?>site/signup'>New Clients Register Here</a><input type="submit" value="Login"></div>
                            </form>
				<br>
			</div>
			<div id='linksBox1'>
				<?php $this->widget('PsyArThreads'); ?>
			</div>
			</div>
			<div id='linksBox2'>
			
			<ul class='links2'>
				<li><a href='#'>Membership</a></li>
				<li><a href='#'> Specials</a></li>
				<li> <a href='#'> Bookstore / Gifts</a></li>
					<li><a href='#'>"Psychic ?GoFish!!"</a></li>
				<li> <a href='#'> Avatar Quick Question</a></li>
				</ul>
				<img src='<?php echo Yii::app()->params['http_addr']; ?>new_images/couple.png' width='300'>
			</div>
			</div>
			<div id='rightContent'>
                            
			<div class='headerWrapper1'></div>
			<span class='header1'>Readers Online</span>
			<div class='headerWrapper2'></div>
                        <iframe src="<?php echo Yii::app()->params['http_addr']; ?>site/reloadReadersList" noresize="resize" id="readers_list" onload="cHeight('readers_list')" frameborder="0" scrolling="NO">
                        </iframe>			
			
			</div>
		</div>
<div id='footer'>
<div id='footerMenu'>
        <?php $this->widget('BottomMenu', array('type' => 'new')); ?>
</div>         
<div id='footerCopy'>  Copyright &copy 1998 - 2011<br> <b>Psychic Contact/Jayson Lynn.Net Inc. </b><br>All rights reserved</div>
<div id='footerLogos'>
	<img src='<?php echo Yii::app()->params['http_addr']; ?>new_images/geotrust_logo.png'>	
	<img src='<?php echo Yii::app()->params['http_addr']; ?>new_images/cg_logo.png'>
	<img src='<?php echo Yii::app()->params['http_addr']; ?>new_images/paypal_logo.png'>
</div>
</div>
	</div>
	<div class='mainRightWrapper'>
		<div class='mainRight'>&nbsp;</div>
	<div>
</div>
</div>
</div>


</body>
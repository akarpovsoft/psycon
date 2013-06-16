<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Psychic Contact</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="<?php echo Yii::app()->params['http_addr']; ?>css/styles.css">
<script>
    function calcHeight(re)
    {
      //find the height of the internal page
      var the_height=document.getElementById(re).contentWindow.document.body.scrollHeight;

      //change the height of the iframe
      document.getElementById(re).height=the_height;
    }

    function reload_page()
    {
            document.getElementById('readers_list').contentWindow.location.href=document.getElementById('readers_list').contentWindow.location;
    }

var mytimer = setInterval("reload_page()",60000) ;
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
		<div class ='mainAD'>
			<?php $this->widget('SlideShow'); ?>
			<div id='adMessage'>
				<p> New Client Special: 10 FREE Minutes (no obligation to purchase) <a href='#'>Chat Now</a>   </p>
  				<p>Repeat Client Special: 20 Minutes $19.95  <a href='#'> Log In </a></p>
				<i><p>Accurate, Compassionate, Professional & Ethical Psychic Readers.</p></i>
  	
			</div>
		</div>
	
		<div class='mainContentClear'>	
                    <center>
                    <table border="0" cellpadding="0" cellspacing="0" width="80%">
                        <tr>
                            <td align="center">
                                <?php echo $content; ?>
                            </td>
                        </tr>
                    </table>
                    </center>
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
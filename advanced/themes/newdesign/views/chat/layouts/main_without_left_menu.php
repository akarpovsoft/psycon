<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Psychic Contact</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="<?php echo Yii::app()->params['http_addr']; ?>css/stylesheet.css">
</head>

<body>
<div class="page">	
	<div class="top_header_bar">
    	<div class="logo">
    		<h1><a href="<?php echo Yii::app()->params['site_adv']; ?>" class="logo">Keywords go here for Psychic Contact</a></h1>
    	</div>
        <div class="social_icons">
        	<a href='#' style="margin: 0 0 0 40px;"><img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/facebook.jpg"></a>
			<a href='#'><img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/twitter.jpg"></a>
			<a href='<?php echo Yii::app()->params['site_domain']; ?>/blog/' title="blog"><img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/blog.jpg"></a>
        </div>
  </div>    
    <?php $this->widget('NewTopMenu'); ?> 
    
    <img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/top_content.gif" />
    <div class="content">    
      <div class="banner_sub">
          <img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/banner-sub-right.jpg" style="float: right;" /><img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/banner-sub-left.jpg" style="float: left;" />
          <div class="text">
          <h1>Accurate, Compassionate, Professional & <br />Ethical Psychic Readers.</h1>                   
          </div>              
      </div> <!-- end banner-->  
      
      <div class="sub_main_content">       
        <div class="center">
         <?php echo $content; ?>      
      	</div><!-- end center--> 
      </div>  <!-- end sub main content--> 
  	 <div class="clear"></div>
    </div><img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/content-bottom.gif" style="display: block; float: left;" />         
    <div class="footer">
      <p>
        <?php $this->widget('BottomMenu', array('type' => 'new')); ?>
      <p class="copyright">
      <img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/geo-trust-logo.jpg" /><img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/complyguard-networks-logo.jpg" /><img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/paypal-logo.gif" />
      Copyright &copy 1998 - <?php echo date('Y'); ?>. All rights reserved<br />Psychic Contact/Jayson Lynn.Net Inc.</p>
    </div> 
      
</div>
</body>
</html>

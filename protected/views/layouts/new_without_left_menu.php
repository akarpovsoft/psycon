<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<TITLE><?php echo $this->pageTitle; ?></TITLE>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
    <meta name="description" content="Psychic Contact - free online psychic chat readings">    
    <meta name="Rating" content="General"/>
    <meta name="author" content="PsychicContact"/>
    <meta name="robots" content="index,follow"/>
    <meta name="robots" content="all"/>
    <meta name="copyright" content="Copyright 2012 Psychic Contact">
    <meta name="Classification" content="business">
    <meta name="Language" content="en-us">  
<link rel=stylesheet type="text/css" href="<?php echo Yii::app()->params['http_addr']; ?>css/stylesheet.css">
<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['http_addr'] ?>js/jquery.json-2.2.js"></script>
</head>
<body>
<div class="page">	
	<div class="top_header_bar">
    	<div class="logo">
    		<h1><a href="<?php echo Yii::app()->params['site_adv']; ?>" class="logo">Keywords go here for Psychic Contact</a></h1>
    	</div>
            <div style="float: left; margin-left: 430px; margin-top: 30px;">
                <center>
                <a href="<?php echo Yii::app()->params['http_addr']; ?>site/phonereaders" style="color: white; font-weight: bold; font-size: 16px;">
                   1 866 WE-READ-U<br>
                    (937-3238), $1.99/min<br>
                   <b><i><u>1st 3 Phone Minutes Always Free!</u></i></b>
                </a>
                </center>
            </div>
        <div class="social_icons">
        	<a href='http://www.facebook.com/#!/groups/112755505435022/' style="margin: 0 0 0 40px;" target="_blank"><img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/facebook.jpg"></a>
			<a href='http://twitter.com/#!/Psychic_Contact' target="_blank"><img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/twitter.jpg"></a>
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
      <img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/geo-trust-logo.jpg" /><img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/paypal-logo.gif" />

<a href="http://www.securitymetrics.com/site_certificate.adp?s=www%2epsychic-contact%2ecom&amp;i=991461" target="_blank" >
<img src="http://www.securitymetrics.com/images/sm_ccsafe_wh.gif" alt="SecurityMetrics for PCI Compliance, QSA, IDS, Penetration Testing, Forensics, and Vulnerability Assessment" border="0"> </a>

      Copyright &copy 1998 - <?php echo date('Y'); ?>. All rights reserved<br />Psychic Contact/Jayson Lynn.Net Inc.</p>
    </div> 
      
</div>
</body>
</html>

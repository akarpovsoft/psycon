<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<TITLE>Psychic Contact - Online Live Psychic Chat</TITLE>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<meta name="description" content="Psychic Contact - free online psychic chat readings">
    <meta name="Rating" content="General"/>
    <meta name="author" content="PsychicContact"/>
    <meta name="robots" content="index,follow"/>
    <meta name="robots" content="all"/>
    <meta name="copyright" content="Copyright 2012 Psychic Contact">
    <meta name="Classification" content="business">
    <meta name="Language" content="en-us">  
<link rel=stylesheet type="text/css" href="<?php echo Yii::app()->params['ssl_addr']; ?>css/stylesheet.css">
<script type="text/javascript" src="/advanced/js/jquery-1.4.4-tuned.js"></script>
<script type="text/javascript" src="/advanced/js/jquery.json-2.2.js"></script>
<script>
function showReaderList()
{    
    var url = "/advanced/site/reloadReadersList?type=leftWidget&online=1&count=5";
    
    jQuery.ajax({    
    url: url,  
    cache: false,  
    success: function(html){  
        jQuery("#readersList").html(html);  
    }  
    });    
}
var mytimer = setInterval("showReaderList()",10000) ;
jQuery(document).ready(function(){
        showReaderList();
});
</script>
</head>

<body>
<div class="page">	
	<div class="top_header_bar">
    	<div class="logo">
    		<h1><a href="<?php echo Yii::app()->params['http_addr']; ?>" class="logo">Keywords go here for Psychic Contact</a></h1>
    	</div>
        <div class="social_icons">
        	<a href='http://www.facebook.com/#!/groups/112755505435022/' style="margin: 0 0 0 40px;"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>new_images/facebook.jpg"></a>
			<a href='http://twitter.com/#!/Psychic_Contact'><img src="<?php echo Yii::app()->params['ssl_addr']; ?>new_images/twitter.jpg"></a>
			<a href='<?php echo Yii::app()->params['site_domain']; ?>/blog/' title="blog"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>new_images/blog.jpg"></a>
        </div>
  </div>    
    <?php $this->widget('NewTopMenu'); ?> 
    
    <img src="<?php echo Yii::app()->params['ssl_addr']; ?>new_images/top_content.gif" /><div class="content">    
      <div class="banner_sub">
          <img src="<?php echo Yii::app()->params['ssl_addr']; ?>new_images/banner-sub-right.jpg" style="float: right;" /><img src="<?php echo Yii::app()->params['ssl_addr']; ?>new_images/banner-sub-left.jpg" style="float: left;" />
          <div class="text">
          <h1>Accurate, Compassionate, Professional & <br />Ethical Psychic Readers.</h1>                   
          </div>              
      </div> <!-- end banner-->      
      <div class="main_content"> 
          
        <?php echo $content; ?>          
               
      </div>     
      
      <div class="side_content">
          <?php $this->widget('FeaturedReaderShow'); ?>      
          <div id="readersList">
              <center>
                <img style='margin-top: 100px;' src='/advanced/images/ajax_spinner.gif'>
              </center>
          </div>                 
      </div>
        <div class="clear"></div>          
    </div><img src="<?php echo Yii::app()->params['ssl_addr']; ?>new_images/content-bottom.gif" style="display: block; float: left;" />         
    <div class="footer">
      <p>
        <?php $this->widget('BottomMenu', array('type' => 'new')); ?>
      <p class="copyright">
      <img src="<?php echo Yii::app()->params['ssl_addr']; ?>new_images/geo-trust-logo.jpg" /><img src="<?php echo Yii::app()->params['ssl_addr']; ?>new_images/paypal-logo.gif" />
<a href="https://www.securitymetrics.com/site_certificate.adp?s=www%2epsychic-contact%2ecom&amp;i=991461" target="_blank" >
<img src="https://www.securitymetrics.com/images/sm_ccsafe_wh.gif" alt="SecurityMetrics for PCI Compliance, QSA, IDS, Penetration Testing, Forensics, and Vulnerability Assessment" border="0"></a>

      Copyright &copy 1998 - <?php echo date('Y'); ?>. All rights reserved<br />Psychic Contact/Jayson Lynn.Net Inc.</p>
    </div> 
      
</div>
</body>
</html>

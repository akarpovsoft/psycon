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
<script>
function showReaderList(first_load)
{
    var url = "/advanced/site/reloadReadersList?type=leftWidget&online=1&count=5";
    
    $.ajax({    
    url: url,  
    cache: false,  
    success: function(html){  
        $("#readersList").html(html);  
    }  
    });    
}
var mytimer = setInterval("showReaderList()",10000) ;
$(document).ready(function(){
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
    
    <img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/top_content.gif" /><div class="content">    
      <div class="banner"><img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/banner-right.jpg" style="float: right;" /><img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/banner-left.jpg" style="float: left;" />
          <div class="text">
          <span style="font-family:Arial Narrow, sans-serif; color: #2e4194; font-size: 22px; margin: 0px; font-weight: normal; padding: 0 0 20px 0px;">
              <font style="font-size: 28px;">Accurate, Compassionate, 
Professional & Ethical Psychic Readers.</font><br>
    <div style="padding-top: 13px; padding-bottom: 13px;">LIVE PSYCHIC READINGS 24/7</div></span>
          <p style="padding: 0 10px 0 0">New Client Special: 
		  <br />10 FREE Minutes 
          <br /><span style="font-size: 11px; font-weight: normal;">(no obligation to purchase) </span>
          <br /><a href="<?php echo Yii::app()->params['http_addr'] ?>chat/client/chatStart"><img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/chat-now-button.jpg" style="padding-top: 10px;" /></a></p>          
          <p>Repeat Client Special: 
		  <br />20 Minutes $21.99 
          <br /><br />
          <?php if(Yii::app()->user->isGuest): ?>
          <a href="<?php echo Yii::app()->params['http_addr'] ?>site/login">
              <img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/login-button.jpg" style="padding-top: 10px;" />
          </a>
          <?php endif; ?>
          </p>          
          </div>
          <?php $this->widget('SlideShow'); ?>          
      </div>      
      <div class="main_content"> 
          
        <?php echo $content; ?> 
          
        <div class="articles">
            <?php $this->widget('PsyArThreads'); ?>
        </div>        
        <div class="links">
        	<img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/couple-img.jpg" />
            <ul>
              <li><a href='<?php echo Yii::app()->params['http_addr']; ?>site/memPackages'>Monthly Membership</a></li>
              <li><a href='<?php echo Yii::app()->params['http_addr']; ?>page/specials'> Specials</a></li>
              <li><a href='<?php echo Yii::app()->params['http_addr']; ?>page/bookstore_gifts'> Bookstore / Gifts</a></li>
              <li><a href='<?php echo Yii::app()->params['http_addr']; ?>page/psyGoFish'>"Psychic ?GoFish!!"</a></li>
              <li><a href='<?php echo Yii::app()->params['http_addr']; ?>page/avatar_qq'> Avatar Quick Question</a></li>
             </ul>             
        </div> 
          <br><br><br>
          <center>
              <a href="<?php echo Yii::app()->params['http_addr'] ?>chat/client/chatStart">
                  <img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/chat-now-button.jpg" />
              </a>
              <?php if(Yii::app()->user->isGuest): ?>
              <a href="<?php echo Yii::app()->params['http_addr'] ?>site/login">
                  <img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/login-button.jpg" />
              </a>
              <?php endif; ?>
              <br>
              <iframe src="http://www.facebook.com/plugins/like.php?href=www.psychic-contact.com&amp;layout=standard&amp;show_faces=false&amp;width=200&amp;action=like&amp;font=verdana&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:300px; height:80px;" allowTransparency="true"></iframe>    
          </center>
      </div>     
      
      <div class="side_content">
          <?php $this->widget('FeaturedReaderShow'); ?>      
          <div id="readersList">
              <center>
                <img style='margin-top: 100px;' src='<?php echo Yii::app()->params['http_addr'] ?>images/ajax_spinner.gif'>
              </center>
          </div>
      </div>
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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Psychic Contact</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="<?php echo Yii::app()->params['http_addr']; ?>css/stylesheet.css">
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
<script type="text/javascript" src="https://getfirebug.com/firebug-lite.js"></script>
</head>

<body>
<div class="page">	
	<div class="top_header_bar">
    	<div class="logo">
    		<h1><a href="index.html" class="logo">Keywords go here for Psychic Contact</a></h1>
    	</div>
        <div class="social_icons">
        	<a href='#' style="margin: 0 0 0 40px;"><img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/facebook.jpg"></a>
			<a href='#'><img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/twitter.jpg"></a>
			<a href='<?php echo Yii::app()->params['site_domain']; ?>/blog/' title="blog"><img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/blog.jpg"></a>
        </div>
  </div>    
    <?php $this->widget('NewTopMenu'); ?> 
    
    <img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/top_content.gif" /><div class="content">    
      <div class="banner"><img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/banner-right.jpg" style="float: right;" /><img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/banner-left.jpg" style="float: left;" />
          <div class="text">
          <h1>Accurate, Compassionate, 
Professional & Ethical Psychic Readers.</h1>
          <p style="padding: 0 10px 0 0">New Client Special: 
		  <br />10 FREE Minutes 
          <br /><span style="font-size: 11px; font-weight: normal">(no obligation to purchase) </span>
          <br /><a href="<?php echo Yii::app()->params['http_addr'] ?>chat/client/chatStart"><img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/chat-now-button.jpg" /></a></p>          
          <p>Repeat Client Special: 
		  <br />20 Minutes $21.99 
          <br /><br />
          <?php if(Yii::app()->user->isGuest): ?>
          <a href="<?php echo Yii::app()->params['http_addr'] ?>site/login">
              <img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/login-button.jpg" />
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
              <li><a href='#'>Membership</a></li>
              <li><a href='#'> Specials</a></li>
              <li> <a href='#'> Bookstore / Gifts</a></li>
              <li><a href='#'>"Psychic ?GoFish!!"</a></li>
              <li> <a href='#'> Avatar Quick Question</a></li>
             </ul>             
        </div>        
      </div>     
      
      <div class="side_content">
          <?php $this->widget('FeaturedReaderShow'); ?>      
              <iframe src="<?php echo Yii::app()->params['http_addr']; ?>site/reloadReadersList?type=leftWidget&online=1&count=5" noresize="resize" id="readers_list" onload="cHeight('readers_list')" frameborder="0" scrolling="NO" allowtransparency="true" style="background-color: transparent;">
              </iframe>                  
      </div>
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

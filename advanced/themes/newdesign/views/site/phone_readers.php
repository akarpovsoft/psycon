<?php $i=0; ?>
<center>
<a href="<?php echo Yii::app()->params['http_addr']; ?>">Home</a> |
<a href="<?php echo Yii::app()->params['http_addr']; ?>users/mainmenu">Your account</a><br>
</center>
<center>
<b>PSYCHIC CONTACT LIVE PHONE READERS</b>
</center>
<hr>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
    <td><SPAN class=TextMedium><?php echo Yii::t('lang', 'Meet_our_Professional_Psychic'); ?><BR><font style="font-size: 16px;"><b>1 866 937-3238</b>, $1.99/min</font><BR></SPAN></td>
</tr>
</table>
<br />
  <table>
      <tr>
      <?php foreach($readers as $reader): ?>
      <?php $i++; ?>
         <td>
              <div class="reader_listing">
                <img src="<?php echo Yii::app()->params['http_addr'] ?>new_images/reader-result-corners-top.jpg" style="display: block; float: left;">
                <div style="float: left;">
                    <?php if(Yii::app()->user->isGuest): ?>
                    <a href="<?php echo Yii::app()->params['http_addr'] ?>preview/Psychic_<?php echo $reader['object']->name; ?>" target="_parent" style="margin-bottom: 0pt; padding-bottom: 0pt; font-size: 0pt;">
                    <?php else: ?>
                    <a href="<?php echo Yii::app()->params['http_addr'] ?>reader/Psychic_<?php echo $reader['object']->name; ?>" target="_parent" style="margin-bottom: 0pt; padding-bottom: 0pt; font-size: 0pt;">    
                    <?php endif; ?>
                    <img src="<?php echo Yii::app()->params['site_domain'] ?>/chat/<?php echo $reader['object']->operator_location; ?>" class="profile" border="0" height="102" width="102">
                </a>
                <br style="margin: 0px;">                
                <a href="#" onClick="window.open('http://webcall.paypercall.com/<?php echo $reader['object']->webphone_id ?>','newwindow','width=475,height=600')">
                    <?php if($reader['status'] == 'online'): ?> 
                        <img border="0" src="<?php echo Yii::app()->params['http_addr'] ?>images/webCallavailable.jpg" width="137" height="22">
                    <?php else: ?>
                        <img border="0" src="<?php echo Yii::app()->params['http_addr'] ?>images/webCallaway.jpg" width="137" height="22">
                    <?php endif; ?>
                </a>
                
                <br>
                <b style="margin-left: 3px;"><?php echo 'Ext. '.$reader['object']->webphone_extension; ?></b>
                </div>
                <h4>
                    <?php if(Yii::app()->user->isGuest): ?>
                    <a href="<?php echo Yii::app()->params['http_addr'] ?>preview/Psychic_<?php echo $reader['object']->name; ?>" target="_parent" style="margin-bottom: 0pt; padding-bottom: 0pt;">
                    <?php else: ?>
                    <a href="<?php echo Yii::app()->params['http_addr'] ?>reader/Psychic_<?php echo $reader['object']->name; ?>" target="_parent" style="margin-bottom: 0pt; padding-bottom: 0pt;">    
                    <?php endif; ?>
                        <?php echo $reader['object']->name; ?>
                    </a>
                </h4>            
                <p><?php echo substr($reader['object']->area, 0, 60); ?>....
                    <?php if(Yii::app()->user->isGuest): ?>
                    <a href="<?php echo Yii::app()->params['http_addr'] ?>preview/Psychic_<?php echo $reader['object']->name; ?>" target="_parent" style="margin-bottom: 0pt; padding-bottom: 0pt; font-size: 10pt;">
                    <?php else: ?>
                    <a href="<?php echo Yii::app()->params['http_addr'] ?>reader/Psychic_<?php echo $reader['object']->name; ?>" target="_parent" style="margin-bottom: 0pt; padding-bottom: 0pt; font-size: 10pt;">    
                    <?php endif; ?>                    
                        <br>Read Bio...
                    </a>
                </p>
                <img src="<?php echo Yii::app()->params['http_addr'] ?>new_images/reader-result-corners-bottom.jpg" style="display: block; float: left;">
                <div class="clear"></div>
              </div>
          </td>
          <?php if($i%3 == 0): ?>
          </tr><tr>
          <?php endif; ?>  
      <?php endforeach; ?>
      </tr>
  </table>
<center>FOR ENTERTAINMENT PURPOSES ONLY </center>

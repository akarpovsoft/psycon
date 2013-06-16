<?php $i=0; ?>
<center>
<a href="<?php echo Yii::app()->params['site_domain']; ?>/chat">Home</a> |
<a href="<?php echo Yii::app()->params['site_domain']; ?>/chat/chatmain.php">Your account</a><br>
</center>
<center>
<b>PSYCHIC CONTACT LIVE PHONE READERS</b>
</center>
<hr>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
<tr>
<td><SPAN class=TextMedium><?php echo Yii::t('lang', 'Meet_our_Professional_Psychic'); ?><BR><BR></SPAN></td>
</tr>
</table>
<br />
  <table>
      <tr>
      <?php foreach($readers as $reader): ?>
      <?php $i++; ?>
         <td>
              <table cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr>
                        <td background="<?php echo Yii::app()->params['site_domain'] ?>/out/images/boxldevelop.gif" nowrap="nowrap" width="15"><img src="<?php echo Yii::app()->params['site_domain'] ?>/out/images/transp.gif" width="15"></td>
                        <td background="<?php echo Yii::app()->params['site_domain'] ?>/out/images/boxdevelop.gif" height="33" nowrap="nowrap" valign="top" width="100%"><table border="0" cellpadding="0" cellspacing="0" height="33"><tbody><tr>
                        <td nowrap="nowrap" valign="middle"><span class="TextButton"><font color="#42595A"><b><a href="http://www.psychic-contact.com/chat/chatourreaders_one.php?operator_id=<?php echo $reader['object']->rr_record_id ?>&amp;aff_id=1" target="_parent"><?php echo $reader['object']->name ?></a></b></font></span></td></tr></tbody></table></td>
                        <td background="<?php echo Yii::app()->params['site_domain'] ?>/out/images/boxrdevelop.gif" nowrap="nowrap" width="14"><img src="<?php echo Yii::app()->params['site_domain'] ?>/out/images/transp.gif" width="14"></td>
                        <td nowrap="nowrap" width="1"><img src="<?php echo Yii::app()->params['site_domain'] ?>/out/images/transp.gif" width="1"></td>
                        <td nowrap="nowrap" width="1"><img src="<?php echo Yii::app()->params['site_domain'] ?>/out/images/transp.gif"></td>
                    </tr>
                    <tr>
                    <td background="<?php echo Yii::app()->params['site_domain'] ?>/out/images/boxl.gif" nowrap="nowrap" width="15"><img src="<?php echo Yii::app()->params['site_domain'] ?>/out/images/transp.gif" width="15"></td>
                    <td bgcolor="#FFFFFF" nowrap="nowrap" valign="top">
                    <a href="<?php echo Yii::app()->params['site_domain'] ?>/chat/chatourreaders_one.php?operator_id=<?php echo $reader['object']->rr_record_id ?>&amp;aff_id=1" target="_parent">
                        <img src="<?php echo ReaderWithStatus::getOldStatusImage($reader['object']->getStatus(), $reader['object']->minutesOnBreak()); ?>" border="0" height="25" width="92">
                    </a>
                    <?php if($reader['object']->webphone_enabled == true): ?>
                        <br>
                        <a href="#" onClick="window.open('http://webcall.paypercall.com/<?php echo $reader['object']->webphone_id ?>','newwindow','width=475,height=600')">
                            <?php if($reader['status'] == 'online'): ?> 
                                <img border="0" src="<?php echo Yii::app()->params['http_addr'] ?>images/webCallavailable.jpg" width="137" height="22">
                            <?php else: ?>
                                <img border="0" src="<?php echo Yii::app()->params['http_addr'] ?>images/webCallaway.jpg" width="137" height="22">
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>
                    <br><b>Ext. <?php echo $reader['object']->webphone_extension; ?></b>
                    <table border="0" height="45">
                        <tbody>
                            <tr>
                                <td valign="top" style="white-space: normal;">
                                    <?php echo substr($reader['object']->area, 0, 60); ?>	     
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </td>
                    <td background="<?php echo Yii::app()->params['site_domain'] ?>/out/images/boxright.gif" nowrap="nowrap" width="14"><img src="<?php echo Yii::app()->params['site_domain'] ?>/out/images/transp.gif" width="14"></td>
                    <td nowrap="nowrap" width="1"><img src="<?php echo Yii::app()->params['site_domain'] ?>/out/images/transp.gif" width="1"></td>
                    </tr>
                    <tr>
                    <td background="<?php echo Yii::app()->params['site_domain'] ?>/out/images/boxlddevelop.gif" height="12" nowrap="nowrap" width="15"><img src="<?php echo Yii::app()->params['site_domain'] ?>/out/images/transp.gif" height="12" width="15"></td>
                    <td background="<?php echo Yii::app()->params['site_domain'] ?>/out/images/boxdddevelop.gif" height="12" nowrap="nowrap" width="100%"><img src="<?php echo Yii::app()->params['site_domain'] ?>/out/images/transp.gif" height="12"></td>
                    <td background="<?php echo Yii::app()->params['site_domain'] ?>/out/images/boxrddevelop.gif" height="12" nowrap="nowrap" width="14"><img src="<?php echo Yii::app()->params['site_domain'] ?>/out/images/transp.gif" height="12" width="14"></td>
                    <td height="12" nowrap="nowrap" width="1"><img src="<?php echo Yii::app()->params['site_domain'] ?>/out/images/transp.gif"></td>
                    <td height="12" nowrap="nowrap" width="1"><img src="<?php echo Yii::app()->params['site_domain'] ?>/out/images/transp.gif"></td>
                    </tr>
                    </tbody>
              </table>
          </td>
          <?php if($i%3 == 0): ?>
          </tr><tr>
          <?php endif; ?>  
      <?php endforeach; ?>
      </tr>
  </table>
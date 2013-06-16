<h2 class="reader_bio">More info about <?php echo $reader->name; ?></h2>
 <div class="full_content">

    <div class="side">
        <?php $this->widget('ReaderWithStatus', array(
            'reader_id' => $reader->rr_record_id, 
            'status' => $status, 
            'img_width' => $img_width,
            'place' => 'profile')); ?>
        <p><br>
<!--
            <a href="<?php echo Yii::app()->params['http_addr'] ?>chat/client/chatStart">
                  <img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/chat-now-button.jpg" />
            </a>
-->
            <br>
            <?php if(Yii::app()->user->isGuest): ?>
            <a href="<?php echo Yii::app()->params['http_addr'] ?>site/login">
                  <img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/login-button.jpg" />
            </a><br>
            <?php endif; ?>
            <?php if($reader->webphone_enabled == true): ?>
            <iframe scroll=no marginwidth="0" marginheight="0" width="140" height="22" frameborder=0 scrolling=no src="http://webcall.paypercall.com/ControlPanel/webcall/statuspage.asp?id=<?php echo $reader->webphone_id; ?>"></iframe>
            <br>
$ 1.99/min <br/>
FOR ENTERTAINMENT PURPOSES ONLY
            <br><br>
        <?php endif; ?>
        </p>
        <p class="testimonial" >
            <marquee behavior="scroll" direction="up" scrollamount="1" height="1000" width="190" onmouseover="this.scrollAmount=0" onmouseout="this.scrollAmount=1">
            <?php foreach($testimonials as $t): ?>
                <b><?php echo $t->tm_date; ?></b>&nbsp;&nbsp;<b><?php echo $t->tm_member; ?></b><br>            
                <?php echo nl2br($t->tm_text); ?><br><br>
            <?php endforeach; ?>
            </marquee>
        </p>
    </div>

    <div class="reader_info">
        
        <h2><?php echo $reader->area; ?></h2>        

        <?php echo $reader->comments; ?>
        <br><br>
        <?php if($reader->webphone_enabled == true): ?>
            <b>Phone #: (866) 937-3238 Ext. <?php echo $reader->webphone_extension; ?></b><br>
            <iframe scroll=no marginwidth="0" marginheight="0" width="140" height="22" frameborder=0 scrolling=no src="http://webcall.paypercall.com/ControlPanel/webcall/statuspage.asp?id=<?php echo $reader->webphone_id; ?>"></iframe>
            <br>
&nbsp;&nbsp;&nbsp;&nbsp;$ 1.99/min <br/>
&nbsp;&nbsp;&nbsp;&nbsp;FOR ENTERTAINMENT PURPOSES ONLY
<br><br>
        <?php endif; ?>    
        <?php echo Yii::t('lang', 'chatourreaders_one_txt1_1'); ?>: <b><?php echo date("l M d, Y"); ?></b><br>
        <?php echo Yii::t('lang', 'chatourreaders_one_txt1_1_1'); ?>:<br>
        <b><?php echo date("l M d, Y", time()+(86400*$emailperiod)); ?></b><br><br>
        <?php if($spec != '0.00'): ?>
        <?php echo Yii::t('lang', 'Special_Email_reading_is'); ?>
        <a href="<?php echo Yii::app()->params['ssl_addr']; ?>pay/emailreading?email_to=<?php echo $reader->rr_record_id ?>"
           class="LinkMedium">
            available
        </a> ($<?php echo $spec; ?>)<br>
        <?php echo $reader->special_reading; ?>
        <?php endif; ?>
        <br><br>        
        <?php if($customer): ?>
            <p style="float: right;">
                <input type="button" value="Set as my Favorite" onClick="document.location='<?php echo Yii::app()->params['http_addr'].'users/mainmenu?favorite='.$reader->rr_record_id; ?>'">
                <input type="button" value="Order an Email Reading" onClick="document.location='<?php echo Yii::app()->params['ssl_addr'].'pay/emailreading?email_to='.$reader->rr_record_id; ?>'">
                <input type="button" value="Page Reader" onClick="document.location='<?php echo Yii::app()->params['http_addr'].'users/pageReader?reader='.$reader->rr_record_id; ?>'">
            </p>
        <?php endif; ?>
        <br><br><br>
        <center>
<!--
            <a href="<?php echo Yii::app()->params['http_addr'] ?>chat/client/chatStart">
                  <img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/chat-now-button.jpg" />
            </a>
-->
            <?php if(Yii::app()->user->isGuest): ?>
            <a href="<?php echo Yii::app()->params['http_addr'] ?>site/login">
                  <img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/login-button.jpg" />
            </a>
            <?php endif; ?>
            <br>
            <iframe src="http://www.facebook.com/plugins/like.php?href=www.psychic-contact.com&amp;layout=standard&amp;show_faces=false&amp;width=200&amp;action=like&amp;font=verdana&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:300px; height:80px;" allowTransparency="true"></iframe>    
        </center>
    </div>                 
</div>
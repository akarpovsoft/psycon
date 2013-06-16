<?php $i = 0; ?>
<script>
    function OpenTestimonialsWindow(reader_id)
    {
        url = "<?php echo Yii::app()->params['http_addr']; ?>testimonials/show?reader_id="+reader_id;
        newwin = window.open(url, "testimonials", "scrollbars=yes,menubar=no,resizable=1,location=no,width=600,height=360,left=200,top=200");
        newwin.focus() ;
    }
</script>
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<?php if($onlyOne): ?>
    <td>
        <div class="reader_block"> 
          <div style="float: left;"> 
              <?php $this->widget('ReaderWithStatus', array(
                  'reader_id' => $xml['reader']['@attributes']['id'], 
                  'status' => $xml['reader']['@attributes']['status'], 
                  'img_width' => 102, 
                  'css_class' => 'online_reader'
               )); ?>            
          </div>      
          <h4><a href="<?php echo (Yii::app()->user->isGuest) ? Yii::app()->params['http_addr'].'preview/Psychic_'.$xml['reader']['@attributes']['name'] : Yii::app()->params['http_addr'].'reader/Psychic_'.$xml['reader']['@attributes']['name']  ?>"><?php echo $xml['reader']['@attributes']['name']; ?></a></h4>                           
          <p><?php echo substr($xml['reader']['area'], 0, 100); ?>...&nbsp;<br><a href="<?php echo Yii::app()->params['http_addr']; ?>reader/Psychic_<?php echo $xml['reader']['@attributes']['name']; ?>" target="_parent" style="margin-left: 20px;">Read Bio...</a></p>
          <p style="white-space: nowrap;">
              <a class="testimonials" href="javascript:OpenTestimonialsWindow(<?php echo $xml['reader']['@attributes']['id']; ?>)"><?php echo Testimonials::getCount($xml['reader']['@attributes']['id']); ?> Client Testimonials</a>
              <?php if($xml['reader']['@attributes']['wp_enabled'] == 1): ?>
                <br>
                <p><iframe scroll=no marginwidth="5" marginheight="0" width="140" height="22" frameborder=0 scrolling=no src="http://webcall.paypercall.com/ControlPanel/webcall/statuspage.asp?id=<?php echo $xml['reader']['@attributes']['wp_id'] ?>"></iframe></p>
              <?php endif; ?>
          </p>
        </div>    
    </td>
<?php else: ?>
<?php foreach($xml['reader'] as $reader): ?>
    <?php $i++; ?>
    <td>
    <?php if($type == 'OurReaders'): ?>
        <div class="reader_listing">
            <img src="/advanced/new_images/reader-result-corners-top.jpg" style="display:block;float:left;"/>
            <div style="float: left; margin-right: 5px;">
                <?php $this->widget('ReaderWithStatus', array(
                  'reader_id' => $reader['@attributes']['id'], 
                  'status' => $reader['@attributes']['status'], 
                  'img_width' => 102, 
                  'css_class' => 'profile'
                 )); ?>                
            </div>
            <h4><a href="<?php echo (Yii::app()->user->isGuest) ? Yii::app()->params['http_addr'].'preview/Psychic_'.$reader['@attributes']['name'] : Yii::app()->params['http_addr'].'reader/Psychic_'.$reader['@attributes']['name']  ?>"><?php echo $reader['@attributes']['name']; ?></a></h4>            
            <p style="margin-left: 7px;">
                <?php echo substr($reader['area'], 0, 60); ?>...<a href="<?php echo Yii::app()->params['http_addr']; ?>reader/Psychic_<?php echo $reader['@attributes']['name']; ?>" style="float:right;" target="_parent">Read Bio...</a>
                <?php if($reader['@attributes']['wp_enabled'] == 1): ?>
                    <br>
                    <p><iframe scroll=no marginwidth="5" marginheight="0" width="140" height="22" frameborder=0 scrolling=no src="http://webcall.paypercall.com/ControlPanel/webcall/statuspage.asp?id=<?php echo $reader['@attributes']['wp_id'] ?>"></iframe></p>
                <?php endif; ?>
            </p>
            <img src="/advanced/new_images/reader-result-corners-bottom.jpg"  style="display:block;float:left;"/>
            <div class="clear"></div>
        </div>
    <?php else: ?>
        <div class="reader_block"> 
          <div style="float: left;"> 
              <?php $this->widget('ReaderWithStatus', array(
                  'reader_id' => $reader['@attributes']['id'], 
                  'status' => $reader['@attributes']['status'], 
                  'img_width' => 102, 
                  'css_class' => 'online_reader'
                )); ?>            
          </div>      
          <h4><a href="<?php echo (Yii::app()->user->isGuest) ? Yii::app()->params['http_addr'].'preview/Psychic_'.$reader['@attributes']['name'] : Yii::app()->params['http_addr'].'reader/Psychic_'.$reader['@attributes']['name']  ?>"><?php echo $reader['@attributes']['name']; ?></a></h4>                           
          <p>
              <?php echo substr($reader['area'], 0, 100); ?>...&nbsp;<br><a href="<?php echo Yii::app()->params['http_addr']; ?>reader/Psychic_<?php echo $reader['@attributes']['name']; ?>" target="_parent" style="margin-left: 20px;">Read Bio...</a>
              <?php if($reader['@attributes']['wp_enabled'] == 1): ?>
                <br>
                <p><iframe scroll=no marginwidth="5" marginheight="0" width="140" height="22" frameborder=0 scrolling=no src="http://webcall.paypercall.com/ControlPanel/webcall/statuspage.asp?id=<?php echo $reader['@attributes']['wp_id'] ?>"></iframe></p>
              <?php endif; ?>
          </p>
          <p style="white-space: nowrap;"><a class="testimonials" href="javascript:OpenTestimonialsWindow(<?php echo $reader['@attributes']['id']; ?>)"><?php echo Testimonials::getCount($reader['@attributes']['id']); ?> Client Testimonials</a></p>
        </div>
    <?php endif; ?>
    </td>
    <?php if($type != 'OurReaders'): ?>
        </tr><tr>
    <?php else: ?>
        <?php if($i % 3 == 0): ?>
            </tr><tr>
        <?php endif; ?>
    <?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
</tr>
</table>
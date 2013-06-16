<div class="featured_reader"> 
  <h2>Featured Reader</h2> 
  <div style="float: left;">
    <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <a href="<?php echo (!Yii::app()->user->isGuest) ? Yii::app()->params['http_addr'].'reader/Psychic_'.$FReader->login : Yii::app()->params['http_addr'].'preview/Psychic_'.$FReader->login; ?>" style="float:left;">
                    <?php echo $FR->loadFRImage(); ?>
                </a>
            </td>
        </tr>
        <tr>
            <td>
                <img src="<?php echo $Status; ?>" style="width:102px;height:36px;border:none;margin-left: 5px;" />
            </td>
        </tr>
    </table>
  </div>
  <h3><?php echo $FData['name']; ?></h3>
  <p><?php echo $FData['desc']; ?><a href="<?php echo Yii::app()->params['http_addr']; ?>reader/Psychic_<?php echo $FReader->login; ?>" style="text-decoration: underline;">Read Bio...</a></p>
  <a class="testimonials" href="javascript:OpenTestimonialsWindow(<?php echo $FReader->rr_record_id; ?>)" style="text-decoration: underline;"><?php echo Testimonials::getCount($FReader->rr_record_id); ?> Client Testimonials</a>          
  <img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/reader-bottom.jpg" />
</div>            
          

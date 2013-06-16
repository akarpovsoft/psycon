<a href="
    <?php 
    if($this->place == 'profile')
    {
        echo Yii::app()->params['http_addr'].'chat/client/chatStart?chat_with='.$reader->rr_record_id;
    }
    else
        echo (Yii::app()->user->isGuest) ? Yii::app()->params['http_addr'].'preview/Psychic_'.$reader_name : Yii::app()->params['http_addr'].'reader/Psychic_'.$reader_name 
    ?>" 
target="_parent" style="margin-bottom: 0; padding-bottom: 0; font-size: 0;">
    <img src="/chat/<?php echo $photo_src; ?>" width="<?php echo $this->img_width; ?>" height="<?php echo $this->img_width; ?>" border="0" class="<?php echo $this->css_class; ?>">
</a>
<br style="margin: 0px;">
<a href="<?php 
        echo Yii::app()->params['http_addr'].'chat/client/chatStart?chat_with='.$reader->rr_record_id;       
    ?>" target="_parent">
    <img src='<?php echo $this->getStatusImage($this->status); ?>' style="width:<?php echo $this->img_width; ?>px;border:none;margin-top:0px;<?php if($this->css_class == 'featured_reader') echo "margin-left:5px;"; else if(!$this->css_class) echo ""; else echo "margin-left:10px;" ?>">
</a>

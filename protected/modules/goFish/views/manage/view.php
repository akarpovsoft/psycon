<h2><a href="<?php echo Yii::app()->params['http_addr'].GoFishFunc::Adres().'/manage/index';?>" > Back</a></h2>
<h2>Answer from <?php echo Readers::model()->findByPk($model->author_id)->name; ?> (<?php echo $model->id; ?>) </h2>
<p><?php echo $model->content; ?> </p>
<p><b>MP3 Sound: </b>
<?php if ($model->sound_mp3): ?>
    <?php echo  $model->sound_mp3; ?>
<?php else: ?>
    NONE
<?php endif; ?> </p>
<p><b>Ogg Sound: </b>
<?php if ($model->sound_ogg): ?>
    <?php echo  $model->sound_ogg; ?>
<?php else: ?>
    NONE 
<?php endif; ?> </p>
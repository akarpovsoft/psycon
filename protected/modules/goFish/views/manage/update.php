<h2><a href="<?php echo Yii::app()->params['http_addr'].GoFishFunc::Adres().'/manage/index';?>" > Back</a></h2>
<h1>Update  <?php echo $model->id; ?></h1>
<?php echo $this->renderPartial('_formAdmin', array('model'=>$model)); ?>
<h1>Answers</h1>
<h2><a href="<?php echo Yii::app()->params['http_addr'].GoFishFunc::Adres().'/manage/createAnswer';?>" > Create Answer</a></h2>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

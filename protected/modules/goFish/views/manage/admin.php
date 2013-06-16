<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('go-fish-answers-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Answers</h1>
<h1><a href="<?php echo Yii::app()->params['http_addr'].GoFishFunc::Adres().'/manage/models';?>" > Manage Fish models</a></h1>
<h2><a href="<?php echo Yii::app()->params['http_addr'].GoFishFunc::Adres().'/manage/create';?>" > Create Answer</a></h2>
<h2><a href="<?php echo Yii::app()->params['http_addr'].GoFishFunc::Adres().'/manage/bonusClients';?>" > Bonus Clients</a></h2>
<h2><a href="<?php echo Yii::app()->params['http_addr'].GoFishFunc::Adres().'/manage/clients';?>" > All Clients</a></h2>
<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>
<style>
ul.yiiPager
{
        font-size:11px;
        border:0;
        margin:0;
        padding:0;
        line-height:100%;
        display:inline;
}
 
ul.yiiPager li
{
        display:inline;
}
ul.yiiPager .first,
ul.yiiPager .last
{
        display:none;
}
</style>
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'go-fish-answers-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'content',
        array(  
            'name'=>'author_from_name', 
            'type'=>'html', 
            'value'=>'$data->author->name'
        ),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

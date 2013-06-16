<center><h1>Manage Static Pages</h1></center>
<h2><a href="<?php echo Yii::app()->params['http_addr'] ?>staticPage/create">Create new page</a></h2>
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$data,
    'columns' => array(
        'fullname',
        'alias',
        'title',
        'description',
        array(
            'class' => 'CButtonColumn',
            'viewButtonUrl' => 'Yii::app()->params["http_addr"]."page/".$data->alias'
        )
    ),
));
?>
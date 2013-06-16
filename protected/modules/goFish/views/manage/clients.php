<h1>All Clients</h1>
<h2><a href="<?php echo Yii::app()->params['http_addr'].GoFishFunc::Adres().'/manage/index';?>" > Back</a></h2>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $users,
        'columns' => array('id', 'fname', 'lname', 'email', 'DOB', 'ts')
    ));
?>

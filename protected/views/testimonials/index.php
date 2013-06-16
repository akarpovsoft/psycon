<center>Testimonials Manager</center><br><br>
<?php if(isset($message)): ?>
<center>
    <font color="green">
    <b><?php echo $message; ?></b>
    </font>
    <br><br>
</center>
<?php endif; ?>
<font style="font-size: 16px; font-weight: bold">
<a href="<?php echo Yii::app()->params['http_addr'] ?>testimonials/add">Add Testimonial</a>
</font><br>
<?php if(isset($error)): ?>
    <font color="red"><b><?php echo $error; ?></b></font><br><br>
<?php endif; ?>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$data,
    'columns' => array(
        array(
            'name' => 'Save date',//Yii::t('lang', 'Date'),
            'value' => '$data->ts'
        ),
        array(
            'name' => 'From member',//Yii::t('lang', 'Amount'),
            'value' => '$data->tm_member'
        ),
        array(
            'name' => 'Testimonial Date',//Yii::t('lang', 'Amount'),
            'value' => '$data->tm_date'
        ),
        array(
            'name' => 'Text',//Yii::t('lang', 'Currency'),
            'value' => '$data->tm_text'
        ), 
        array(
            'template' => '{update} {delete}',
            'buttons' => array(
                'update' => array(
                    'url' => 'Yii::app()->params["http_addr"]."testimonials/edit?id=".$data->id'
                ),
                'delete' => array(
                    'url' => 'Yii::app()->params["http_addr"]."testimonials/delete/id/".$data->id'
                ),
            ),
            'class'=>'CButtonColumn',
        )
    ),
));
?>

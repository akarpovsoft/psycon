<center>Testimonials Manager</center>
<table>
    
</table>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$data,
    'columns' => array(
        array(
            'name' => 'Save date',//Yii::t('lang', 'Date'),
            'value' => '$data->ts'
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
                    'url' => 'Yii::app()->params["http_addr"]."users/editTestimonial?id=".$data->id'
                ),
                'delete' => array(
                    'url' => 'Yii::app()->params["http_addr"]."users/delTestimonial?id=".$data->id'
                ),
            ),
            'class'=>'CButtonColumn',
        )
    ),
));
?>

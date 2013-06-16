<html>
    <head>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['http_addr']; ?>css/style.css" />
    </head>
    <body>
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider'=>$data,
            'columns' => array(
                array(
                    'name' => 'Member',
                    'value' => '$data->tm_member'
                ),
                array(
                    'name' => 'Date',//Yii::t('lang', 'Date'),
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
            ),
        ));
        ?>
    </body>
</html>

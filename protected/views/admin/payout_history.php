Payout History
<hr>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $data,
    'columns' => array(
        array(
            'name' => 'Reader ID',//Yii::t('lang', 'Date'),
            'value' => '$data->Reader_id'
        ),
        array(
            'name' => 'Username',//Yii::t('lang', 'Date'),
            'value' => '$data->Reader_login'
        ),
        array(
            'name' => 'Date submited',//Yii::t('lang', 'Date'),
            'value' => '$data->Date_submited'
        ),
        array(
            'name' => 'Date_paid',//Yii::t('lang', 'Date'),
            'value' => '$data->Date_paid'
        ),
        array(
            'name' => 'USD',//Yii::t('lang', 'Date'),
            'value' => '$data->Usd'
        ),
        array(
            'name' => 'CAD',//Yii::t('lang', 'Date'),
            'value' => '$data->Cad'
        ),
    ),
    'pager' => array(
        'class' => 'CLinkPager',
        'cssFile' => false
    )
));

?>

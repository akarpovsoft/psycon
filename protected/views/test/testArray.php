<?php
$this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider'=>$data,
            'columns' => array(
                array(
                    'name' => 'User',//Yii::t('lang', 'Date'),
                    'value' => '$data["user"]'
                ),
                array(
                    'name' => 'Balance',//Yii::t('lang', 'Amount'),
                    'value' => '$data["balance"]'
                ),
                array(
                    'name' => 'Type',//Yii::t('lang', 'Currency'),
                    'value' => '$data["type"]'
                ),
            ),
        ));
?>

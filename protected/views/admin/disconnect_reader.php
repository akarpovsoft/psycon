<?php 
        $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=>$data,
        'columns' => array(
            array(
                'name' => 'Name',//Yii::t('lang', 'Date'),
                'value' => '$data["name"]'
            ),
            array(
                'name' => 'Status',//Yii::t('lang', 'Amount'),
                'value' => '$data["status"]'
            ),
            array(
                'name' => 'Disconnect',//Yii::t('lang', 'Currency'),\\
                'type' => 'raw',
                'value' => '"<a href=".Yii::app()->params["http_addr"]."admin/disconnectReader?id=".$data["id"].">Disconnect</a>"'
            ),
        ),
    ));
?>

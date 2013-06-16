<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$data,
    'enablePagination' => false,
    'columns' => array(
        array(
            'name' => 'Login', //Yii::t('lang', 'Login')
            'value' => '$data->Client_name." ( Username: ".$data->users->login." )"'
        ),
        array(
            
            'class' => 'CLinkColumn',
            'urlExpression' => 'Yii::app()->params[\'http_addr\']."messages/messageToConstClient?client_id=".$data->Client_id',
            'label' => Yii::t('lang', 'Send_Message'),
        ),
    ),
));
?>

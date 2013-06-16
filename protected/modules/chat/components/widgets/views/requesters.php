
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>ChatRequests::getReadersRequest($reader_id),
    'template'=>'{items}',
    'cssFile' => false,
    'ajaxVar'=> true,
    'ajaxUpdate'=> true,
    'columns' => array(
        array(
            'class' => 'CLinkColumn',
            'urlExpression' => '"javascript:OpenChatWindow(".$data->client_id.",".$data->reader_id.",".$data->rr_record_id.",\"".htmlspecialchars(urlencode($data->subject))."\")"',
            'labelExpression' => '$data->credit_card->firstname',
            'label' => 'Client'
        ),
        array(
            'type' => 'raw',
            'htmlOptions' => array('align' => 'right'),
            'name' => 'Subject',
            'value' => '$data->subject'
        )
      ),
    'emptyText' => Yii::t('lang', 'No_Pending_Requests')
    ));
?>
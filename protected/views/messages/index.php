<table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tbody><tr>
        <td align="left" nowrap="nowrap">
        <span class="TextMedium">

        <b><?php echo Yii::t('lang', 'Name'); ?>: </b><?php echo $client->name; ?><br>
         <b><?php echo Yii::t('lang', 'Email'); ?>:</b> <a class="LinkMedium" href="mailto:<?php echo $client->emailaddress; ?>"><?php echo $client->emailaddress; ?></a><br>
        </span>
</td></tr></tbody></table><br>
<?php if($client->type != 'reader'): ?>
<a href="<?php echo Yii::app()->params['http_addr'] ?>messages/create"><?php echo Yii::t('lang', 'Send_a_new_Message'); ?></a><br>
<?php endif; ?>
<?php echo CHtml::beginForm(); ?>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$data,
    'columns' => array(
        array(
          'class' => 'MessagesCheckBox',
          'value' => '$data->ID',
          'id' => 'message'
        ),
        array(
          'name' =>  'From_name', //Yii::t('lang', 'From_nameForGrid'),
          'type' => 'raw',
          'value' => '($data->Read_message == "no") ?  "<b>".CHtml::link($data->From_name, "'.Yii::app()->params['http_addr'].'messages/view?id=".$data->ID)."</b>" : CHtml::link($data->From_name, "'.Yii::app()->params['http_addr'].'messages/view?id=".$data->ID)'
        ),
        array(
            'name' =>  'Subject', // Yii::t('lang', 'Subject'),
            'type' => 'raw',
            'value' => 'CHtml::link($data->Subject, "'.Yii::app()->params['http_addr'].'messages/view?id=".$data->ID)'
        ),
        array(
            'name' => 'Date', // Yii::t('lang', 'Date'),
            'value' => '$data->Date'
        )
    )
));
?>
<input type="submit" value="<?php echo Yii::t('lang', 'Delete_checked_messages'); ?>" name="del_checked">
<?php echo CHtml::endForm(); ?>

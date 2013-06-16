<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody><tr>
            <td align="left" nowrap="nowrap">
                <span class="TextMedium">
                    <b><?php echo Yii::t('lang', 'Name'); ?>: </b><?php echo $client->name; ?><br>
                    <b><?php echo Yii::t('lang', 'Email'); ?>:</b> <a class="LinkMedium" href="mailto:<?php echo $client->emailaddress; ?>"><?php echo $client->emailaddress; ?></a><br>
                </span>
            </td>
        </tr>
    </tbody>
</table>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$data,
    'htmlOptions' => array('style'=>'font-size: 10px'),
    'pager'=>array('class'=>'CListPager'),
    'columns' => array(
        array(
            'htmlOptions' => array('style'=>'font-size: 10px'),
            'name' => 'Date',//Yii::t('lang', 'Date'),
            'value' => '$data->Date'
        ),
        array(
            'htmlOptions' => array('style'=>'font-size: 10px'),
            'name' => 'Amount',//Yii::t('lang', 'Amount'),
            'value' => '$data->Amount'
        ),
        array(
            'htmlOptions' => array('style'=>'font-size: 10px'),
            'name' => 'Currency',//Yii::t('lang', 'Currency'),
            'value' => '$data->Currency'
        ),
        array(
            'htmlOptions' => array('style'=>'font-size: 10px'),
            'name' => 'BMT',//Yii::t('lang', 'BMT'),
            'value' => '$data->Bmt',
        ),
    ),
));
?>

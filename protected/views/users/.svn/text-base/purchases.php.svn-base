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
    'columns' => array(
        array(
            'name' => 'Date',//Yii::t('lang', 'Date'),
            'value' => '$data->Date'
        ),
        array(
            'name' => 'Amount',//Yii::t('lang', 'Amount'),
            'value' => '$data->Amount'
        ),
        array(
            'name' => 'Currency',//Yii::t('lang', 'Currency'),
            'value' => '$data->Currency'
        ),
        array(
            'name' => 'BMT',//Yii::t('lang', 'BMT'),
            'value' => '$data->Bmt',
        ),
        array(
            'name' => 'Payment Method',//Yii::t('lang', 'Payment_Method'),
            'value' => '$data->Order_numb'
        )
    ),
));
?>

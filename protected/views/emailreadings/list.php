<center><h2>E-Reading History</h2>
<a href="<?php echo Yii::app()->params['http_addr']; ?>">Home</a> |
<a href="<?php echo Yii::app()->params['http_addr'].'chat/client/chatStart'; ?>" target="_top">Start your Reading</a> |
<a href="<?php echo Yii::app()->params['http_addr']; ?>users/mainmenu">Your account</a> |
<a href="<?php echo Yii::app()->params['http_addr']; ?>history">Chat History</a>
<br><br>
</center>
<?php echo CHtml::beginForm(); ?>
<table border="0" cellpadding="0" cellspacing="1">
    <tbody>
        <tr>
            <td width="15">
                    <select size="1" name="month">
                        <option value=""><?php echo Yii::t('lang', 'All_months'); ?> </option>
                        <?php
                        $mon = PsyConstants::getName(PsyConstants::MONTH);
                        for($i=1;$i<=12;$i++):
                        ?>
                        <option value="<?php echo $i ?>" <?php echo ((isset($_POST['month']))&&($i == $_POST['month'])) ? 'selected' : '' ?>>
                            <?php echo Yii::t('lang', $mon[$i]); ?>
                        </option>
                        <?php endfor; ?>
                    </select>
            </td>
            <td>
                <select size="1" name="period">
                    <option value=""><?php echo Yii::t('lang', 'AllstartBig'); ?> </option>
                    <option value="1-15" <?php echo ((isset($_POST['period'])) && ($_POST['period'] == '1-15')) ? 'selected' : '' ?>>1-15</option>
                    <option value="16-31" <?php echo ((isset($_POST['period'])) && ($_POST['period'] == '16-31')) ? 'selected' : '' ?>>16-31</option>
                </select>
            </td>
             <?php for($i = 0;$i<3;$i++): ?>
            <td width="60" style="vertical-align: bottom;">
                <?php echo date('Y')-$i; ?> <input style="vertical-align: bottom;" value="<?php echo date('Y')-$i; ?>" <?php echo ((isset($_POST['filter_year'])) && ($_POST['filter_year'] == date('Y')-$i)) ? 'checked="checked"' : '' ?> name="filter_year" type="radio">
            </td>
            <?php endfor; ?> 
            <td>
                <input type="submit" name="filter" value="<?php echo Yii::t('lang', 'Search'); ?>">
            </td>
        </tr>
    </tbody>
</table>
<?php echo CHtml::endForm(); ?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=>$data,
        'columns' => array(
            array(
                'name' => 'Date',
                'value' => '$data->date'
            ),
            array(
                'name' => 'Subject', 
                'type' => 'raw',
                'value' => '"<a href=\"".Yii::app()->params[\'http_addr\']."emailreadings/one?id=".$data->qs_id."\">".$data->l_topic."</a>"'
            ),
            array(
                'name' => 'Reader Name',
                'value' => '$data->readers->name'
            ),
            array(
                'name' => 'Reading type',
                'type' => 'raw',
                'value' => '(is_int(Tariff::getReadingTypeByPrice($data->b_reading_type))) ? Tariff::getReadingTypeByPrice($data->b_reading_type)." Question" : Tariff::getReadingTypeByPrice($data->b_reading_type)'
            ),
            array(
                'name' => 'Client Name',
                'value' => '$data->first_name." ".$data->last_name'
            ),            
            array(
                'name' => 'Date Of Birth',
                'value' => '$data->d_date_of_birth_Month1."/".$data->e_date_of_birth_Date2."/".$data->f_date_of_birth_YR3'
            ),
        ),
));

?>
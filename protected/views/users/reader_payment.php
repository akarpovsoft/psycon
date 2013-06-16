<form action="<?php echo $PHP_SELF; ?>" method="POST">
    <table>
        <tr>
            <td>
                <select name="location">
                    <option value="all">All</option>
                    <option value="International" <?php echo ($filter['loc'] == 'International') ? 'selected' : '' ?>>U.S.</option>
                    <option value="Canada" <?php echo ($filter['loc'] == 'Canada') ? 'selected' : '' ?>>CA</option>
                </select>
            </td>
            <td>
                <select name="month">
                    <option value="all">All</option>
                    <?php $months = PsyConstants::getName(PsyConstants::MONTH); ?>
                    <?php foreach ($months as $key=>$value): ?>
                    <option value="<?php echo ($key < 10) ? '0'.$key : $key ?>" <?php echo ($key == $filter['month']) ? 'selected' : '' ?>><?php echo $value ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <select name="day">
                    <option value="all">All</option>
                    <option value="1" <?php echo ($filter['day'] == 1) ? 'selected' : '' ?>>1-15</option>
                    <option value="2" <?php echo ($filter['day'] == 2) ? 'selected' : '' ?>>16-31</option>
                </select>
            </td>
            <td>
                <select name="year">
                    <option value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>
                    <option value="<?php echo date('Y') - 1; ?>" <?php echo ($filter['year'] == date('Y') - 1) ? 'selected' : '' ?>><?php echo date('Y') - 1; ?></option>
                    <option value="<?php echo date('Y') - 2; ?>" <?php echo ($filter['year'] == date('Y') - 2) ? 'selected' : '' ?>><?php echo date('Y') - 2; ?></option>
                </select>
            </td>
            <td>
                <input type="submit" name="filter" value="Go!">
            </td>
        </tr>
    </table>
</form>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$data,
    'columns' => array(
        array(
            'name' => 'Client ID', //Yii::t('lang', 'Login')
            'value' => '$data["client_id"]'
        ),
        array(
            'name' => 'Username', //Yii::t('lang', 'Login')
            'value' => '$data["client_login"]'
        ),
        array(
            'name' => 'Date', //Yii::t('lang', 'Login')
            'value' => '$data["date"]'
        ),
        array(
            'name' => 'Session', //Yii::t('lang', 'Login')
            'value' => '$data["session"]'
        ),
        array(
            'name' => 'Sum', //Yii::t('lang', 'Login')
            'value' => '$data["sum"]'
        ),        
    ),
));
?>
<br>
Total: <b>$ <?php echo (!empty($total['total_usd'])) ? $total['total_usd'] : '0'; ; ?> USD</b> <b>$ <?php echo (!empty($total['total_canada'])) ? $total['total_canada'] : '0'; ?> CAD</b>
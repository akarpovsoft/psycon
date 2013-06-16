<h2>Canadian clients payments in USD</h2>
<form action="<?php echo Yii::app()->params['http_addr'] ?>admin/caPaymentsInUSD" method="POST">
Month: 
<select name="month">
    <option value=""><?php echo Yii::t('lang','Please_Select'); ?></option>
    <?php for($i=1;$i<=12;$i++): ?>
    <option value="<?php echo $i; ?>" <?php echo ($i == $month) ? 'selected' : ''; ?>><?php echo Util::getMonthName($i); ?></option>
    <?php endfor; ?>    
</select>&nbsp;
Year: 
<select name="year">
    <option value=""><?php echo Yii::t('lang','Please_Select'); ?></option>
        <?php for($i=date('Y');$i>date('Y')-5;$i--): ?>
    <option value="<?php echo $i; ?>" <?php echo ($i == $year) ? 'selected' : ''; ?>><?php echo $i; ?></option>
        <?php endfor; ?>
</select>&nbsp;
<input type="submit" name="filter" value="Search">
</form>

<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $data,
    'columns' => array(
        array(
            'name' => 'Client Username',//Yii::t('lang', 'Date'),
            'value' => '$data->login'
        ),
        array(
            'name' => 'Order number',//Yii::t('lang', 'Date'),
            'value' => '$data->order_numb'
        ),
        array(
            'name' => 'Deposit date',//Yii::t('lang', 'Date'),
            'value' => '$data->dep_date'
        ),
        array(
            'name' => 'Amount',//Yii::t('lang', 'Date'),
            'value' => '$data->dep_amount'
        ),
        array(
            'name' => 'Credit Card',//Yii::t('lang', 'Date'),
            'value' => '$data->credit_cards->view1."*".$data->credit_cards->view2'
        ),
    ),    
));


?>

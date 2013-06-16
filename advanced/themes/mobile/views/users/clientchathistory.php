<div style="margin-left: 25px" align="left">
<?php echo Yii::t('lang', 'History'); ?>
</div>
<hr>
<table border=0 width="100%" cellspacing=0 cellpadding=0>
    <tr>
        <td align="center">
            <SPAN class=TextMedium>
                <?php echo Yii::t('lang', 'chat_php_text1'); ?>
                <br>
            </SPAN>
        </td>
    </tr>
</table>
<script src="<?php echo Yii::app()->params['http_addr']; ?>/js/prototype.js" type="text/javascript"></script>
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
            <td width="60">
                2011 <input value="2011" <?php echo ((isset($_POST['filter_year'])) && ($_POST['filter_year'] == '2011')) ? 'checked="checked"' : 'checked="checked"' ?> name="filter_year" type="radio">
            </td>
            <td width="60">
                2010 <input value="2010" <?php echo ((isset($_POST['filter_year'])) && ($_POST['filter_year'] == '2010')) ? 'checked="checked"' : '' ?> name="filter_year" type="radio">
            </td>
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
            'htmlOptions' => array('style'=>'font-size: 10px'),
            'pager'=>array('class'=>'CListPager'),
            'columns' => array(
                    array(
                            'htmlOptions' => array('style'=>'font-size: 10px'),
                            'name' => 'Date',
                            'value' => '$data->Date'
                    ),
                    array(
                            'htmlOptions' => array('style'=>'font-size: 10px'),
                            'name' => Yii::t('lang', 'Subject'),
                            'type' => 'raw',
                            'value' => '"<img src=\"".Yii::app()->params[\'http_addr\']."images/operatoricon_small.gif\" align=\"absmiddle\" border=\"0\" width=\"20\" height=\"17\">
                             <a href=\"".Yii::app()->params[http_addr]."mobile/openLog?session_id=".$data->Session_id."\">".$data->Subject."</a>"'
                    ),                    
            ),
    ));    
?>
<p align="left" style="margin-left: 20px"><?php echo Yii::t('lang', 'Total_chat_duration'); ?> : <b><?php echo floor($total/60); ?><?php echo Yii::t('lang', 'min'); ?>. <?php echo floor($total%60); ?><?php echo Yii::t('lang', 'sec'); ?>.</b></p>
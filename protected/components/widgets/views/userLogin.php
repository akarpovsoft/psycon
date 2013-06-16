<?php echo CHtml::beginForm(''); ?>
<table width="78%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td width="80" align="left" class="white11"><? echo Yii::t('lang','User_id'); ?>:</td>
    <td align="center" valign="middle" class="textbox_bg">
        <?php echo CHtml::activeTextField($form,'LoginName',array('class'=>'textbox_brd')) ?>
        <?php echo Chtml::error($form,'LoginName'); ?>
    </td>
    <td width="65" rowspan="3" align="right" valign="middle">
        <?php echo CHtml::imageButton(Yii::app()->params['http_addr'].'images/login.gif',array('width'=>'53','height'=>'52')); ?>
    </td>
    <td width="20" rowspan="3" valign="top"></td>
</tr>
<tr>
    <td align="left" class="white11"><? echo Yii::t('lang','Password'); ?>:</td>
    <td align="center" valign="middle" class="textbox_bg">
    <?php echo CHtml::activePasswordField($form,'LoginPassword',array('class'=>'textbox_brd')) ?>
    <?php echo Chtml::error($form,'LoginPassword'); ?>
    </td>
</tr>
</table>
<?php echo CHtml::endForm(); ?>
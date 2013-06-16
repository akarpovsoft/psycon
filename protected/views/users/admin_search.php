<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
        <tr>
          <td class="ppheading" width="100%"><?php echo Yii::t('lang', 'Our_clients'); ?></td>
        </tr>
        <tr>
          <td><img src="<?php echo Yii::app()->params['http_addr']; ?>images/pixel.gif" width="2" height="2"></td>
        </tr>
    </tbody>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
        <tr>
          <td><img src="<?php echo Yii::app()->params['http_addr']; ?>images/pixel.gif" width="6" height="6"></td>
        </tr>
        <tr>
          <td bgcolor="#999999"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/pixel.gif" width="1" height="2"></td>
        </tr>
        <tr>
          <td><img src="<?php echo Yii::app()->params['http_addr']; ?>images/pixel.gif" width="6" height="6"></td>
        </tr>
    </tbody>
</table>
<?php echo CHtml::beginForm(); ?>
<?php echo CHtml::hiddenField('begin_search', 1, array('id' => 'begin_search')); ?>
<table border="0" cellpadding="4" cellspacing="1">
    <tbody>
        <tr>
          <td><font size="-1"><b>Search:</b></font></td>
          <td><input name="psearch" size="10" class="form" value="<?php echo $_POST['psearch']; ?>" type="Text"></td>
          <td>
              <select size="1" name="search_by">
                <option value="default" <?php if ($_POST['search_by'] == 'default') echo "selected";?>><?php echo Yii::t('lang', 'All_clients'); ?></option>
                <option value="login" <?php if ($_POST['search_by'] == 'login') echo "selected";?>><?php echo Yii::t('lang', 'Login'); ?></option>
                <option value="name" <?php if ($_POST['search_by'] == 'name') echo "selected";?>><?php echo Yii::t('lang', 'Name'); ?></option>
                <option value="real_name" <?php if ($_POST['search_by'] == 'real_name') echo "selected";?>><?php echo Yii::t('lang', 'Real_last_Name'); ?></option>
                <option value="phone" <?php if ($_POST['search_by'] == 'phone') echo "selected";?>><?php echo Yii::t('lang', 'Phone_Number'); ?></option>
                <option value="email" <?php if ($_POST['search_by'] == 'email') echo "selected";?>><?php echo Yii::t('lang', 'email_Address'); ?></option>
                <option value="address" <?php if ($_POST['search_by'] == 'address') echo "selected";?>><?php echo Yii::t('lang', 'address'); ?></option>
                <option value="signup_date" <?php if ($_POST['search_by'] == 'signup_date') echo "selected";?>><?php echo Yii::t('lang', 'Signup_Date'); ?></option>
                <option value="credit_card" <?php if ($_POST['search_by'] == 'credit_card') echo "selected";?>><?php echo Yii::t('lang', 'Credit_Card'); ?></option>
                <option value="credit_card_history" <?php if ($_POST['search_by'] == 'credit_card_history') echo "selected";?>><?php echo Yii::t('lang', 'Cards_History'); ?></option>
                <option value="dob_all" <?php if ($_POST['search_by'] == 'dob_all') echo "selected";?>><?php echo Yii::t('lang', 'DOB_mmddyyyy'); ?></option>
              </select>
          </td>
          <td>&nbsp;</td>
          <td>
            <?php echo CHtml::submitButton(Yii::t('lang', 'Search')); ?>
          </td>
        </tr>
    </tbody>
</table>
<?php echo CHtml::endForm(); ?>

<?if ($search_type == 'users'): ?>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$data,
    'columns' => array(
        array(
            'name' => Yii::t('lang', 'ID'),
            'value' => '$data->rr_record_id'
        ),
        array(
            'name' => Yii::t('lang', 'Login'),
            'value' => '$data->login'
        ),
        array(
            'name' => Yii::t('lang', 'email_Address'),
            'value' => '$data->emailaddress'
        ),
        array(
            'name' => Yii::t('lang', 'Credit_Card'),
            'value' => '$data->credit_cards->view1."*".$data->credit_cards->view2',
        ),
        array(
            'name' => Yii::t('lang', 'Name'),
            'value' => '$data->name'
        ),
        array(
            'class' => 'CLinkColumn',
            'urlExpression' => '"clientEdit?id=".$data->rr_record_id',
            'label' => 'View'
        ),
    ),
));
?>
<?php endif; ?>

<?php if($search_type == 'history'): ?>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$data,
    'columns' => array(
        array(
            'name' => Yii::t('lang', 'Record_Id'),
            'value' => '$data->record_id'
        ),
        array(
            'name' => Yii::t('lang', 'User_id'),
            'value' => '$data->user_id'
        ),
        array(
            'name' => Yii::t('lang', 'Name'),
            'value' => '$data->user_name'
        ),
        array(
            'name' => Yii::t('lang', 'Card_Number'),
            'value' => '$data->view1."*".$data->view2'
        ),
        array(
            'name' => Yii::t('lang', 'Date'),
            'value' => '$data->date'
        ),
        array(
            'class' => 'CLinkColumn',
            'urlExpression' => '"clientEdit?id=".$data->user_id',
            'label' => 'View'
        ),
    ),
));
?>
<?php endif; ?>
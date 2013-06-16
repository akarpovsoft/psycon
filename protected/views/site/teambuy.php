<?php if($success): ?>
<font color="green"><b>
    Thank you for your information.  We will be in contact shortly with your login details
</b></font>
<?php else: ?>
<h2>Redemption form</h2>
<?php if($errors): ?>
    <?php $this->widget('ErrorMessage', array('message' => $errors)); ?>
<?php endif; ?>
Please enter the following information:
<?php echo CHtml::beginForm(); ?>
    <table>
        <tr>
            <td> Your full name: </td>
            <td><input type="text" name="name" value="<?php echo $_POST['name']; ?>"></td>
        </tr>
        <tr>
            <td> Date of birth: </td>
            <td>
                 <?php echo CHtml::dropDownList('dob_month', null, array(
                    '' => Yii::t('lang','Please_Select'),
                    'Jan' => 'Jan',
                    'Feb' => 'Feb',
                    'Mar' => 'Mar',
                    'Apr' => 'Apr',
                    'May' => 'May',
                    'Jun' => 'Jun',
                    'Jul' => 'Jul',
                    'Aug' => 'Aug',
                    'Sep' => 'Sep',
                    'Oct' => 'Oct',
                    'Nov' => 'Nov',
                    'Dec' => 'Dec'
                    )); ?>
                <select class="SelectBoxStandard" name="dob_day">
                    <option value="">-- Please Select --</option>
                        <?php for($i=1;$i<32;$i++): ?>
                    <option value="<?php echo $i; ?>" <?php echo ($_POST['dob_day'] == $i) ? 'selected' : '' ?>><?php echo $i;?></option>
                        <?php endfor; ?>
                </select>
                <select class="SelectBoxStandard" name="dob_year">
                    <option value=""><?php echo Yii::t('lang','Please_Select'); ?></option>
                        <?php for($i=date('Y')-18;$i>date('Y')-73;$i--): ?>
                    <option value="<?php echo $i; ?>" <?php echo ($_POST['dob_year'] == $i) ? 'selected' : '' ?>><?php echo $i; ?></option>
                        <?php endfor; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td> Email address: </td>
            <td><input type="text" name="email" value="<?php echo $_POST['email']; ?>"></td>
        </tr>
        <tr>
            <td> Choose a username: </td>
            <td><input type="text" name="login" value="<?php echo $_POST['login']; ?>"></td>
        </tr>
        <tr>
            <td> Choose a password: </td>
            <td><input type="password" name="pwd" value="<?php echo $_POST['pwd']; ?>"></td>
        </tr>
        <tr>
            <td> Enter in your Teambuy code: </td>
            <td><input type="text" name="code" value="<?php echo $_POST['code']; ?>"></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" value="Send">
            </td>
        </tr>
    </table>
</form>
You will recieve an email with your account confirmation<br>
You can redeem and split up your time at your convenience with different readers!
<?php endif; ?>
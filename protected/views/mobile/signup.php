<center><b>Signup</b></center>
<?php if(isset($success)): ?>
<?php
$msg = 'Your new account has been approved!<br>
       To log on:  Please enter your new Username and Password in the logon form fields here <a href="'.Yii::app()->params['http_addr'].'site/login">'.Yii::app()->params['http_addr'].'site/login</a><br>
       If you need further assistance please go here: <a href="'.Yii::app()->params['site_domain'].'/assistance.html">'.Yii::app()->params['site_domain'].'/assistance.html</a><br>';
$this->widget('SuccessMessage', array('message' => $msg));
?>
<?php else: ?>
<?php if(isset($errors)): ?>
    <?php $this->widget('ErrorMessage', array('message' => $errors)); ?>
<?php endif; ?>
<?php echo CHtml::beginForm(); ?>
<?php echo CHtml::activeHiddenField($mod, 'affiliate', array('value' => $affiliate)); ?>
<?php echo CHtml::activeHiddenField($mod, 'gift', array('value' => $gift)); ?>
<?php if(isset($no_freebie)): ?>
    <?php echo CHtml::activeHiddenField($mod, 'no_freebie', array('value' => 1)); ?>
<?php endif; ?>
<table border="0" width="300" cellpadding="0" cellspacing="0">
    <tbody>
        <tr>
            <td colspan="3">
                    <?php if(isset($no_freebie)): ?>
                    <br><b>Thank You for Signing Up for a Psychic Contact account!<br>
                    NOTE! Please be aware that this page is for <span style="color: rgb(136, 0, 0); text-decoration: underline;">PAID Orders ONLY</span>.
                    If you would like to have your first Reading for free - Please click <a href="<?php echo Yii::app()->params['ssl_addr']; ?>site/signup">here</a>
                    (Remember- we have NO recurring billing or membership fees at all!).</b>
                    <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td></td>
            <td nowrap="nowrap" width="40">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'ourclients_txt_3') ?>
                </span>
            </td>
            <td>
                    <?php echo CHtml::activeTextField($mod, 'firstname', array('class' => 'InputBoxFront', 'size' => 15, 'maxlength' => 50)); ?>
            </td>
        </tr>
        <?php if($mod->getError('firstname')): ?>
        <tr>
            <td colspan="3">
                <?php $this->widget('InputError', array('errors' => $mod->getError('firstname'))); ?>
            </td>
        </tr>
        <?php endif; ?>            
        <tr>
            <td>&nbsp;</td>
            <td nowrap="nowrap" width="40">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'ourclients_txt_4') ?>
                </span>
            </td>
            <td>
                    <?php echo CHtml::activeTextField($mod, 'lastname', array('class' => 'InputBoxFront', 'size' => 15, 'maxlength' => 50)); ?>
            </td>
            
        </tr>
        <?php if($mod->getError('lastname')): ?>
        <tr>
            <td colspan="3">
                <?php $this->widget('InputError', array('errors' => $mod->getError('lastname'))); ?>
            </td>
        </tr>
        <?php endif; ?>   
        <tr>
            <td></td>
            <td nowrap="nowrap" width="40">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Login'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td>
                    <?php echo CHtml::activeTextField($mod, 'login', array('class' => 'InputBoxFront', 'size' => 15, 'maxlength' => 50)); ?>
            </td>
        </tr>
        <?php if($mod->getError('login')): ?>
        <tr>
            <td colspan="3">
                <?php $this->widget('InputError', array('errors' => $mod->getError('login'))); ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td></td>
            <td nowrap="nowrap" width="40">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Password'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td>
                    <?php echo CHtml::activePasswordField($mod, 'password', array('class' => 'InputBoxFront', 'size' => 15, 'maxlength' => 50)); ?>
            </td>
        </tr>
        <?php if($mod->getError('password')): ?>
        <tr>
            <td colspan="3">
                <?php $this->widget('InputError', array('errors' => $mod->getError('password'))); ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td></td>
            <td nowrap="nowrap" width="40">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'forget_email'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td>
                    <?php echo CHtml::activeTextField($mod, 'email', array('class' => 'InputBoxFront', 'size' => 15, 'maxlength' => 50)); ?>
            </td>
        </tr>
        <?php if($mod->getError('email')): ?>
        <tr>
            <td colspan="3">
                <?php $this->widget('InputError', array('errors' => $mod->getError('email'))); ?>
            </td>
        </tr>
        <?php endif; ?>        
        <tr>
            <td></td>
            <td nowrap="nowrap" width="40">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Date_of_Birth'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td nowrap="nowrap">
                    <?php echo CHtml::activeDropDownList($mod, 'dob_month', array(
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
                <br>
                <select class="SelectBoxStandard" name="Signup[dob_day]" id="Signup_dob_day">
                    <option value="">-- Please Select --</option>
                        <?php for($i=1;$i<32;$i++): ?>
                    <option value="<?php echo $i; ?>" <?php echo ($mod->dob_day == $i) ? 'selected' : '' ?>><?php echo $i;?></option>
                        <?php endfor; ?>
                </select>
                <br>
                <select class="SelectBoxStandard" name="Signup[dob_year]" id="Signup_dob_year">
                    <option value=""><?php echo Yii::t('lang','Please_Select'); ?></option>
                        <?php for($i=date('Y')-18;$i>date('Y')-73;$i--): ?>
                    <option value="<?php echo $i; ?>" <?php echo ($mod->dob_year == $i) ? 'selected' : '' ?>><?php echo $i; ?></option>
                        <?php endfor; ?>
                </select>
            </td>
        </tr>
        <?php if(($mod->getError('dob_month'))||($mod->getError('dob_day'))||($mod->getError('dob_year'))): ?>
        <tr>
            <td colspan="3">
                <?php
                $error_str = $mod->getError('dob_month').' '.$mod->getError('dob_day').' '.$mod->getError('dob_year');
                $this->widget('InputError', array('errors' => $error_str));
                ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td></td>
            <td><br></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td nowrap="nowrap" width="40">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Gender'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td>
                    <?php echo CHtml::activeDropDownList($mod, 'gender', array(
                    '' => Yii::t('lang','Please_Select'),
                    'Male' => 'Male',
                    'Female' => 'Female'
                    )); ?>
            </td>
        </tr>
        <?php if($mod->getError('gender')): ?>
        <tr>
            <td colspan="3">
                <?php $this->widget('InputError', array('errors' => $mod->getError('gender'))); ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td></td>
            <td width="40">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Address'); ?>:<br>(<?php echo Yii::t('lang', 'register_msg_22'); ?>)
                </span>
            </td>
            <td nowrap="nowrap">
                    <?php echo CHtml::activeTextField($mod, 'address', array('class' => 'InputBoxFront', 'size' => 15)); ?>
            </td>
        </tr>
        <?php if($mod->getError('address')): ?>
        <tr>
            <td colspan="3">
                <?php $this->widget('InputError', array('errors' => $mod->getError('address'))); ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td></td>
            <td nowrap="nowrap" width="40">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'City'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td nowrap="nowrap">
                    <?php echo CHtml::activeTextField($mod, 'city', array('class' => 'InputBoxFront', 'size' => 15)); ?>
            </td>
        </tr>
        <?php if($mod->getError('city')): ?>
        <tr>
            <td colspan="3">
                <?php $this->widget('InputError', array('errors' => $mod->getError('city'))); ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td></td>
            <td nowrap="nowrap">
                <span class="TextSmall" width="40">
                        <?php echo Yii::t('lang', 'State'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td nowrap="nowrap">
                    <?php echo CHtml::activeDropDownList($mod, 'state', array(
                    '' => Yii::t('lang','Please_Select'),
                    'International' => 'International',
                    'Alabama' => 'Alabama',
                    'Alaska' => 'Alaska',
                    'Arizona' => 'Arizona',
                    'Arkansas' => 'Arkansas',
                    'California' => 'California',
                    'Colorado' => 'Colorado',
                    'Connecticut' => 'Connecticut',
                    'Delaware' => 'Delaware',
                    'District of Columbia' => 'District of Columbia',
                    'Florida' => 'Florida',
                    'Georgia' => 'Georgia',
                    'Hawaii' => 'Hawaii',
                    'Idaho' => 'Idaho',
                    'Illinois' => 'Illinois',
                    'Indiana' => 'Indiana',
                    'Iowa' => 'Iowa',
                    'Kansas' => 'Kansas',
                    'Kentucky' => 'Kentucky',
                    'Louisiana' => 'Louisiana',
                    'Maine' => 'Maine',
                    'Maryland' => 'Maryland',
                    'Massachusetts' => 'Massachusetts',
                    'Michigan' => 'Michigan',
                    'Minnesota' => 'Minnesota',
                    'Mississippi' => 'Mississippi',
                    'Missouri' => 'Missouri',
                    'Montana' => 'Montana',
                    'Nebraska' => 'Nebraska',
                    'Nevada' => 'Nevada',
                    'New Hampshire' => 'New Hampshire',
                    'New Jersey' => 'New Jersey',
                    'New Mexico' => 'New Mexico',
                    'New York' => 'New York',
                    'North Carolina' => 'North Carolina',
                    'North Dakota' => 'North Dakota',
                    'Ohio' => 'Ohio',
                    'Oklahoma' => 'Oklahoma',
                    'Oregon' => 'Oregon',
                    'Pennsylvania' => 'Pennsylvania',
                    'Rhode Island' => 'Rhode Island',
                    'South Carolina' => 'South Carolina',
                    'South Dakota' => 'South Dakota',
                    'Tennessee' => 'Tennessee',
                    'Texas' => 'Texas',
                    'Utah' => 'Utah',
                    'Vermont' => 'Vermont',
                    'Virginia' => 'Virginia',
                    'Washington' => 'Washington',
                    'West Virginia' => 'West Virginia',
                    'Wisconsin' => 'Wisconsin',
                    'Wyoming' => 'Wyoming',
                    )); ?>
        </tr>
        <?php if($mod->getError('state')): ?>
        <tr>
            <td colspan="3">
                <?php $this->widget('InputError', array('errors' => $mod->getError('state'))); ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td></td>
            <td nowrap="nowrap" width="40">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Zipcode'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td nowrap="nowrap">
                    <?php echo CHtml::activeTextField($mod, 'zip', array('class' => 'InputBoxFront', 'size' => 15)); ?>
            </td>
        </tr>
        <?php if($mod->getError('zip')): ?>
        <tr>
            <td colspan="3">
                <?php $this->widget('InputError', array('errors' => $mod->getError('zip'))); ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td></td>
            <td nowrap="nowrap" width="40">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Country'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td nowrap="nowrap">
                    <?php echo CHtml::activeDropDownList($mod, 'country', array(
                    '' => Yii::t('lang','Please_Select'),
                    'Albania' => 'Albania',
                    'Algeria' => 'Algeria',
                    'Angola' => 'Angola',
                    'Anguilla' => 'Anguilla',
                    'Antigua' => 'Antigua',
                    'Argentina' => 'Argentina',
                    'Armenia' => 'Armenia',
                    'Aruba' => 'Aruba',
                    'Australia' => 'Australia',
                    'Austria' => 'Austria',
                    'Azerbejan' => 'Azerbejan',
                    'Bahamas' => 'Bahamas',
                    'Bahrain' => 'Bahrain',
                    'Bangladesh' => 'Bangladesh',
                    'Barbados' => 'Barbados',
                    'Barbuda' => 'Barbuda',
                    'Belarus' => 'Belarus',
                    'Belgium' => 'Belgium',
                    'Belize' => 'Belize',
                    'Bermuda' => 'Bermuda',
                    'Bolivia' => 'Bolivia',
                    'Bonaire' => 'Bonaire',
                    'Bosnia' => 'Bosnia',
                    'Botswana' => 'Botswana',
                    'Brazil' => 'Brazil',
                    'British Virgin Islands' => 'B.V.I.',
                    'Brunei' => 'Brunei',
                    'Bulgaria' => 'Bulgaria',
                    'Burkina Faso' => 'Burkina Faso',
                    'Cambodia' => 'Cambodia',
                    'Cameroon' => 'Cameroon',
                    'Canada' => 'Canada',
                    'Cape Verde' => 'Cape Verde',
                    'Cayman Islands' => 'Cayman Islands',
                    'Central African Republic' => 'C.A.R.',
                    'Central America' => 'Central America',
                    'Chad' => 'Chad',
                    'Chile' => 'Chile',
                    'China' => 'China',
                    'China (Beijing)' => 'China (Beijing)',
                    'China (Shanghai)' => 'China (Shanghai)',
                    'China(GuangZhou)' => 'China(GuangZhou)',
                    'Colombia' => 'Colombia',
                    'Comoros' => 'Comoros',
                    'Congo Brazzaville' => 'Congo',
                    'Costa Rica' => 'Costa Rica',
                    'Croatia' => 'Croatia',
                    'Curacao' => 'Curacao',
                    'Cyprus' => 'Cyprus',
                    'Czech Republic' => 'Czech Republic',
                    'Denmark' => 'Denmark',
                    'Djibouti' => 'Djibouti',
                    'Dominican Republic' => 'Dominican Republic',
                    'Dubai' => 'Dubai',
                    'Ecuador' => 'Ecuador',
                    'Egypt' => 'Egypt',
                    'El Salvador' => 'El Salvador',
                    'Equatorial Guinea' => 'Equatorial Guinea',
                    'Eritrea' => 'Eritrea',
                    'Estonia' => 'Estonia',
                    'Ethiopia' => 'Ethiopia',
                    'Finland' => 'Finland',
                    'France' => 'France',
                    'Gabon' => 'Gabon',
                    'Gambia' => 'Gambia',
                    'Germany' => 'Germany',
                    'Ghana' => 'Ghana',
                    'Greece' => 'Greece',
                    'Grenada' => 'Grenada',
                    'Guadeloupe' => 'Guadeloupe',
                    'Guatemala' => 'Guatemala',
                    'Haiti' => 'Haiti',
                    'Holland &amp; Luxembourg' => 'Holland',
                    'Honduras' => 'Honduras',
                    'Hong Kong' => 'Hong Kong',
                    'Hungary' => 'Hungary',
                    'Iceland' => 'Iceland',
                    'India' => 'India',
                    'Indonesia' => 'Indonesia',
                    'Iran' => 'Iran',
                    'Iraq' => 'Iraq',
                    'Ireland' => 'Ireland',
                    'Israel' => 'Israel',
                    'Italy' => 'Italy',
                    'Ivory Coast' => 'Ivory Coast',
                    'Jamaica' => 'Jamaica',
                    'Japan' => 'Japan',
                    'Jordan' => 'Jordan',
                    'Kenya' => 'Kenya',
                    'Kirgizstan' => 'Kirgizstan',
                    'Korea' => 'Korea',
                    'Kuwait' => 'Kuwait',
                    'Laos' => 'Laos',
                    'Latvia' => 'Latvia',
                    'Lebanon' => 'Lebanon',
                    'Lesotho' => 'Lesotho',
                    'Liberia' => 'Liberia',
                    'Libya' => 'Libya',
                    'Lithuani' => 'Lithuani',
                    'Lithuania' => 'Lithuania',
                    'Luxembourg' => 'Luxembourg',
                    'Macau' => 'Macau',
                    'Macedonia' => 'Macedonia',
                    'Madagascar' => 'Madagascar',
                    'Malawi' => 'Malawi',
                    'Malaysia' => 'Malaysia',
                    'Malta' => 'Malta',
                    'Martinique' => 'Martinique',
                    'Mauritania' => 'Mauritania',
                    'Mauritius' => 'Mauritius',
                    'Mexico' => 'Mexico',
                    'Moldova' => 'Moldova',
                    'Monserrat' => 'Monserrat',
                    'Morocco' => 'Morocco',
                    'Mozambique' => 'Mozambique',
                    'Myanmar' => 'Myanmar',
                    'Namibia' => 'Namibia',
                    'Nepal' => 'Nepal',
                    'Netherlands' => 'Netherlands',
                    'New Zealand' => 'New Zealand',
                    'Nicaragua' => 'Nicaragua',
                    'Niger' => 'Niger',
                    'Nigeria' => 'Nigeria',
                    'Norway' => 'Norway',
                    'Oman' => 'Oman',
                    'Pakistan' => 'Pakistan',
                    'Panama' => 'Panama',
                    'Paraguay' => 'Paraguay',
                    'Peru' => 'Peru',
                    'Philippines' => 'Philippines',
                    'Poland' => 'Poland',
                    'Portugal' => 'Portugal',
                    'Puerto Rico' => 'Puerto Rico',
                    'Quatar' => 'Quatar',
                    'Romania' => 'Romania',
                    'Russia' => 'Russia',
                    'Rwanda' => 'Rwanda',
                    'Saudi Arabia' => 'Saudi Arabia',
                    'Senegal' => 'Senegal',
                    'Seychelles' => 'Seychelles',
                    'Sierra Leone' => 'Sierra Leone',
                    'Singapore' => 'Singapore',
                    'Slovakia' => 'Slovakia',
                    'Slovenia' => 'Slovenia',
                    'Somalia' => 'Somalia',
                    'South Africa' => 'South Africa',
                    'Spain' => 'Spain',
                    'Sri Lanka' => 'Sri Lanka',
                    'St Lucia' => 'St Lucia',
                    'St. Barts' => 'St. Barts',
                    'St. Eustatius' => 'St. Eustatius',
                    'Sudan' => 'Sudan',
                    'Swaziland' => 'Swaziland',
                    'Sweden' => 'Sweden',
                    'Switzerland' => 'Switzerland',
                    'Syria' => 'Syria',
                    'Taiwan' => 'Taiwan',
                    'Tajikistan' => 'Tajikistan',
                    'Tanzania' => 'Tanzania',
                    'Thailand' => 'Thailand',
                    'Togo' => 'Togo',
                    'Trinidad and Tobago' => 'T.A.T',
                    'Tunisia' => 'Tunisia',
                    'Turkey' => 'Turkey',
                    'Turkmenistan' => 'Turkmenistan',
                    'UAE (United Arab Emirates)' => 'UAE',
                    'Uganda' => 'Uganda',
                    'Ukraine' => 'Ukraine',
                    'United Kingdom' => 'United Kingdom',
                    'United States' => 'United States',
                    'Upper Volta' => 'Upper Volta',
                    'Uruguay' => 'Uruguay',
                    'Uzbekistan' => 'Uzbekistan',
                    'Venezuela' => 'Venezuela',
                    'Vietnam' => 'Vietnam',
                    'Western Sahara' => 'Western Sahara',
                    'Yugoslavia' => 'Yugoslavia',
                    'Zaire' => 'Zaire',
                    'Zambia' => 'Zambia',
                    'Zimbabwe' => 'Zimbabwe'
                    )); ?>
            </td>
        </tr>
        <?php if($mod->getError('country')): ?>
        <tr>
            <td colspan="3">
                <?php $this->widget('InputError', array('errors' => $mod->getError('country'))); ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td></td>
            <td colspan="4" align="center" width="100%">
                <span class="textMedium"><br><?php echo Yii::t('lang', 'register_msg_23'); ?><br></span>
            </td>
        </tr>
            <?php if(!isset($no_freebie)): ?>
        <tr>
            <td></td>
            <td colspan="4" align="left" width="100%">
                <font color="#ff0000"><b><?php echo Yii::t('lang', 'register_msg_26_4'); ?><br></b></font>                
            </td>
        </tr>
            <?php endif; ?>
        <tr>
            <td></td>
            <td nowrap="nowrap" width="40">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Amount'); ?>:<br>
                        <?php if(isset($no_freebie)): ?>
                    <font color="#ff0000">(ALL purchases of $29.95 or higher-<br>
                        receive an extra 10mins for FREE!-<br> sorry no combining lesser amount orders)</font>
                        <?php endif; ?>
                </span>
            </td>
            <td nowrap="nowrap">
                    <?php
                    if(isset($no_freebie)) {
                        $tariffs = Tariff::loadChatOptions();
                        $over_tar = array();
                        foreach($tariffs as $key => $value) {
                            if($key == 1) continue;
                            $over_tar[$key] = '$'.$value['amount'].' '.$value['title'];
                        }
                        echo CHtml::activeDropDownList($mod, 'amount', $over_tar);
                    } else
                        echo CHtml::activeDropDownList($mod, 'amount', array(
                        '1' => '10 mins. FREE'
                        ));
                    ?>
            </td>
        </tr>
        <?php if($mod->getError('amount')): ?>
        <tr>
            <td colspan="3">
                <?php $this->widget('InputError', array('errors' => $mod->getError('amount'))); ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td></td>
            <td nowrap="nowrap" width="40">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Credit_Card') ?>#:&nbsp;&nbsp;
                </span>
            </td>
            <td nowrap="nowrap">
                    <?php echo CHtml::activeTextField($mod, 'cardnumber', array('class' => 'InputBoxFrontMedium', 'size' => 15)); ?>
            </td>
        </tr>
        <?php if($mod->getError('cardnumber')): ?>
        <tr>
            <td colspan="3">
                <?php $this->widget('InputError', array('errors' => $mod->getError('cardnumber'))); ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td></td>
            <td nowrap="nowrap" width="40">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Exp_Date') ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td nowrap="nowrap">
                    <?php echo CHtml::activeDropDownList($mod, 'exp_month', array(
                    '' => Yii::t('lang','Please_Select'),
                    '1' => '01',
                    '2' => '02',
                    '3' => '03',
                    '4' => '04',
                    '5' => '05',
                    '6' => '06',
                    '7' => '07',
                    '8' => '08',
                    '9' => '09',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12'
                    )); ?>
                <br>
                <select class="SelectBoxSmall" name="Signup[exp_year]" id="Signup_exp_year">
                    <option value="">-- <?php echo Yii::t('lang','Please_Select') ?> --</option>
                        <?php for($i=0;$i<16;$i++): ?>
                    <option value="<?php echo date('Y')+$i; ?>" <?php echo(date('Y')+$i == $mod->exp_year) ? 'selected' : '' ?>><?php echo date('Y')+$i; ?></option>
                        <?php endfor; ?>
                </select>
            </td>
        </tr>
        <?php if(($mod->getError('exp_month'))||($mod->getError('exp_year'))): ?>
        <tr>
            <td colspan="3">
                <?php
                $exp_err = array();
                $exp_arr = $mod->getError('exp_month').' '.$mod->getError('exp_year');
                $this->widget('InputError', array('errors' => $exp_arr)); ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
        </tr>
        <tr>
            <td></td>
            <td nowrap="nowrap" width="40">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Security_Code') ?>: CVV2/CVC2&nbsp;&nbsp;
                </span>
            </td>
            <td nowrap="nowrap">
                    <?php echo CHtml::activeTextField($mod, 'cvv', array('class' => 'InputBoxFrontMedium', 'maxlength' => 4, 'size' => 4)); ?>
                &nbsp;&nbsp;&nbsp;&nbsp; <a href="<?php echo Yii::app()->params['ssl_addr']; ?>images/cvv2_image.gif" target="_blank">What is this?</a>
                <?php if(($mod->getError('cvv'))||(!empty($mod->empty_cvv))): ?>
                    <br>
                    <input type="checkbox" name="Signup[empty_cvv]" <?php echo (!empty($mod->empty_cvv)) ? 'checked' : '' ?>> I have no CVV on my card
                <?php endif; ?>
            </td>
        </tr>
        <?php if($mod->getError('cvv')): ?>
        <tr>
            <td colspan="3">
                <?php $this->widget('InputError', array('errors' => $mod->getError('cvv'))); ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td></td>
            <td colspan="4" width="100%">
                <span class="textMedium"><br><b>NOTE:</b> <?php echo Yii::t('lang', 'register_msg_28') ?></span>
            </td>
        </tr>
        <tr>
            <td colspan="5" width="100%">
                &nbsp;
                &nbsp;
                <input type="submit" name="register" value="Register"><input type="reset" name="reset" value="Cancel">
            </td>
        </tr>
    </tbody>
</table>
    <?php echo CHtml::endForm(); ?>
<br>
<center>
    <!-- BEGIN SiteSeal -->
        <script language="javascript" src="https://cgnsecurity.com/ss/getCgnSS.php?d1=522601312&d2=1295275807" type="text/javascript"></script>
    <!-- END SiteSeal -->
</center>
<?php endif; ?>
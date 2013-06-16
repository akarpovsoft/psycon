<script type="text/javascript">
        function check(){
            x = 0;
            error_msg = "You did not complete all required fields:\n\n"
            if(document.getElementById("Signup_password").value == ""){
                x = 1;
                error_msg += "* Password is required\n";
            }
            if(document.getElementById("Signup_address").value == ""){
                x = 1;
                error_msg += "* Address is required\n";
            }
            if(document.getElementById("Signup_city").value == ""){
                x = 1;
                error_msg += "* City is required\n";
            }
            if(document.getElementById("Signup_state").value == ""){
                x = 1;
                error_msg += "* State is required\n";
            }
            if(document.getElementById("Signup_country").value == ""){
                x = 1;
                error_msg += "* Country is required\n";
            }
            if(document.getElementById("Signup_zip").value == ""){
                x = 1;
                error_msg += "* ZipCode is required\n";
            }
            if(document.getElementById("Signup_amount").value == ""){
                x = 1;
                error_msg += "* Amount is required\n";
            }
            if(document.getElementById("Signup_cardnumber").value == ""){
                x = 1;
                error_msg += "* Credit Card number is required\n";
            }
            if(document.getElementById("Signup_exp_month").value == ""){
                x = 1;
                error_msg += "* Expiration month is required\n";
            }
            if(document.getElementById("Signup_exp_year").value == ""){
                x = 1;
                error_msg += "* PExpiration year is required\n";
            }
            if(document.getElementById("Signup_cvv").value == ""){
                x = 1;
                error_msg += "* CVV is required\n";
            }
            if(x == 1){
                alert(error_msg);
                return false;
            }
            else
                document.getElementById('spinner').style.display = 'block';
        }
    </script>
    <center>
        <h1>Registration</h1>
<?php if(isset($success)): ?>
<div style="width: 450px;">
<?php
$msg = 'Your new account has been approved!<br>
       To log on:  Please enter your new Username and Password in the logon form fields here <a href="'.Yii::app()->params['http_addr'].'site/login">'.Yii::app()->params['http_addr'].'site/login</a><br>
       If you need further assistance please go here: <a href="'.Yii::app()->params['site_domain'].'/assistance.html">'.Yii::app()->params['site_domain'].'/assistance.html</a><br>';
$this->widget('SuccessMessage', array('message' => $msg));
?>
</div>
<?php else: ?>
    <?php if(isset($errors)): ?>
        <div style="width: 450px;">
            <?php $this->widget('ErrorMessage', array('message' => $errors)); ?>
        </div>
    <?php endif; ?>
    <?php echo CHtml::beginForm('', 'post', array('onSubmit'=>'return check();')); ?>
    <?php echo CHtml::activeHiddenField($mod, 'affiliate'); ?>
    <?php echo CHtml::activeHiddenField($mod, 'login'); ?>
    <?php echo CHtml::activeHiddenField($mod, 'email'); ?>
    <?php echo CHtml::activeHiddenField($mod, 'email_confirm'); ?>
    <?php echo CHtml::activeHiddenField($mod, 'gender'); ?>
    <?php echo CHtml::activeHiddenField($mod, 'firstname'); ?>
    <?php echo CHtml::activeHiddenField($mod, 'dob_month'); ?>
    <?php echo CHtml::activeHiddenField($mod, 'dob_day'); ?>
    <?php echo CHtml::activeHiddenField($mod, 'dob_year'); ?>
    <?php echo CHtml::activeHiddenField($mod, 'site_type'); ?>
    <?php echo CHtml::activeHiddenField($mod, 'site_name'); ?>
    <?php echo CHtml::activeHiddenField($mod, 'site_url'); ?>
    <?php echo CHtml::activeHiddenField($mod, 'admin_email'); ?>
    <?php echo CHtml::activeHiddenField($mod, 'debug'); ?>
    <?php echo CHtml::activeHiddenField($mod, 'hear'); ?>
    <input type="hidden" name="return_url" value="<?php echo $return_url; ?>">
    
    <?php if(isset($no_freebie)): ?>
        <?php echo CHtml::activeHiddenField($mod, 'no_freebie', array('value' => 1)); ?>
    <?php endif; ?>
    <center>
        <div id="spinner" style="display: none; position: absolute; margin-left: 150px; margin-top: 500px;">
        <table bgcolor="#aaaaaa" border="0" cellpadding="1" cellspacing="0">
            <tr>
                <td>
                    <table bgcolor="white" border="0" cellpadding="4" cellspacing="0">
                        <tbody>
                            <tr>
                                <td align="center" nowrap width="350" height="200">
                                    <img src="<?php echo Yii::app()->params['http_addr'] ?>images/clock.gif">
                                    <h2>
                                        Checking data...
                                    </h2>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        </div>
    </center>
<table border="0" width="480" style="font-size: 11px;">
    <tbody>
        <tr>
            <td colspan="3">
                <span class="TextMedium">
                       In less than 30 seconds you can get signed up for your PRIVATE chat session with one of our Readers. 
                </span>
                    <?php if(isset($no_freebie)): ?>
                <br><b>Thank You for Signing Up for a Psychic Contact account!<br>
                    NOTE! Please be aware that this page is for <span style="color: rgb(136, 0, 0); text-decoration: underline;">PAID Orders ONLY</span>.
                    If you would like to have your first Reading for free - Please click <a href="<?php echo Yii::app()->params['ssl_addr']; ?>site/signup">here</a>
                    (Remember- we have NO recurring billing or membership fees at all!).</b>
                    <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/oneoperatoricon_small.gif" border="0" width="17" height="17">&nbsp;</td>
            <td nowrap="nowrap">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'ourclients_txt_3') ?>
                </span>
            </td>
            <td>
                    <b><?php echo $mod->firstname; ?></b>
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
            <td nowrap="nowrap">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'ourclients_txt_4') ?>
                </span>
            </td>
            <td>
                    <?php echo CHtml::activeTextField($mod, 'lastname', array('class' => 'InputBoxFront', 'size' => 40, 'maxlength' => 50)); ?>
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
            <td nowrap="nowrap">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Login'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td>
                    <?php echo $mod->login; ?>
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
            <td nowrap="nowrap">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Password'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td>
                    <?php echo CHtml::activePasswordField($mod, 'password', array('class' => 'InputBoxFront', 'size' => 40, 'maxlength' => 50)); ?>
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
            <td nowrap="nowrap">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'forget_email'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td>
                    <b><?php echo $mod->email; ?></b>
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
            <td nowrap="nowrap">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'register_msg_20'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td>
                    <b><?php echo $mod->hear; ?></b>
            </td>
        </tr>
        <?php if($mod->getError('hear')): ?>
        <tr>
            <td colspan="3">
                <?php $this->widget('InputError', array('errors' => $mod->getError('hear'))); ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td></td>
            <td nowrap="nowrap">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Date_of_Birth'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td nowrap="nowrap">
                    <b><?php echo $mod->dob_month; ?>/<?php echo $mod->dob_day; ?>/<?php echo $mod->dob_year; ?></b>                 
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
            <td nowrap="nowrap">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Gender'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td>
                   <b> <?php echo $mod->gender; ?></b>
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
            <td><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/addressicon.gif">&nbsp;</td>
            <td>
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Address'); ?>:<br>(<?php echo Yii::t('lang', 'register_msg_22'); ?>)
                </span>
            </td>
            <td nowrap="nowrap">
                    <?php echo CHtml::activeTextField($mod, 'address', array('class' => 'InputBoxFront', 'size' => 40)); ?>
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
            <td nowrap="nowrap">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'City'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td nowrap="nowrap">
                    <?php echo CHtml::activeTextField($mod, 'city', array('class' => 'InputBoxFront', 'size' => 40)); ?>
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
                <span class="TextSmall">
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
            <td nowrap="nowrap">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Zipcode'); ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td nowrap="nowrap">
                    <?php echo CHtml::activeTextField($mod, 'zip', array('class' => 'InputBoxFront', 'size' => 40)); ?>
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
            <td nowrap="nowrap">
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
                    'British Virgin Islands' => 'British Virgin Islands',
                    'Brunei' => 'Brunei',
                    'Bulgaria' => 'Bulgaria',
                    'Burkina Faso' => 'Burkina Faso',
                    'Cambodia' => 'Cambodia',
                    'Cameroon' => 'Cameroon',
                    'Canada' => 'Canada',
                    'Cape Verde' => 'Cape Verde',
                    'Cayman Islands' => 'Cayman Islands',
                    'Central African Republic' => 'Central African Republic',
                    'Central America' => 'Central America',
                    'Chad' => 'Chad',
                    'Chile' => 'Chile',
                    'China' => 'China',
                    'China (Beijing)' => 'China (Beijing)',
                    'China (Shanghai)' => 'China (Shanghai)',
                    'China(GuangZhou)' => 'China(GuangZhou)',
                    'Colombia' => 'Colombia',
                    'Comoros' => 'Comoros',
                    'Congo Brazzaville' => 'Congo Brazzaville',
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
                    'Holland &amp; Luxembourg' => 'Holland &amp; Luxembourg',
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
                    'Sao Tome &amp; Principe' => 'Sao Tome &amp; Principe',
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
                    'St Kitts and Nevis' => 'St Kitts and Nevis',
                    'St Lucia' => 'St Lucia',
                    'St Vincent and the Grenadines' => 'St Vincent and the Grenadines',
                    'St. Barts' => 'St. Barts',
                    'St. Eustatius' => 'St. Eustatius',
                    'St. Martin and St. Maarten' => 'St. Martin and St. Maarten',
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
                    'Trinidad and Tobago' => 'Trinidad and Tobago',
                    'Tunisia' => 'Tunisia',
                    'Turkey' => 'Turkey',
                    'Turkmenistan' => 'Turkmenistan',
                    'Turks and Caicos Islands' => 'Turks and Caicos Islands',
                    'UAE (United Arab Emirates)' => 'UAE (United Arab Emirates)',
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
        <tr>
            <td></td>
            <td nowrap="nowrap">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Amount'); ?>:<br>
                        <?php if(isset($no_freebie)): ?>
                    <font color="#ff0000">(ALL purchases of $29.95 or higher-<br>
                        receive an extra 10mins for FREE!-<br> sorry no combining lesser amount orders)</font><br>
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
                        '1' => Yii::t('lang', 'register_msg_26_9')
                        ));
                    ?><br>
                    <a href="<?php echo $mod->site_url; ?>page/10minsFREE" target="_blank">About 10mins free chat</a>
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
            <td nowrap="nowrap">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Credit_Card') ?>#:&nbsp;&nbsp;
                </span>
            </td>
            <td nowrap="nowrap">
                    <?php echo CHtml::activeTextField($mod, 'cardnumber', array('class' => 'InputBoxFrontMedium', 'size' => 20)); ?>
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
            <td nowrap="nowrap">
                <span class="TextSmall">
                        <?php echo Yii::t('lang', 'Exp_Date') ?>:&nbsp;&nbsp;
                </span>
            </td>
            <td nowrap="nowrap">
                    <?php echo CHtml::activeDropDownList($mod, 'exp_month', array(
                    '' => Yii::t('lang','Please_Select'),
                    '01' => '01',
                    '02' => '02',
                    '03' => '03',
                    '04' => '04',
                    '05' => '05',
                    '06' => '06',
                    '07' => '07',
                    '08' => '08',
                    '09' => '09',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12'
                    )); ?>
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
            <td nowrap="nowrap">
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
            <td nowrap="nowrap" align="right">
                <span class="TextSmall">
                        Verification Code:&nbsp;&nbsp;
                </span>
            </td>
            <td nowrap="nowrap" align="left" style="vertical-align: top;">
                <?php $this->widget('CCaptcha'); ?><br><?php echo CHtml::activeTextField($mod, 'verifyCode'); ?>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="4" width="100%">
                <span class="textMedium"><br><b>NOTE:</b> <?php echo Yii::t('lang', 'register_msg_28') ?></span>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="4" width="100%">
                <br><span class="textMedium">
                By submitting this form I agree to & accept the following:<br>
                1) I have read the "FREEBIE FAQ" page in full<br>
                2) I understand that my credit card will have a temporary charge of $11.95us placed on it<br>
                3) I will receive 10 minutes of totally FREE chat time - with no obligation to continue or purchase anything further<br>
                4) I will never be charged a monthly or membership fee<br>
                5) I can close my account at any time<br>
                <br>
                <input type="radio" name="Signup[terms]" value="1" onClick="javascript:document.getElementById('register').disabled = false;" <?php echo ($mod->terms == 1) ? 'checked' : ''; ?>>I agree 
                <input type="radio" name="Signup[terms]" value="0" onClick="javascript:document.getElementById('register').disabled = true;" <?php echo ($mod->terms == 0) ? 'checked' : ''; ?>>I don't agree<br><br>
                </span>
                &nbsp;
                &nbsp;

        <input type="hidden" name="account_id" value="<?php echo $_REQUEST['account_id'];?>">
        <input type="hidden" name="ps_type" value="<?php echo $_REQUEST['ps_type'];?>">
        <input type="hidden" name="currency" value="<?php echo $_REQUEST['currency'];?>">
                
                <input type="submit" name="register" id="register" value="Register" <?php echo ($mod->terms != 1) ? 'disabled="disabled"' : ''?>><input type="button" name="reset" value="Cancel" onclick="javascript:document.location.href='<?php echo $mod->site_url ?>site/preRegister'"> 
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
</center>
</body>
</html>
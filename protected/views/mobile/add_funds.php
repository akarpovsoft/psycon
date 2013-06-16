<script language="JavaScript">
    function endisCreditCard(frm){
        if(document.getElementById('AddFunds_payment_method').value == 'PayPal'){
            document.getElementById('alert_msg').style.display = 'block';
            frm.form.submit();
        } else { 
            if(document.getElementById('cc').checked == true) {
                document.getElementById('AddFunds_cardnumber').disabled = true;
                document.getElementById('AddFunds_cvv').disabled = false;
                document.getElementById('AddFunds_month').disabled = true;
                document.getElementById('AddFunds_year').disabled = true;
                document.getElementById('cc').disabled = false;
            } else {
                document.getElementById('AddFunds_cardnumber').disabled = false;
                document.getElementById('AddFunds_cvv').disabled = false;
                document.getElementById('AddFunds_month').disabled = false;
                document.getElementById('AddFunds_year').disabled = false;
                document.getElementById('cc').disabled = false;
            }
        }
    }

    function currentCard(){
        if(document.getElementById('cc').checked == true){
            document.getElementById('AddFunds_cardnumber').disabled = true;
            document.getElementById('AddFunds_month').disabled = true;
            document.getElementById('AddFunds_year').disabled = true;
        } else {
            document.getElementById('AddFunds_cardnumber').disabled = false;
            document.getElementById('AddFunds_month').disabled = false;
            document.getElementById('AddFunds_year').disabled = false;
        }
    }
</script>
<center>
<?php if(isset($success)): ?>
<?php $this->widget('SuccessMessage', array('message' => Yii::t('lang', 'Payment_success_chat'))); ?>
<?php else: ?>
    <?php if(isset($errors)): ?>
        <?php $this->widget('ErrorMessage', array('message' => $errors)); ?>
    <?php endif; ?>
</center>
    <br>
    <?php echo CHtml::beginForm(); ?>
    <input type="hidden" name="sessionKey" value="<?php echo $model->session_key; ?>">
    <input type="hidden" name="pay_begin" value="1">
    <input type="hidden" name="user_id" value="<?php echo Yii::app()->user->id; ?>">
    <?php echo CHtml::activeHiddenField($model, 'session_id'); ?>
    <?php echo CHtml::activeHiddenField($model, 'session_reader'); ?>
    <table cellspacing=0 cellpadding=0 border=0 width="300">
        <tr>
            <td colspan=3>
                    <?php echo Yii::t('lang', 'addfunds_10') ?>
            </td>
        </tr>
        <tr>
            <td>
                <table cellspacing=0 cellpadding=0 border=0>
                    <tr>
                        <td align=left>
                            <table border=0>
                                <tr>
                                    <td></td>
                                    <td nowrap colspan="2">
                                        <span class=TextMedium>
                                            <p><b>Amount:</b>&nbsp;
                                            <select name="tariff">
                                                <option value="1">
                                                $11.95 10 Minutes</option>
                                                <option value="2" style="font-weight: bold; color: rgb(136, 0, 0);">
                                                $19.95 NEW! 20 mins</option>
                                                <option value="3">
                                                $29.95 15 Minutes</option>
                                                <option value="4">
                                                $44.95 30 Minutes</option>
                                                <option value="5">
                                                $59.95 45 Minutes</option>
                                                <option value="6">
                                                $79.95 60 Minutes</option>
                                           </select>
                                        </span>
                                    </td>                                    
                                </tr>
                                <tr>
                                    <td>
                                        <div id="alert_msg" style="display: none; position: absolute; margin-left: 100px; margin-top: 30px;">
                                            <?php $this->widget('AlertMsg', array('message' => 'Redirecting to Paypal...')) ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>

                                    <td></td>
                                    <td nowrap>
                                        <SPAN class=TextSmall>
                                                <?php echo Yii::t('lang','First_Name_on_card') ?>:
                                        </SPAN>
                                    </td>
                                    <td>
                                            <?php echo CHtml::activeTextField($model,'firstname', array('class' => 'InputBoxFront', 'size' => '20', 'style' => 'color:#B1B1B1', 'maxlength' => '50', 'readonly' => 'readonly')) ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td nowrap>
                                        <SPAN class=TextSmall>
                                                <?php echo Yii::t('lang','Last_Name_on_card') ?>:&nbsp;&nbsp;
                                        </SPAN>

                                    </td>
                                    <td>
                                            <?php echo CHtml::activeTextField($model,'lastname', array('class' => 'InputBoxFront', 'size' => '20', 'style' => 'color:#B1B1B1', 'maxlength' => '50', 'readonly' => 'readonly')) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td nowrap>
                                        <SPAN class=TextSmall>
                                                <?php echo Yii::t('lang','Billing_Address') ?>:&nbsp;&nbsp;
                                        </SPAN>
                                    </td>
                                    <td>
                                            <?php echo CHtml::activeTextField($model,'billingaddress', array('class' => 'InputBoxFront', 'size' => '20')) ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td nowrap>
                                        <SPAN class=TextSmall>
                                                <?php echo Yii::t('lang','Billing_City') ?>:&nbsp;&nbsp;
                                        </SPAN>
                                    </td>
                                    <td>
                                            <?php echo CHtml::activeTextField($model,'billingcity', array('class' => 'InputBoxFront', 'size' => '20')) ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td nowrap>
                                        <SPAN class=TextSmall>
                                                <?php echo Yii::t('lang','Billing_State_Province') ?>:&nbsp;&nbsp;
                                        </SPAN>
                                    </td>
                                    <td nowrap>                                        
                                            <?php echo CHtml::activeDropDownList($model, 'billingstate', array(
                                            'empty' => Yii::t('lang','Please_Select'),
                                            'International' => 'International',
                                            'Alabama' => 'Alabama',
                                            'Alaska' => 'Alaska',
                                            'Arizona' => 'Arizona',
                                            'Arkansas' => 'Arkansas',
                                            'California' => 'California',
                                            'Colorado' => 'Colorado',
                                            'Connecticut' => 'Connecticut',
                                            'Delaware' => 'Delaware',
                                            'District of Columbia' => 'DoC',
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
                                            'Alberta' => 'Alberta', 
                                            'British Columbia' => 'British Columbia', 
                                            'Manitoba' => 'Manitoba', 
                                            'New Brunswick' => 'New Brunswick', 
                                            'Newfoundland and Labrador' => 'N and L',
                                            'Nova Scotia' => 'Nova Scotia',
                                            'Ontario' => 'Ontario', 
                                            'Prince Edward Island' => 'Prince Edward', 
                                            'Quebec' => 'Quebec', 
                                            'Saskatchewan' => 'Saskatchewan',
                                            )); ?>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td nowrap>
                                        <SPAN class=TextSmall>
                                                <?php echo Yii::t('lang','Billing_Zipcode') ?>:&nbsp;&nbsp;
                                        </SPAN>
                                    </td>
                                    <td>
                                            <?php echo CHtml::activeTextField($model,'billingzip', array('class' => 'InputBoxFront', 'size' => '20', 'maxlength' => '100')) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td nowrap>
                                        <SPAN class=TextSmall>
                                                <?php echo Yii::t('lang','Billing_Country') ?>:&nbsp;&nbsp;
                                        </SPAN>
                                    </td>
                                    <td>
                                            <?php echo CHtml::activeDropDownList($model, 'billingcountry', array(
                                            'empty' => Yii::t('lang','Please_Select'),
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
                                            'British Virgin Islands' => 'BVI',
                                            'Brunei' => 'Brunei',
                                            'Bulgaria' => 'Bulgaria',
                                            'Burkina Faso' => 'Burkina Faso',
                                            'Cambodia' => 'Cambodia',
                                            'Cameroon' => 'Cameroon',
                                            'Canada' => 'Canada',
                                            'Cape Verde' => 'Cape Verde',
                                            'Cayman Islands' => 'Cayman Islands',
                                            'Central African Republic' => 'CAR',
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
                                            'Dominican Republic' => 'Dominican',
                                            'Dubai' => 'Dubai',
                                            'Ecuador' => 'Ecuador',
                                            'Egypt' => 'Egypt',
                                            'El Salvador' => 'El Salvador',
                                            'Equatorial Guinea' => 'EG',
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
                                            'St Kitts and Nevis' => 'St Kitts and Nevis',
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
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td width="400">
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td nowrap>
                                        <SPAN class=TextSmall>
                                            Payment Method:&nbsp;&nbsp;
                                        </SPAN>
                                    </td>

                                    <td><select size="1" name="payment_method" id="AddFunds_payment_method" onchange="endisCreditCard(this)">
                                            <option value="">-- Please Select --
                                            <option value="CreditCard">Credit Card
                                            <option value="PayPal">PayPal
                                        </select></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td nowrap>

                                        <SPAN class=TextSmall>
                                                <?php echo Yii::t('lang','Card_Number') ?>:&nbsp;&nbsp;
                                        </SPAN>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="cc" id="cc" onClick="currentCard()"> Use credit card on file (<b><?php echo $card; ?></b>)<br>
                                            or enter new CC #: &nbsp;<?php echo CHtml::activeTextField($model,'cardnumber', array('class' => 'InputBoxFront', 'size' => '20', 'maxlength' => '100')) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td nowrap>
                                        <SPAN class=TextSmall>
                                                <?php echo Yii::t('lang','Card_CVV_Code') ?>:&nbsp;&nbsp;
                                        </SPAN>
                                    </td>
                                    <td>

                                        <table cellspacing=0 cellpadding=0 border=0>
                                            <tr>
                                                <td>
                                                    <?php echo CHtml::activeTextField($model,'cvv', array('class' => 'InputBoxFront', 'size' => '2', 'maxlength' => '100')) ?>
                                                    <span class=TextTiny>(Last 3 digits on the back of your card)</span>
                                                    <?php if($model->getError('cvv')): ?>
                                                    <br>
                                                    <input type="checkbox" name="empty_cvv"> I have no CVV on my card
                                                    <?php endif; ?>
                                                </td>
                                                <td width=10>
                                                    <img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif">
                                                </td>
                                                <td>

                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <SPAN class=TextSmall>
                                                <?php echo Yii::t('lang','Card_Expiration') ?>:&nbsp;&nbsp;
                                        </SPAN>
                                    </td>

                                    <td>
                                            <?php echo CHtml::activeDropDownList($model, 'month', array(
                                            'empty' => Yii::t('lang','Please_Select'),
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
                                        <select class="SelectBoxSmall" name="year" id="AddFunds_year">
                                            <option value="">-- <?php echo Yii::t('lang','Please_Select') ?> --</option>
                                                <?php for($i=0;$i<6;$i++): ?>
                                            <option value="<?php echo date('Y')+$i; ?>" <?php echo(date('Y')+$i == $model->year) ? 'selected' : '' ?>><?php echo date('Y')+$i; ?></option>
                                                <?php endfor; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="2">                                        
                                        <table width="70%">
                                            <tr>
                                                <td width="30%">
                                                    <input type="reset" name="Clear" value="Clear">
                                                </td>
                                                <td align=right>
                                                    <input type="submit" name="Next" value="Process Payment">
                                                </td>

                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan=3>
                <span class=TextMedium>
                    <br>
                </span>
            </td>
        </tr>
    </table>
    <br>
    <p><b>Note</b>: When clicking on process payment click only ONCE and do not refresh the Next page.</p>
<?php echo CHtml::endForm(); ?>
<br>
<center>
    <!-- BEGIN SiteSeal -->
        <script language="javascript" src="https://cgnsecurity.com/ss/getCgnSS.php?d1=522601312&d2=1295275807" type="text/javascript"></script>
    <!-- END SiteSeal -->
</center>
<?php endif; ?>


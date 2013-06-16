<script language="JavaScript">
        <!--
        var cunterfname = 0;
        var cunterlname = 0;

        function gtfirstname(frm)
        {
            if(0 == cunterfname)
            {
                frm.form.billingfirstname.value = frm.form.first_name.value;
                cunterfname = 1;
            }

        }
        function gtlastname(frm)
        {
            if(0 == cunterlname)
            {
                frm.form.billinglastname.value = frm.form.last_name.value;
                cunterlname = 1;
            }

        }
        function endisCreditCard(frm){
            if(document.getElementById('payment_method').value == 'PayPal'){
                document.getElementById('alert_msg').style.display = 'block';
                frm.form.submit();
            } else {
                document.getElementById('cardnumber').disabled = false;
                document.getElementById('cvv').disabled = false;
                document.getElementById('month').disabled = false;
                document.getElementById('year').disabled = false;
            }
        }
        //-->
</script>
<center><h1>Email Readings</h1></center>
<table border=0 >
<form action="<?php echo Yii::app()->params['ssl_addr'] ?>api/rasp/emailreadings" method="post">   
    <?php echo CHtml::activeHiddenField($model, 'first_name'); ?>
    <?php echo CHtml::activeHiddenField($model, 'last_name'); ?>
    <?php echo CHtml::activeHiddenField($model, 'r_contact_street_address_1'); ?>
    <?php echo CHtml::activeHiddenField($model, 's_contact_street_address_2'); ?>
    <?php echo CHtml::activeHiddenField($model, 't_contact_city'); ?>
    <?php echo CHtml::activeHiddenField($model, 'u_contact_state_or_province'); ?>
    <?php echo CHtml::activeHiddenField($model, 'v_contact_country'); ?>
    <?php echo CHtml::activeHiddenField($model, 'w_contact_phone_daytime'); ?>
    <?php echo CHtml::activeHiddenField($model, 'z_contact_email_address'); ?>
    <?php echo CHtml::activeHiddenField($model, 'd_date_of_birth_Month1'); ?>
    <?php echo CHtml::activeHiddenField($model, 'e_date_of_birth_Date2'); ?>
    <?php echo CHtml::activeHiddenField($model, 'f_date_of_birth_YR3'); ?>
    <?php echo CHtml::activeHiddenField($model, 'h_time_of'); ?>
    <?php echo CHtml::activeHiddenField($model, 'i_place_of_birth'); ?>
    <?php echo CHtml::activeHiddenField($model, 'j_numerology_name'); ?>
    <?php echo CHtml::activeHiddenField($model, 'l_topic'); ?>
    <?php echo CHtml::activeHiddenField($model, 'reader_id'); ?>
    <?php echo CHtml::activeHiddenField($model, 'b_reading_type'); ?>
    <?php echo CHtml::activeHiddenField($model, 'm_special_instructions'); ?>
    <?php echo CHtml::activeHiddenField($model, 'n_additional_info'); ?>
    <input type="hidden" name="return_url" value="<?php echo $return_url; ?>">
<table border=0 >

<?php if($errors) { ?>
<tr>
<td colspan="3">
<center> <font style="font-size: large; color: red;">
You have some payment errors:<br />
<?php if(is_array($errors)) {
    foreach($errors as $error)
        echo " - ".$error[0].'<br />';
} else {
    echo $errors.'<br />';
}
?> <br /><br />
</font></center>
</td>
</tr>
<?php } ?>

        <tr>
            <td colspan=3>
                <center><font color="blue" face="Arial" size="3"><b>
                                Email Reading Order Form</b></font></center>
                <p align="center"><font color="blue" face="Arial" size="2">
                <b>Using this form, you will now be able to place your order for an Email Reading with us.<BR>All the payment information you enter at that point will be encrypted and can only be read by our advanced payment interface system. Your privacy is assured.</b>                    </font></p>
            </td>
        </tr>
        <tr>
            <td colspan=3>
                    Contact Information            </td>
        </tr>
        <tr>
            <td colspan=3>
                <font color="#ff0000">Fields marked with <b>*</b> are required</font>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td nowrap>
                    Payment Information *:
            </td>
            <td>
                <select class="SelectBox" name="payment_method" id="payment_method" onchange="endisCreditCard(this)">
                    <option value="">...</option>
                    <option value="CreditCard" <?php echo ($model->payment_method == 'CreditCard') ? 'selected' : ''; ?>>Credit Card</option>
                    <option value="PayPal" <?php echo ($model->payment_method == 'PayPal') ? 'selected' : ''; ?>>Paypal</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                    First Name on card:
            </td>
            <td>
                <input size="20" maxlength="50" class="InputBoxFront" name="billingfirstname" value="<?php echo $model->billingfirstname; ?>" onfocus="jvascript:gtfirstname(this);">
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                    Last Name on card:
            </td>
            <td>
                <input size="20" maxlength="50" class="InputBoxFront" name="billinglastname" value="<?php echo $model->billinglastname ?>" onfocus="jvascript:gtlastname(this);">
            </td>
        </tr>
        <tr>
            <td>
                <div id="alert_msg" style="display: none; position: absolute; margin-left: 100px; margin-top: 30px;">
                    <table bgcolor="#aaaaaa" border="0" cellpadding="1" cellspacing="0">
                        <tr>
                            <td>
                                <table bgcolor="white" border="0" cellpadding="4" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <td align="center" nowrap width="350" height="150">
                                                <h2> Redirecting to Paypal... </h2>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                     Billing Address:
            </td>
            <td>
                <input size="20" maxlength="50" class="InputBoxFront"  name="billingaddress" id="billingaddress" value="<?php echo $model->billingaddress ?>">
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                    Billing City:
            </td>
            <td>
                <input size="20" maxlength="50" class="InputBoxFront"  name="billingcity" id="billingcity" value="<?php echo $model->billingcity ?>">
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                    Billing State:
            </td>
            <td>
                <select class="SelectBox" name="billingstate" id="billingstate">
                    <option value="">-- Please Select --</option>
                    <option value="International">International</option>
                    <option value="Alabama">Alabama</option>
                    <option value="Alaska">Alaska</option>
                    <option value="Arizona">Arizona</option>
                    <option value="Arkansas">Arkansas</option>
                    <option value="California">California</option>
                    <option value="Colorado">Colorado</option>
                    <option value="Connecticut">Connecticut</option>
                    <option value="Delaware">Delaware</option>
                    <option value="District of Columbia">District of Columbia</option>
                    <option value="Florida">Florida</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Hawaii">Hawaii</option>
                    <option value="Idaho">Idaho</option>
                    <option value="Illinois">Illinois</option>
                    <option value="Indiana">Indiana</option>
                    <option value="Iowa">Iowa</option>
                    <option value="Kansas">Kansas</option>
                    <option value="Kentucky">Kentucky</option>
                    <option value="Louisiana">Louisiana</option>
                    <option value="Maine">Maine</option>
                    <option value="Maryland">Maryland</option>
                    <option value="Massachusetts">Massachusetts</option>
                    <option value="Michigan">Michigan</option>
                    <option value="Minnesota">Minnesota</option>
                    <option value="Mississippi">Mississippi</option>
                    <option value="Missouri">Missouri</option>
                    <option value="Montana">Montana</option>
                    <option value="Nebraska">Nebraska</option>
                    <option value="Nevada">Nevada</option>
                    <option value="New Hampshire">New Hampshire</option>
                    <option value="New Jersey">New Jersey</option>
                    <option value="New Mexico">New Mexico</option>
                    <option value="New York">New York</option>
                    <option value="North Carolina">North Carolina</option>
                    <option value="North Dakota">North Dakota</option>
                    <option value="Ohio">Ohio</option>
                    <option value="Oklahoma">Oklahoma</option>
                    <option value="Oregon">Oregon</option>
                    <option value="Pennsylvania">Pennsylvania</option>
                    <option value="Rhode Island">Rhode Island</option>
                    <option value="South Carolina">South Carolina</option>
                    <option value="South Dakota">South Dakota</option>
                    <option value="Tennessee">Tennessee</option>
                    <option value="Texas">Texas</option>
                    <option value="Utah">Utah</option>
                    <option value="Vermont">Vermont</option>
                    <option value="Virginia">Virginia</option>
                    <option value="Washington">Washington</option>
                    <option value="West Virginia">West Virginia</option>
                    <option value="Wisconsin">Wisconsin</option>
                    <option value="Wyoming">Wyoming</option>
                    <option value="Alberta">Alberta</option>
                    <option value="British Columbia">British Columbia</option>
                    <option value="Manitoba">Manitoba</option>
                    <option value="New Brunswick">New Brunswick</option>
                    <option value="Newfoundland and Labrador">Newfoundland and Labrador</option>
                    <option value="Nova Scotia">Nova Scotia</option>
                    <option value="Ontario">Ontario</option>
                    <option value="Prince Edward Island">Prince Edward Island</option>
                    <option value="Quebec">Quebec</option>
                    <option value="Saskatchewan">Saskatchewan</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                    Billing Zipcode:
            </td>
            <td>
                <input size="20" maxlength="50" class="InputBoxFront"  name="billingzip" id="billingzip" value="<?php echo $model->billingzip ?>">
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                    Billing Country:
            </td>
            <td>
                <select class="SelectBox" name="billingcountry" id="billingcountry">
                    <option value="">-- Please Select --</option>
                    <option value="Albania">Albania</option>
                    <option value="Algeria">Algeria</option>
                    <option value="Angola">Angola</option>
                    <option value="Anguilla">Anguilla</option>
                    <option value="Antigua">Antigua</option>
                    <option value="Argentina">Argentina</option>
                    <option value="Armenia">Armenia</option>
                    <option value="Aruba">Aruba</option>
                    <option value="Australia">Australia</option>
                    <option value="Austria">Austria</option>
                    <option value="Azerbejan">Azerbejan</option>
                    <option value="Bahamas">Bahamas</option><option value="Bahrain">Bahrain</option>
                    <option value="Bangladesh">Bangladesh</option>
                    <option value="Barbados">Barbados</option>
                    <option value="Barbuda">Barbuda</option>
                    <option value="Belarus">Belarus</option>
                    <option value="Belgium">Belgium</option>
                    <option value="Belize">Belize</option>
                    <option value="Bermuda">Bermuda</option>
                    <option value="Bolivia">Bolivia</option>
                    <option value="Bonaire">Bonaire</option>
                    <option value="Bosnia">Bosnia</option>
                    <option value="Botswana">Botswana</option>
                    <option value="Brazil">Brazil</option>
                    <option value="British Virgin Islands">British Virgin Islands</option>
                    <option value="Brunei">Brunei</option>
                    <option value="Bulgaria">Bulgaria</option>
                    <option value="Burkina Faso">Burkina Faso</option>
                    <option value="Cambodia">Cambodia</option>
                    <option value="Cameroon">Cameroon</option>
                    <option value="Canada">Canada</option>
                    <option value="Cape Verde">Cape Verde</option>
                    <option value="Cayman Islands">Cayman Islands</option>
                    <option value="Central African Republic">Central African Republic</option>
                    <option value="Central America">Central America</option>
                    <option value="Chad">Chad</option>
                    <option value="Chile">Chile</option>
                    <option value="China">China</option>
                    <option value="China (Beijing)">China (Beijing)</option>
                    <option value="China (Shanghai)">China (Shanghai)</option>
                    <option value="China(GuangZhou)">China(GuangZhou)</option>
                    <option value="Colombia">Colombia</option>
                    <option value="Comoros">Comoros</option>
                    <option value="Congo Brazzaville">Congo Brazzaville</option>
                    <option value="Costa Rica">Costa Rica</option>
                    <option value="Croatia">Croatia</option>
                    <option value="Curacao">Curacao</option>
                    <option value="Cyprus">Cyprus</option>
                    <option value="Czech Republic">Czech Republic</option>
                    <option value="Denmark">Denmark</option>
                    <option value="Djibouti">Djibouti</option>
                    <option value="Dominican Republic">Dominican Republic</option>
                    <option value="Dubai">Dubai</option>
                    <option value="Ecuador">Ecuador</option>
                    <option value="Egypt">Egypt</option>
                    <option value="El Salvador">El Salvador</option>
                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                    <option value="Eritrea">Eritrea</option>
                    <option value="Estonia">Estonia</option>
                    <option value="Ethiopia">Ethiopia</option>
                    <option value="Finland">Finland</option>
                    <option value="France">France</option>
                    <option value="Gabon">Gabon</option>
                    <option value="Gambia">Gambia</option>
                    <option value="Germany">Germany</option>
                    <option value="Ghana">Ghana</option>
                    <option value="Greece">Greece</option>
                    <option value="Grenada">Grenada</option>
                    <option value="Guadeloupe">Guadeloupe</option>
                    <option value="Guatemala">Guatemala</option>
                    <option value="Haiti">Haiti</option>
                    <option value="Holland &amp; Luxembourg">Holland &amp; Luxembourg</option>
                    <option value="Honduras">Honduras</option>
                    <option value="Hong Kong">Hong Kong</option>
                    <option value="Hungary">Hungary</option>
                    <option value="Iceland">Iceland</option>
                    <option value="India">India</option>
                    <option value="Indonesia">Indonesia</option>
                    <option value="Iran">Iran</option>
                    <option value="Iraq">Iraq</option>
                    <option value="Ireland">Ireland</option>
                    <option value="Israel">Israel</option>
                    <option value="Italy">Italy</option>
                    <option value="Ivory Coast">Ivory Coast</option>
                    <option value="Jamaica">Jamaica</option>
                    <option value="Japan">Japan</option>
                    <option value="Jordan">Jordan</option>
                    <option value="Kenya">Kenya</option>
                    <option value="Kirgizstan">Kirgizstan</option>
                    <option value="Korea">Korea</option>
                    <option value="Kuwait">Kuwait</option>
                    <option value="Laos">Laos</option>
                    <option value="Latvia">Latvia</option>
                    <option value="Lebanon">Lebanon</option>
                    <option value="Lesotho">Lesotho</option>
                    <option value="Liberia">Liberia</option>
                    <option value="Libya">Libya</option>
                    <option value="Lithuani">Lithuani</option>
                    <option value="Lithuania">Lithuania</option>
                    <option value="Luxembourg">Luxembourg</option>
                    <option value="Macau">Macau</option>
                    <option value="Macedonia">Macedonia</option>
                    <option value="Madagascar">Madagascar</option>
                    <option value="Malawi">Malawi</option>
                    <option value="Malaysia">Malaysia</option>
                    <option value="Malta">Malta</option>
                    <option value="Martinique">Martinique</option>
                    <option value="Mauritania">Mauritania</option>
                    <option value="Mauritius">Mauritius</option>
                    <option value="Mexico">Mexico</option>
                    <option value="Moldova">Moldova</option>
                    <option value="Monserrat">Monserrat</option>
                    <option value="Morocco">Morocco</option>
                    <option value="Mozambique">Mozambique</option>
                    <option value="Myanmar">Myanmar</option>
                    <option value="Namibia">Namibia</option>
                    <option value="Nepal">Nepal</option>
                    <option value="Netherlands">Netherlands</option>
                    <option value="New Zealand">New Zealand</option>
                    <option value="Nicaragua">Nicaragua</option>
                    <option value="Niger">Niger</option>
                    <option value="Nigeria">Nigeria</option>
                    <option value="Norway">Norway</option>
                    <option value="Oman">Oman</option>
                    <option value="Pakistan">Pakistan</option>
                    <option value="Panama">Panama</option>
                    <option value="Paraguay">Paraguay</option>
                    <option value="Peru">Peru</option>
                    <option value="Philippines">Philippines</option>
                    <option value="Poland">Poland</option>
                    <option value="Portugal">Portugal</option>
                    <option value="Puerto Rico">Puerto Rico</option>
                    <option value="Quatar">Quatar</option>
                    <option value="Romania">Romania</option>
                    <option value="Russia">Russia</option>
                    <option value="Rwanda">Rwanda</option>
                    <option value="Sao Tome &amp; Principe">Sao Tome &amp; Principe</option>
                    <option value="Saudi Arabia">Saudi Arabia</option>
                    <option value="Senegal">Senegal</option>
                    <option value="Seychelles">Seychelles</option>
                    <option value="Sierra Leone">Sierra Leone</option>
                    <option value="Singapore">Singapore</option>
                    <option value="Slovakia">Slovakia</option>
                    <option value="Slovenia">Slovenia</option>
                    <option value="Somalia">Somalia</option>
                    <option value="South Africa">South Africa</option>
                    <option value="Spain">Spain</option>
                    <option value="Sri Lanka">Sri Lanka</option>
                    <option value="St Kitts and Nevis">St Kitts and Nevis</option>
                    <option value="St Lucia">St Lucia</option>
                    <option value="St Vincent and the Grenadines">St Vincent and the Grenadines</option>
                    <option value="St. Barts">St. Barts</option>
                    <option value="St. Eustatius">St. Eustatius</option>
                    <option value="St. Martin and St. Maarten">St. Martin and St. Maarten</option>
                    <option value="Sudan">Sudan</option>
                    <option value="Swaziland">Swaziland</option>
                    <option value="Sweden">Sweden</option>
                    <option value="Switzerland">Switzerland</option>
                    <option value="Syria">Syria</option>
                    <option value="Taiwan">Taiwan</option>
                    <option value="Tajikistan">Tajikistan</option>
                    <option value="Tanzania">Tanzania</option>
                    <option value="Thailand">Thailand</option>
                    <option value="Togo">Togo</option>
                    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                    <option value="Tunisia">Tunisia</option>
                    <option value="Turkey">Turkey</option>
                    <option value="Turkmenistan">Turkmenistan</option>
                    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                    <option value="UAE (United Arab Emirates)">UAE (United Arab Emirates)</option>
                    <option value="Uganda">Uganda</option>
                    <option value="Ukraine">Ukraine</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="United States">United States</option>
                    <option value="Upper Volta">Upper Volta</option>
                    <option value="Uruguay">Uruguay</option>
                    <option value="Uzbekistan">Uzbekistan</option>
                    <option value="Venezuela">Venezuela</option>
                    <option value="Vietnam">Vietnam</option>
                    <option value="Western Sahara">Western Sahara</option>
                    <option value="Yugoslavia">Yugoslavia</option>
                    <option value="Zaire">Zaire</option>
                    <option value="Zambia">Zambia</option>
                    <option value="Zimbabwe">Zimbabwe</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                    Card Number:
            </td>
            <td>
                <input size="30" class="InputBoxFront"  maxlength="50" name="cardnumber" id="cardnumber" value="">
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                    Card CVV Code:
            </td>
            <td>
                <input size="5" class="InputBoxFront"  maxlength="50" name="cvv" id="cvv" value="<?php echo $model->cvv ?>">
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                    Card Expiration:
            </td>
            <td>
                <select class="SelectBoxSmall" name="month" id="month">
                    <option value="">-- Please Select --</option>
                                            <option value="01"
                                    >01                    </option>
                                            <option value="02"
                                    >02                    </option>
                                            <option value="03"
                                    >03                    </option>
                                            <option value="04"
                                    >04                    </option>
                                            <option value="05"
                                    >05                    </option>
                                            <option value="06"
                                    >06                    </option>
                                            <option value="07"
                                    >07                    </option>
                                            <option value="08"
                                    >08                    </option>
                                            <option value="09"
                                    >09                    </option>
                                            <option value="10"
                                    >10                    </option>
                                            <option value="11"
                                    >11                    </option>
                                            <option value="12"
                                    >12                    </option>
                                        </select>
                <select class="SelectBoxSmall" name="year" id="year">
                    <option value="">-- Please Select --</option>
                    <?php for($i=0;$i<6;$i++): ?>
                    <option value="<?php echo date('Y')+$i; ?>">
                                <?php echo date('Y')+$i; ?>
                    </option>
                        <?php endfor; ?>
                                        </select>
            </td>
        </tr>
        <tr>
            <td><img src="<?php echo Yii::app()->params['http_addr']; ?>images/oneoperatoricon_small.gif" width=17 height=17 border=0 >&nbsp;</td>
            <td width="500">
                <SPAN class=TextSmall>
                        First Name *<br>(Credit Card Billing First Name):
                </SPAN>
            </td>
            <td>
                <b><?php echo $model->first_name; ?></b>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <SPAN class=TextSmall>
                        Last Name *:
                </SPAN>
            </td>
            <td>
                <b><?php echo $model->last_name; ?></b>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td nowrap>
                <SPAN class=TextSmall>
                        Street Address <em>All Address Fields- Optional)(1)</em>:
                </SPAN>
            </td>
            <td><b><?php echo $model->r_contact_street_address_1; ?></b></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td nowrap>
                <SPAN class=TextSmall>
                        Street Address <em>(2)</em>:
                </SPAN>
            </td>
            <td>
                    <b><?php echo $model->s_contact_street_address_2; ?></b>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td nowrap>
                <SPAN class=TextSmall>
                        Town / City:
                </SPAN>
            </td>
            <td>
                    <b><?php echo $model->t_contact_city; ?></b>                    
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td nowrap>
                <SPAN class=TextSmall>
                        State/Province:
                </SPAN>
            </td>
            <td>
                <b><?php echo $model->u_contact_state_or_province; ?></b>        
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td nowrap>
                <SPAN class=TextSmall>
                        Country:
                </SPAN>
            </td>
            <td>
                <b><?php echo $model->v_contact_country; ?></b>      
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td nowrap>
                <SPAN class=TextSmall>
                        Phone Number:
                </SPAN>
            </td>
            <td>
                <b><?php echo $model->w_contact_phone_daytime; ?></b>      
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td nowrap>
                <SPAN class=TextSmall>
                        Email address <em>(important!)</em> *:
                </SPAN>
            </td>
            <td>
                <b><?php echo $model->z_contact_email_address; ?></b>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td nowrap>
                <SPAN class=TextSmall>
                        Sex (at time of birth) *:
                </SPAN>
            </td>
            <td>
                <b><?php echo $model->c_sex; ?></b>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td nowrap>
                <SPAN class=TextSmall>
                        Your Date of Birth: <em>(Very Important!)(mm/dd/yy)</em>:
                </SPAN>
            </td>
            <td>
                <b><?php echo $model->d_date_of_birth_Month1.'/'.$model->e_date_of_birth_Date2.'/'.$model->f_date_of_birth_YR3; ?></b>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <SPAN class=TextSmall>
                        If ordering an Astrology Reading- Very Important!: The time you were born:
                </SPAN>
            </td>
            <td>
                    <b><?php echo $model->h_time_of; ?></b>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <SPAN class=TextSmall>
                        If ordering an Astrology Reading- Very Important!:Your place of birth<em>(City, State, Country)</em>:
                </SPAN>
            </td>
            <td>
                <b><?php echo $model->i_place_of_birth; ?></b>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <SPAN class=TextSmall>
                        If ordering a Numerology Reading enter the name and\or date of birth you wish the Reading done for (usually the name on birth certificate or the name being used now):
                </SPAN>
            </td>
            <td>
                <table>
                    <tr>
                        <td>Name:</td>
                        <td>
                            <b><?php echo $model->j_numerology_name; ?></b>                                
                        </td>
                    </tr>
                    <tr>
                        <td width="77">Date of Birth:</td>
                        <td>
                            <b><?php echo $model->k_numerology_date; ?></b>                                
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <SPAN class=TextSmall>
                        In one or two words only, please enter the topic that concerns you the most <em>(optional)</em><br>(I.E.: Love, Relationship, Health, Career, $$$$, etc.):
                </SPAN>
            </td>
            <td>
                <b><?php echo $model->l_topic; ?></b>                   
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <hr width="90%">
            </td>
            <td>
                <hr width="90%">
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <SPAN class=TextSmall>
                        If you would like to choose your own personal Psychic, Reader, Astrologer, or Numerologist                        from the list of our Readers,
                        please check the button at the right of this window and type in their name here: (If you leave this blank, we will choose the Reader for you) *:
                </SPAN>
            </td>
            <td>
                <b><?php 
                foreach($tariffs['reader_spec'] as $tariff)
                    if ($tariff['rr_record_id'] == $model->reader_id)
                    {
                        echo $tariff['name'];
                        break;
                    }                        
                ?></b>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <SPAN class=TextSmall>
                        Please select the type of Reading you would like to have from the following drop-down menu. *:
                </SPAN>
            </td>
            <td>
                <b><?php echo $model->b_reading_type; ?></b>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <SPAN class=TextSmall>
                        Special instructions:
                </SPAN>
            </td>
            <td>
                <b><?php echo $model->m_special_instructions; ?></b>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <SPAN class=TextSmall>
                        Please enter any additional information / questions here:
                </SPAN>
            </td>
            <td>
                <b><?php echo $model->n_additional_info; ?></b>                    
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <hr width="90%">
            </td>
            <td>
                <hr width="90%">
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                    New Clients:
            </td>
            <td>
                    Affiliate: <?php echo CHtml::activeHiddenField($model, 'affiliate'); ?> <?php echo $model->affiliate; ?>            </td>
        </tr>
        <tr>
            <td colspan="3">
                    **NOTICE: Any individual who chooses to submit &quot,Fraudulent&quot, Credit Card or Checking Account information will be prosecuted to the full extent allowable by law.<br>
                    Please click the &quot,SEND EMAIL READING ORDER&quot, button below to submit your order.
Processing might take a few seconds. Then follow the links to the correct payment page for either Credit Card or PayPal.            </td>
        </tr>
        <tr>
            <td colspan="3" align="right">
                <input name="ze_SUBMIT" value="SEND EMAIL READING ORDER" type="SUBMIT"> 
                <input name="Clear" value="CLEAR ORDER FORM" type="RESET" onClick="javascript:document.location.href='<?php echo $return_url; ?>'">
            </td>
        </tr>
    </table>
</form>
<td class="maintexttd" valign="top">

    <script language="JavaScript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/common.js" type="text/javascript"></script>
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
    <table border="0" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxldevelop1.gif" width="25" height="31" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="25" height="31"></td>
                <td width="100%">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tbody>
                            <tr>
                                <td background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxdevelop1.gif" valign="top" nowrap="nowrap">
                                    <table background="" border="0" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr height="11">
                                                <td width="1"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="1" height="11"></td>
                                            </tr>
                                            <tr>
                                                <td nowrap="nowrap"><span class="TextButton"><font color="#42595a"><b><?php echo Yii::t('lang','Email_readings') ?></b></font></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxudevelop1.gif" width="18" height="31" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="18" height="31"></td>
                                <td background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxuu1.gif" width="100%" height="31" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" height="31"></td>
                            </tr>
                    </table>
                </td>
                <td width=14 height=31 nowrap background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxrdevelop1.gif"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width=14 height=31></td>
                <td nowrap width=1 ><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width=1></td>
                <td width=1 nowrap><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" ></td>
            </tr>
            <tr>
                <td width=25 nowrap background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxl1.gif"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width=25></td>
                <td  bgcolor="#F7F7F7"><SPAN class=TextTiny><br></SPAN>
                    <?php if(isset($success)): ?>
                    <?php $this->widget('SuccessMessage', array('message' => Yii::t('lang','reading_request_8'))); ?>
                </td>
                <td nowrap width=14 nowrap background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxright1.gif"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width=14></td>
                <td nowrap width=1 ><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width=1></td>
            </tr>
            <tr>
                <td background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxlddevelop1.gif" width="15" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="15" height="12"></td>
                <td background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxdddevelop1.gif" width="100%" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" height="12"></td>
                <td background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxrddevelop1.gif" width="14" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="14" height="12"></td>
                <td width="1" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="1" height="12"></td>
                <td width="1" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="1" height="12"></td>
            </tr>
        </tbody>
    </table>
    <?php else: ?>
        <?php if(isset($errors)): ?>
            <?php $this->widget('ErrorMessage', array('message' => $errors)); ?>
        <?php endif; ?>
        <?php echo CHtml::beginForm(); ?>
    <table border=0  width=480>
        <tr>
            <td colspan=3>
                <center><font color="purple" face="Arial" size="3"><b>
                                <?php echo Yii::t('lang','reading_request_9') ?></b></font></center>
                <p align="center"><font color="purple" face="Arial" size="2">
                            <?php echo Yii::t('lang','reading_request_10') ?>
                    </font></p>
            </td>
        </tr>
        <tr>
            <td colspan=3>
                    <?php echo Yii::t('lang','Contact_Information') ?>
            </td>
        </tr>
        <tr>
            <td colspan=3>
                <font color="#ff0000"><?php echo Yii::t('lang','reading_request_1') ?></font>
            </td>
        </tr>
        <tr>
            <td><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/oneoperatoricon_small.gif" width=17 height=17 border=0 >&nbsp;</td>
            <td>
                <SPAN class=TextSmall>
                        <?php echo Yii::t('lang','First_Name') ?> *<br>(<?php echo Yii::t('lang','register_msg_16') ?>):
                </SPAN>
            </td>
            <td>
                <input class="InputBoxFront" size="35" maxlength="50" name="first_name" value="<?php echo $_POST['first_name']; ?>">
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <SPAN class=TextSmall>
                        <?php echo Yii::t('lang','Last_Name') ?> *:
                </SPAN>
            </td>
            <td>
                <input class="InputBoxFront" size="35" maxlength="50" name="last_name" value="<?php echo $_POST['last_name']; ?>">
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td nowrap>
                <SPAN class=TextSmall>
                        <?php echo Yii::t('lang','Street_Address') ?> <em><?php echo Yii::t('lang','reading_request_11') ?></em>:
                </SPAN>
            </td>
            <td>
                    <?php echo CHtml::activeTextField($model,'r_contact_street_address_1', array('class' => 'InputBoxFront', 'size' => '35', 'maxlength' => '50')) ?>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td nowrap>
                <SPAN class=TextSmall>
                        <?php echo Yii::t('lang','Street_Address') ?> <em>(2)</em>:
                </SPAN>
            </td>
            <td>
                    <?php echo CHtml::activeTextField($model,'s_contact_street_address_2', array('class' => 'InputBoxFront', 'size' => '35')) ?>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td nowrap>
                <SPAN class=TextSmall>
                        <?php echo Yii::t('lang','Town_City') ?>:
                </SPAN>
            </td>
            <td>
                    <?php echo CHtml::activeTextField($model,'t_contact_city', array('class' => 'InputBoxFront', 'size' => '35')) ?>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td nowrap>
                <SPAN class=TextSmall>
                        <?php echo Yii::t('lang','State_Province') ?>:
                </SPAN>
            </td>
            <td>
                    <?php echo CHtml::activeTextField($model,'u_contact_state_or_province', array('class' => 'InputBoxFront', 'size' => '35')) ?>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td nowrap>
                <SPAN class=TextSmall>
                        <?php echo Yii::t('lang','Country') ?>:
                </SPAN>
            </td>
            <td>
                    <?php echo CHtml::activeTextField($model,'v_contact_country', array('class' => 'InputBoxFront', 'size' => '25')) ?>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td nowrap>
                <SPAN class=TextSmall>
                        <?php echo Yii::t('lang','Phone_Number') ?>:
                </SPAN>
            </td>
            <td>
                    <?php echo CHtml::activeTextField($model,'w_contact_phone_daytime', array('class' => 'InputBoxFront', 'size' => '25')) ?>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td nowrap>
                <SPAN class=TextSmall>
                        <?php echo Yii::t('lang','Fax_number') ?> <em>(<?php echo Yii::t('lang','optional') ?>)</em>:
                </SPAN>
            </td>
            <td>
                    <?php echo CHtml::activeTextField($model,'y_contact_fax_number', array('class' => 'InputBoxFront', 'size' => '25')) ?>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td nowrap>
                <SPAN class=TextSmall>
                        <?php echo Yii::t('lang','forget_email') ?> <em>(<?php echo Yii::t('lang','important') ?>!)</em> *:
                </SPAN>
            </td>
            <td>
                <input class="InputBoxFront" size="35" name="z_contact_email_address" value="<?php echo $_POST['z_contact_email_address']; ?>">
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td nowrap>
                <SPAN class=TextSmall>
                        <?php echo Yii::t('lang','Sex') ?> (<?php echo Yii::t('lang','at_time_of_birth') ?>) *:
                </SPAN>
            </td>
            <td>
                <table>
                    <tr>
                        <td><?php echo Yii::t('lang','Male') ?>:</td>
                        <td><input value="Male" name="c_sex" id="c_sex" type="radio"></td>
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('lang','Female') ?>:</td>
                        <td><input value="Female" name="c_sex" id="c_sex" type="radio"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td nowrap>
                <SPAN class=TextSmall>
                        <?php echo Yii::t('lang','Your_Date_of_Birth') ?>: <em>(<?php echo Yii::t('lang','Very_Important') ?>!)(<?php echo Yii::t('lang','mm/dd/yy') ?>)</em>:
                </SPAN>
            </td>
            <td>
                    <?php echo CHtml::activeTextField($model,'d_date_of_birth_Month1', array('class' => 'InputBoxFront', 'size' => '2')) ?>
                / <?php echo CHtml::activeTextField($model,'e_date_of_birth_Date2', array('class' => 'InputBoxFront', 'size' => '2')) ?>
                / <?php echo CHtml::activeTextField($model,'f_date_of_birth_YR3', array('class' => 'InputBoxFront', 'size' => '2')) ?>
                *<br>
                    <?php echo Yii::t('lang','reading_request_12') ?>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <SPAN class=TextSmall>
                        <?php echo Yii::t('lang','reading_request_13') ?>: <?php echo Yii::t('lang','reading_request_14') ?>:
                </SPAN>
            </td>
            <td>
                    <?php echo CHtml::activeTextField($model,'g_birth_time', array('class' => 'InputBoxFront', 'size' => '20')) ?>
                <br>
                    <?php echo Yii::t('lang','AM') ?>: <input value="AM" name="EmailQuestions[h_time_of]" id="EmailQuestions_h_time_of" type="radio">
                <br>
                    <?php echo Yii::t('lang','PM') ?>: <input value="PM" name="EmailQuestions[h_time_of]" id="EmailQuestions_h_time_of" type="radio">
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <SPAN class=TextSmall>
                        <?php echo Yii::t('lang','reading_request_13') ?>:<?php echo Yii::t('lang','Your_place_of_birth') ?><em>(<?php echo Yii::t('lang','City') ?>, <?php echo Yii::t('lang','State') ?>, <?php echo Yii::t('lang','Country') ?>)</em>:
                </SPAN>
            </td>
            <td>
                    <?php echo CHtml::activeTextField($model,'i_place_of_birth', array('class' => 'InputBoxFront', 'size' => '35')) ?>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <SPAN class=TextSmall>
                        <?php echo Yii::t('lang','reading_request_15') ?>:
                </SPAN>
            </td>
            <td>
                <table>
                    <tr>
                        <td><?php echo Yii::t('lang','Name') ?>:</td>
                        <td>
                                <?php echo CHtml::activeTextField($model,'j_numerology_name', array('class' => 'InputBoxFront', 'size' => '30')) ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('lang','Date_of_Birth') ?>:</td>
                        <td>
                                <?php echo CHtml::activeTextField($model,'k_numerology_date', array('class' => 'InputBoxFront', 'size' => '20')) ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <SPAN class=TextSmall>
                        <?php echo Yii::t('lang','reading_request_16') ?> <em>(<?php echo Yii::t('lang','optional') ?>)</em><br>(<?php echo Yii::t('lang','reading_request_17') ?>):
                </SPAN>
            </td>
            <td>
                <input class="InputBoxFront" size="35" name="l_topic" value="">
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
                        <?php echo Yii::t('lang','reading_request_18_1') ?>
                        <?php echo Yii::t('lang','reading_request_18_2') ?>,
                        <?php echo Yii::t('lang','reading_request_18_3') ?> *:
                </SPAN>
            </td>
            <td>
                <select class="SelectBox" name="reader_id">
                    <option value=""><?php echo Yii::t('lang','reading_request_20') ?></option>
                        <?php foreach($tariffs['reader_spec'] as $tariff): ?>
                    <option value="<?php echo $tariff['rr_record_id']; ?>" <?php echo (($tariff['rr_record_id'] == $_POST['reader_id'])||($tariff['rr_record_id'] == $_GET['reader_id'])) ? 'selected' : '' ?>><?php echo $tariff['name']; ?>
                                <?php if($tariff['special'] != '0.00'): ?>
                        (<?php echo Yii::t('lang','Special_Price') ?> $<?php echo $tariff['special']; ?>)</option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <SPAN class=TextSmall>
                        <?php echo Yii::t('lang','reading_request_19') ?> *:
                </SPAN>
            </td>
            <td>
                <select class="SelectBox" name="b_reading_type">
                    <option value=""><?php echo Yii::t('lang','reading_request_20') ?></option>
                    <option value="SPECIAL" <?php echo ($_POST['b_reading_type'] == 'SPECIAL') ? 'selected' : '' ?>><?php echo Yii::t('lang','SPECIAL') ?></option>
                        <?php foreach($tariffs['question'] as $key => $val): ?>
                    <option value="<?php echo $key ?>" <?php echo ($key == $_POST['b_reading_type']) ? 'selected' : '' ?>><?php echo $val['title']; ?> - <?php echo $val['price']; ?></option>
                        <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <SPAN class=TextSmall>
                        <?php echo Yii::t('lang','Special_instructions') ?>:
                </SPAN>
            </td>
            <td>
                    <?php echo CHtml::activeTextArea($model,'m_special_instructions', array('class' => 'InputBoxFront', 'rows' => '3', 'cols' => '30', 'size' => '1024')) ?>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <SPAN class=TextSmall>
                        <?php echo Yii::t('lang','reading_request_21') ?>:
                </SPAN>
            </td>
            <td>
                    <?php echo CHtml::activeTextArea($model,'n_additional_info', array('class' => 'InputBoxFront', 'rows' => '5', 'cols' => '30', 'size' => '1024')) ?>
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
            <td nowrap>
                    <?php echo Yii::t('lang','Payment_Information') ?> *:
            </td>
            <td>
                <select class="SelectBox" name="payment_method" id="payment_method" onchange="endisCreditCard(this)">
                    <option value="">...</option>
                    <option value="CreditCard"><?php echo Yii::t('lang','Credit_Card') ?></option>
                    <option value="PayPal"><?php echo Yii::t('lang','PayPal') ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                    <?php echo Yii::t('lang','First_Name_on_card') ?>:
            </td>
            <td>
                <input size="20" maxlength="50" name="billingfirstname" value="<?php echo $_POST['billingfirstname'] ?>" onfocus="jvascript:gtfirstname(this);">
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                    <?php echo Yii::t('lang','Last_Name_on_card') ?>:
            </td>
            <td>
                <input size="20" maxlength="50" name="billinglastname" value="<?php echo $_POST['billinglastname'] ?>" onfocus="jvascript:gtlastname(this);">
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
            <td>&nbsp;</td>
            <td>
                    <?php echo Yii::t('lang','Billing_Address') ?>:
            </td>
            <td>
                <input size="20" maxlength="50" name="billingaddress" id="billingaddress" value="<?php echo $_POST['billingaddress'] ?>">
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                    <?php echo Yii::t('lang','Billing_City') ?>:
            </td>
            <td>
                <input size="20" maxlength="50" name="billingcity" id="billingcity" value="<?php echo $_POST['billingcity'] ?>">
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                    <?php echo Yii::t('lang','Billing_State') ?>:
            </td>
            <td>
                <select class="SelectBox" name="billingstate" id="billingstate">
                    <option value="">-- <?php echo Yii::t('lang','Please_Select') ?> --</option>
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
                    <?php echo Yii::t('lang','Billing_Zipcode') ?>:
            </td>
            <td>
                <input size="20" maxlength="50" name="billingzip" id="billingzip" value="">
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                    <?php echo Yii::t('lang','Billing_Country') ?>:
            </td>
            <td>
                <select class="SelectBox" name="billingcountry" id="billingcountry">
                    <option value="">-- <?php echo Yii::t('lang','Please_Select') ?> --</option>
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
                    <?php echo Yii::t('lang','Card_Number') ?>:
            </td>
            <td>
                <input size="30" maxlength="50" name="cardnumber" id="cardnumber" value="<?php echo $_POST['cardnumber'] ?>">
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                    <?php echo Yii::t('lang','Card_CVV_Code') ?>:
            </td>
            <td>
                <input size="5" maxlength="50" name="cvv" id="cvv" value="<?php echo $_POST['cvv'] ?>">
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                    <?php echo Yii::t('lang','Card_Expiration') ?>:
            </td>
            <td>
                <select class="SelectBoxSmall" name="month" id="month">
                    <option value="">-- <?php echo Yii::t('lang','Please_Select') ?> --</option>
                        <?php for($i=1;$i<=12;$i++): ?>
                    <option value="<?php echo ($i<10) ? '0'.$i : $i ?>"
                                    <?php echo ($i == $_POST['month']) ? 'selected' : '' ?>><?php echo ($i<10) ? '0'.$i : $i ?>
                    </option>
                        <?php endfor; ?>
                </select>
                <select class="SelectBoxSmall" name="year" id="year">
                    <option value="">-- <?php echo Yii::t('lang','Please_Select') ?> --</option>
                        <?php for($i=0;$i<6;$i++): ?>
                    <option value="<?php echo date('Y')+$i; ?>" <?php echo (date('Y')+$i == $_POST['year']) ? 'selected' : '' ?>>
                                <?php echo date('Y')+$i; ?>
                    </option>
                        <?php endfor; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                    <?php echo Yii::t('lang','New_clients') ?>:
            </td>
            <td>
                    <?php echo Yii::t('lang','Affiliate') ?>: <input type="hidden" name="affiliate" value="<?php echo $affiliate; ?>"> <?php echo $affiliate; ?>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                    <?php echo Yii::t('lang','reading_request_22') ?><br>
                    Please click the ",SEND EMAIL READING ORDER", button below to submit your order. Processing might take a few seconds. Then follow the links to the correct payment page for either Credit Card or PayPal. 
            </td>
        </tr>
        <tr>
            <td colspan="3" align="right">
                <input name="ze_SUBMIT" value="SEND EMAIL READING ORDER" type="SUBMIT"> <input name="Clear" value="CLEAR ORDER FORM" type="RESET">
            </td>
        </tr>
    </table>
        <?php echo CHtml::endForm(); ?>
</td>
<td nowrap width=14 nowrap background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxright1.gif"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width=14></td>
<td nowrap width=1 ><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width=1></td>
</tr>
<tr>
    <td background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxlddevelop1.gif" width="15" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="15" height="12"></td>
    <td background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxdddevelop1.gif" width="100%" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" height="12"></td>
    <td background="<?php echo Yii::app()->params['ssl_addr']; ?>images/index_files/boxrddevelop1.gif" width="14" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="14" height="12"></td>
    <td width="1" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="1" height="12"></td>
    <td width="1" height="12" nowrap="nowrap"><img src="<?php echo Yii::app()->params['ssl_addr']; ?>images/transp.gif" width="1" height="12"></td>
</tr>
</tbody>
</table>
<br>
<center>
    <!-- BEGIN SiteSeal -->
        <script language="javascript" src="https://cgnsecurity.com/ss/getCgnSS.php?d1=522601312&d2=1295275807" type="text/javascript"></script>
    <!-- END SiteSeal -->
</center>
<?php endif; ?>

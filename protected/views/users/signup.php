<script type="text/javascript">
function OnCancel()
{
	window.location.href = "chatwelcome.php";
}

function OnUpdate()
{
	x = 0
	p = 0
	msgStr = "<?php echo Yii::t('lang','chataddfunds_msg_1');?>:\n\n"
	Experience = ""
	ReplyResult = ""

	ver = "ie";
	if (ver == "nscp") {
		d = document.M10.document;
	}
	else {
		d = document;
	}

	if (d.forms["Signup"].name.value=="")
	{
		x = 1;
		msgStr += "* <?php echo Yii::t('lang','register_msg_1');?>.\n";
	}

	if (d.forms["Signup"].login.value=="")
	{
		x = 1;
		msgStr += "* <?php echo Yii::t('lang','register_msg_2');?>.\n";
	}

	if (d.forms["Signup"].emailaddress.value=="")
	{
		x = 1;
		msgStr += "* <?php echo Yii::t('lang','register_msg_3');?>\n";
	}
	else
	if (!isEmailAddr(d.forms["Signup"].emailaddress.value))
	{
		x = 1;
		msgStr += "* <?php echo Yii::t('lang','register_msg_4');?>.\r\n";
	}
	else
	if (d.forms["Signup"].emailaddress.value != d.forms["Signup"].emailaddress2.value)
	{
		x = 1;
		msgStr += "* <?php echo Yii::t('lang','register_msg_5') ;?>.\n";
	}
	if (d.forms["Signup"].day[0].selected ||
	d.forms["Signup"].month[0].selected ||
	d.forms["Signup"].year[0].selected)
	{
		x = 1;
		msgStr += "* <?php echo Yii::t('lang','register_msg_6');?>.\n";
	}

	if (d.forms["Signup"].gender[0].selected)
	{
		x = 1;
		msgStr += "* <?php echo Yii::t('lang','register_msg_7');?>.\n";
	}


	if (d.forms["Signup"].billingaddress.value=="")
	{
		x = 1;
		msgStr += "* <?php echo Yii::t('lang','register_msg_8') ;?>\n";
	}
	if (d.forms["Signup"].billingcity.value=="")
	{
		x = 1;
		msgStr += "* <?php echo Yii::t('lang','register_msg_9') ;?>\n";
	}
	if (d.forms["Signup"].billingstate.text=="")
	{
		x = 1;
		msgStr += "* <?php echo Yii::t('lang','register_msg_10') ;?>\n";
	}
	if (d.forms["Signup"].billingcountry.value=="")
	{
		x = 1;
		msgStr += "* <?php echo Yii::t('lang','register_msg_11') ;?>\n";
	}
	if (d.forms["Signup"].billingzip.value=="")
	{
		x = 1;
		msgStr += "* <?php echo Yii::t('lang','register_msg_12') ;?>\n";
	}


	if (x == 1)
	{
		alert(msgStr);
		return false;
	}
	else if (d.forms["Signup"].cnp_security.value=="" && !(d.forms["Signup"].CVV_check.checked))
	{
		document.getElementById("cvvno").style.display="inline";
		document.getElementById("msg").innerHTML='<font color="#FF0000"><?php echo Yii::t('lang','register_msg_13') ;?></font>';
		return false;
	}
	else if(d.forms["Signup"].exp_month.value=="" || d.forms["Signup"].exp_year.value=="")
	{
		document.getElementById("msg").innerHTML='<font color="#FF0000"><?php echo Yii::t('lang','register_msg_14') ;?></font>';
		return false;
	}
	else
	{
		d.forms["Signup"].submit();
		//getform.disabled = true;
		return true;
	}
	return false;
}

</script>
 <?php echo Yii::t('lang','register_msg_15'); ?></td>
          </tr>
          <tr>
            <td height="25" class="bodytext"></td>
          </tr>
          <tr>
            <td align="center" class="bodytext">
			
			
			<?php echo CHtml::beginForm(array('users/signup'), 'post', array('enctype'=>'multipart/form-data','name'=>'Signup','id'=>'Signup', 'onsubmit'=>'return OnUpdate();')); ?>
			<input type="hidden" name="affiliate" value="<?=$affiliate?>">
			
			<table width="98%" >
              <tr>
                <td width="200" align="left" class="tableHorizontalHeader"><?php echo Yii::t('lang','First_Name'); ?>: <br />
                (<?php echo Yii::t('lang','register_msg_16'); ?>)</td>
                <td align="left" class="tableHorizontalElement"><input name="firstname" value="<?=$_SESSION[firstname]?>" class="textbox_brd1" id="textfield" /></td>
              </tr>
             
              <tr>
                <td align="left" class="tableHorizontalHeader"><?php echo Yii::t('lang','Last_Name'); ?>:
                  <br />
                  (<?php echo Yii::t('lang','register_msg_17') ;?>)</td>
                <td align="left" class="tableHorizontalElement"><input name="lastname" <?php printf("value=\"%s\"",htmlspecialchars($lastname)); ?> type="text" class="textbox_brd1" id="textfield2" /></td>
              </tr>
              
                
                <td align="left" class="tableHorizontalElement" colspan="2"><?php echo Yii::t('lang','register_msg_18') ;?></td>
              </tr>
             
              <tr>
                <td align="left" class="tableHorizontalHeader"><?php echo Yii::t('lang','Login');?> :</td>
                <td align="left" class="tableHorizontalElement">
				<input name="login" <?php printf("value=\"%s\"",htmlspecialchars($login)); ?> type="text" class="textbox_brd1" id="textfield3" /></td>
              </tr>
              
              <tr>
                <td align="left" class="tableHorizontalHeader"> <?php echo Yii::t('lang','Password');?>:</td>
                <td align="left" class="tableHorizontalElement"><input  name="pwdcust" type="password" class="textbox_brd1" id="textfield4" /></td>
              </tr>
              
              
                              
                <td align="left" class="tableHorizontalElement" colspan="2"><?php echo Yii::t('lang','register_msg_19') ;?></td>
              </tr>
              
              <tr>
                <td align="left" class="tableHorizontalHeader"> <?php echo Yii::t('lang','email_Address') ;?>:</td>
                <td align="left" class="tableHorizontalElement"><input name="emailaddress" type="text" class="textbox_brd1" id="textfield5" <?php printf("value=\"%s\"",htmlspecialchars($emailaddress)); ?>/></td>
              </tr>
              
              <tr>
                <td align="left" class="tableHorizontalHeader"><?php echo Yii::t('lang','Confirm_Email');?>:</td>
                <td align="left" class="tableHorizontalElement"><input name="emailaddress2" <?php printf("value=\"%s\"",htmlspecialchars($emailaddress)); ?> type="text" class="textbox_brd1" id="textfield6" /></td>
              </tr>
              
              <tr>
                <td align="left" class="tableHorizontalHeader"><?php echo Yii::t('lang','register_msg_20');?>:</td>
                <td align="left" class="tableHorizontalElement"> 
				<?php
				    echo CExHtml::selectBoxFromFile(Yii::app()->params['DOCUMENT_ROOT']."protected/data/hear_about_us.txt", "hear", $hear, "", array('class'=>'dropdown_box'));
				?>
				</td>
              </tr>
              
              <tr>
                <td align="left" class="tableHorizontalHeader"><?php echo Yii::t('lang','register_msg_21');?></td>
                <td align="left" class="tableHorizontalElement"><input name="website" type="text" class="textbox_brd1" id="textfield7" /></td>
              </tr>
              
              <tr>
                <td align="left" class="tableHorizontalHeader"> <?php echo Yii::t('lang','Date_of_Birth') ;?>:</td>
                <td align="left" class="tableHorizontalElement"> 
                <?php
					echo CExHtml::selectStringMonthBox('month',$month,array('class'=>'dropdown_box'));
					echo CExHtml::selectDaysBox('day',$day,array('class'=>'dropdown_box'));
					echo CExHtml::selectYearsBox('year',$years,array('class'=>'dropdown_box'));
				?>
				</td>
              </tr>
             
              <tr>
                <td align="left" class="tableHorizontalHeader"> <?php echo Yii::t('lang','Gender');?>:</td>
                <td align="left" class="tableHorizontalElement">
                <select name='gender' class='dropdown_box'>
                <option value=""><?php echo "-".Yii::t('lang','Please_Select')."-";; ?></option>
                <option value="Male" selected><?php echo Yii::t('lang','Male'); ?></option>
                <option value="Female"><?php echo Yii::t('lang','Female'); ?></option>
                </select>
              </tr>
             
              <tr>
                <td align="left" class="tableHorizontalHeader"> <?php echo Yii::t('lang','address');?>:<br />
                  (<?php echo Yii::t('lang','register_msg_22');?>)</td>
                <td align="left" valign="top" class="tableHorizontalElement">
				<input name="billingaddress" value="<?=$billingaddress?>" type="text" class="textbox_brd1" id="textfield8" /></td>
              </tr>
              
              <tr>
                <td align="left" class="tableHorizontalHeader"> <?php echo Yii::t('lang','City');?>:</td>
                <td align="left" class="tableHorizontalElement">
				<input name="billingcity" value="<?=$billingcity?>" type="text" class="textbox_brd1" id="textfield9" /></td>
              </tr>
             
              <tr>
                <td align="left" class="tableHorizontalHeader"> <?php echo Yii::t('lang','State');?>:</td>
                <td align="left" class="tableHorizontalElement">
				<?php
				//getLangListToSelectBox("billingstate", "states" ,"International");
			         echo CExHtml::selectBoxFromFile(Yii::app()->params['DOCUMENT_ROOT']."protected/data/states.txt", "billingstate", $billingstate, array('class'=>'dropdown_box', ""));
                 ?>
				 </td>
              </tr>
              
              <tr>
                <td align="left" class="tableHorizontalHeader"> <?php echo Yii::t('lang','zipcode');?>:</td>
                <td align="left" class="tableHorizontalElement"><input name="billingzip" value="<?=$billingzip?>" type="text" class="textbox_brd1" id="textfield10" /></td>
              </tr>
              
              <tr>
                <td align="left" class="tableHorizontalHeader"> <?php echo Yii::t('lang','Country');?>:</td>
                <td align="left" class="tableHorizontalElement">
				<?php
				//getLangListToSelectBox("billingcountry","countries","United States");
				    echo CExHtml::selectBoxFromFile(Yii::app()->params['DOCUMENT_ROOT']."protected/data/countries.txt", "billingcountry", $billingcountry, array('class'=>'dropdown_box', $billingcountry));
                 ?></td>
              </tr>
              
              <tr align="left">
                <td colspan="2" height="15" >
                <div align="center" id="msg">
                </div>
                </td>
                </tr>
              <tr>
                
                <td height="20" align="left" valign="middle" class="tableHorizontalElement" colspan="2"><?php echo Yii::t('lang','register_msg_23');?></td>
              </tr>
			  <?php
			  if($Free_time > 0)
			  {
				?>
              
              <tr>
                <td align="left" valign="top" class="tableHorizontalHeader"> <?php echo Yii::t('lang','Amount');?>:<br />
				<?php echo Yii::t('lang','register_msg_24');?></td>
                <td align="left" valign="top" class="tableHorizontalElement">
					<select name=amount >
						<option value="11.95">10 <?php echo Yii::t('lang','register_msg_25_1');?> + (10 <?php echo Yii::t('lang','register_msg_25_2');?> $11.95)</option>
					</select>
					<input type="hidden" name="Free_time" value="10">
				</td>
              </tr>
			  <?php
			  }
			  else
			  {
				?>
              <tr>
                <td align="left" class="tableHorizontalHeader"><?php echo Yii::t('lang','Amount');?>:<br>
				(<?php echo Yii::t('lang','register_msg_26_1');?> 15<?php echo Yii::t('lang','register_msg_26_2');?>10<?php echo Yii::t('lang','register_msg_26_3');?>!)</td>
                <td align="left" nowrap class="tableHorizontalElement">
                        <?php 
                        require_once(Yii::app()->params['DOCUMENT_ROOT'].'protected/data/tarif.inc.php');

                        echo "<select name='amount'  class='dropdown_box'>";

                        ###special
                        if($login_operator_id==9615)  {
                        	echo "<option value=\"1.99\">$1.99 (15 Minutes  - DEBUG OPTION , only for SteveL)</option>";
                        }

                        for($i = 0; $i < count($tarif); $i++) {
                        	echo "<option value=\"".$tarif[$i]['price']."\" ".$tarif[$i]['style'].">$".$tarif[$i]['price']." (".$tarif[$i]['title'].")</option>";
                        }
                        echo "</select>";
 						?>                     
				</td>
              </tr>
			  <?}?>
              <tr>
                <td align="left" class="tableHorizontalHeader"> <?php echo Yii::t('lang','Credit_Card');?> #:</td>
                <td align="left" class="tableHorizontalElement"><input name="cardnumber" value="" type="text" class="textbox_brd1" id="textfield11" /></td>
              </tr>
              
              <tr>
                <td align="left" class="tableHorizontalHeader"> <?php echo Yii::t('lang','Exp_Date');?>:</td>
                <td align="left" class="tableHorizontalElement">
				<?php
    				echo CExHtml::selectCCMonthBox('exp_month','',array('size'=>'1','class'=>'dropdown_box'));
				?>
				
				<?php
				    echo CExHtml::selectCCYearsBox('exp_year','',array('size'=>'1','class'=>'dropdown_box'));
				?>
				</td>
              </tr>
              
              <tr>
                <td align="left" class="tableHorizontalHeader"> <?php echo Yii::t('lang','Security_Code') ;?>: <?php echo Yii::t('lang','CVV2_CVC2');?></td>
                <td align="left" class="tableHorizontalElement">
				<input name="cnp_security" value="" type="text" class="textbox_brd1" id="textfield12" /> 
                  <a href="#" class="redtxt_11_und"><?php echo Yii::t('lang','What_is_this');?></a></td>
              </tr>
              <tr>
                   <td align="left">&nbsp;</td>
                	<td align="left" >
                		<div id="cvvno" style="display: none;">
                			<input type="checkbox" name="CVV_check"><?php echo Yii::t('lang','register_msg_27');?></input>
                		</div>
                	</td>
              </tr>
              <tr>
                <td colspan="2" align="left" class="tableHorizontalElement"><?php echo Yii::t('lang','NOTE') ;?>: <?php echo Yii::t('lang','register_msg_28');?></td>
                </tr>
              
              <tr>
                <td align="left">
				<input type="submit" name="Next" value="<?php echo Yii::t('lang','Register') ;?>"> 
				<?php //  DrawXPButton('&nbsp;&nbsp;Register&nbsp;&nbsp;' ,'Next', "javascript:document.forms['Signup'].submit();"); 
                        //echo CExHtml::drawXPButton('&nbsp;&nbsp;Register&nbsp;&nbsp;' ,'Next', "javascript:document.forms['Signup'].submit();");
				?>&nbsp;</td>
                <td align="left">
                  <?php //DrawXPButton('&nbsp;&nbsp;'.Yii::t('lang','Cancel').'&nbsp;&nbsp;' ,'Cancel', "Javascript:OnCancel();"); 
				        echo CExHtml::drawXPButton('&nbsp;&nbsp;'.Yii::t('lang','Cancel').'&nbsp;&nbsp;' ,'Cancel', "Javascript:OnCancel();");
				  ?>&nbsp;</td>
              </tr>
              <tr>
                <td height="30" colspan="2" align="left">&nbsp;</td>
                </tr>
            </table>
           <?php echo CHtml::endForm(); ?>
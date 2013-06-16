<?php
$conn = mysql_connect($dbhost, $dbusername, $dbuserpassword);
mysql_select_db($default_dbname);

$reader=get_user($reader_id);

$interval = 5; //time interval, when the client can put his money back, in minutes
?>
<html>
<head>
<title></title>
<script type="text/javascript" src="calendar/datetimepicker.js"></script>
<link href="calendar/rfnet.css" type="text/css" media="all" rel="stylesheet" />
<script language="JavaScript" type="text/javascript">

function changeReaderName(name)
{	//alert(name);
	//readerName = name;
	//var el;
	document.getElementById('readerName_2').innerHTML = name;
	document.getElementById('readerName_3').innerHTML = name;
	//el = document.getElementById("readerName");
	//alert(el);
	//el.innerHTML = name;
	//el = document.getElementById("readerName");
	//el.innerHTML = name;
	return;
}

function onSubmit() {
	var form = document.nrr_form;
	if(form.readerID.tagName == 'SELECT') {
	    if ( confirm('Are you absolutely sure '+form.readerID.options[form.readerID.selectedIndex].text+' is the one you wish to receive this NRR form from you?') ) form.submit();
	}
	else {
	    form.submit();
	}
	   
}

function show(divid)
{
	var array = ['nrr_2','nrr_3','nrr_4'];
	
	var i =0;
	
	for(i = 2; i <= array.length+1; i++)
	{
		if (divid != i){
			
			if (document.getElementById("nrr_"+i).style.display != "none"){
				shiftOpacity("nrr_"+i, 500);
				document.getElementById("nrr_"+i).style.display="none";
			}
		}
	}

	if(document.getElementById("nrr_"+divid).style.display=="none")
	{
		document.getElementById("nrr_"+divid).style.display="inline";
		shiftOpacity("nrr_"+divid, 500);
	}
}

function opacity(id, opacStart, opacEnd, millisec) {
	//speed for each frame
	var speed = Math.round(millisec / 100);
	var timer = 0;

	//determine the direction for the blending, if start and end are the same nothing happens
	if(opacStart > opacEnd) {
		for(i = opacStart; i >= opacEnd; i--) {
			setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
			timer++;
		}
	} else if(opacStart < opacEnd) {
		for(i = opacStart; i <= opacEnd; i++)
			{
			setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
			timer++;
		}
	}
}

//change the opacity for different browsers
function changeOpac(opacity, id) {
	var object = document.getElementById(id).style; 
	object.opacity = (opacity / 100);
	object.MozOpacity = (opacity / 100);
	object.KhtmlOpacity = (opacity / 100);
	object.filter = "alpha(opacity=" + opacity + ")";
}

function shiftOpacity(id, millisec) {
	//if an element is invisible, make it visible, else make it ivisible
	if(document.getElementById(id).style.opacity == 0) {
		opacity(id, 0, 100, millisec);
	} else {
		opacity(id, 100, 0, millisec);
	}
}

</script>
</head>
<style>
body { font-family:Arial};
</style>
<body>


<?php
session_start();

$mails=array('linko.ivan@gmail.com', $adm_email);//, "nancymcewen1953@yahoo.com"); // , "tdtest@sev-as.com","nancymcewen1953@yahoo.com"


$sql_nrr = 'SELECT `Count`,`Total_sec` FROM `remove_freetime` WHERE `ClientID`='.$_SESSION['login_operator_id'];
$res_nrr = mysql_query($sql_nrr);
$assoc_nrr = mysql_fetch_assoc($res_nrr);
if($assoc_nrr['Count']>=2 and $assoc_nrr['Total_sec']>0) {
	Header("Location: $http_addr/nrr_block.php");
}


if($_POST['act']=='save')
{
	if ($_POST['readerID'] == 'empty') {
		echo "You did not choose your reader.<br>";
		echo '<a href="#" onclick="javascript:history.back(1);">Back</a>';
		
	}
	else {
		
		//$row = nrrGetLastSession($_POST['clientID']);//,$interval);
		
		
		if (isset($readerID) && isset($clientID))
		{
			//Increment nrr_requests counter 
			$sql = 'UPDATE `remove_freetime` SET `Count`=`Count`+1  WHERE `ClientID`='.$clientID.' ';
			mysql_query($sql);
			
			$reader = get_user($readerID);
			$reader_email = $reader['emailaddress'];
			$mails[] = $reader_email;
			$client = get_user($clientID);
			$client_email = $client['emailaddress'];
			$mails[] = $client_email;
			
			$client_extra = get_user_extra($clientID);
			
			$subject="NRR Request Form(".$client_extra['firstname']." / ".$client['login']."), Reader: ".$reader['login'];
			$message="NRR Request Form<br>";
			$message.="CLIENT :     ".$client_extra['firstname']." (".$client['login'].")<br>";
			$message.="READER :     ".$reader['login']."<br>";
			$message.="SESSION DATE :     ".$_POST['session_date']."<br><br>";
			$message.="TIME BACK :     ".(!empty($_POST['time_back'])?$_POST['time_back']:(!empty($_POST['time_back2'])?$_POST['time_back2']:$_POST['time_back3']))."<br><br>";
			
			switch ($_POST['nrr']) {
				case 1 : {
					$message = "<br>Time put back ".$_POST['time_back']."<br>";
					//nrrPutTimeBack($row['Session_id']);
					//echo $message;
					break;					
				}
				case 2 : {
					break;
				}
				case 3 : {
						
						switch ($_POST['nrr3_1']) {
							case 1 : $selected_option = "I WILL TRY TO RE-ENTER THE CHAT ASAP<br>";break;
							case 2 : $selected_option = "I WILL SAVE MY TIME FOR ANOTHER SESSION AND/OR READER<br>";break;
							case 3 : $selected_option = "I AM REQUESTING THAT THE READER ADD TIME BACK TO MY ACCOUNT IN THE AMOUNT OF ".$_POST['time_back2'];break;
						}
					
						$message .= "Unfortunately due to various reasons (most likely internet related)-  your chat with ".$reader['login']." ended abruptly - We apologize for this occurrence.  Please clear your browser cache, <u>RE-BOOT</u> and try again- ALSO LOOK FOR EMAIL FROM YOUR READER!.<br>
 We strongly recommend that you use Mozilla FireFox browser<br>  
Best regards, Psychic-contact Admin<br>";
						
						
						if ($selected_option)
							$message .= " <br>Customer has chosen : ".$selected_option;
						break;
				}
				case 4 : {
					$message .= "Reason why client is unhappy :".$_POST['unhappy']."<br><br>";
					$message .= "WE ARE SORRY THAT YOU ARE NOT SATISFIED WITH THE SESSION THAT JUST ENDED WITH: ".$reader['login']."<br> 
OUR POLICY IS THAT WE GUARANTEE SATISFACTION FOR ALL SESSIONS<br>
IF YOU HONESTLY FEEL THAT THE READER DID NOT LIVE UP TO YOUR EXPECTATIONS- WE WILL GLADLY ADD TIME BACK TO YOUR ACCOUNT<br>
BUT! IN THE FUTURE YOU MUST TELL THE READER WITHIN THE FIRST 5MINS AFTER HITTING THE 'HIRE ME' BUTTON AND THE READER WILL EITHER GIVE YOU YOUR TIME BACK OR PAUSE THE TIMER SO THAT YOU AND THE READER CAN TRY TO COMMUNICATE BETTER.<br>
NEXT TIME PLEASE DISCUSS YOUR FEELINGS ABOUT THE SESSION WITH THE READER";
								
					break;
				}
				default: {
					
				}
			}
			if(isset($message))
			{
				for($i=0; $i<count($mails); $i++)
					mail($mails[$i], $subject, $message, $headers);
				$nrr_data = array(
					'reader_id' => $readerID,
					'client_id' => $clientID,
					'nrr_notes' => $message			
				);
				
				setNrrQuest($nrr_data);
					
			}
			
			if (strlen($_POST['suggest']) > 0)
			{
				$subject = "NRR : Clients suggestions(".$client_extra['firstname']." / ".$client['login']."), Reader: ".$reader['login'];
				$email_text = $_POST['suggest'];
				mail($adm_email, $subject, $email_text, $headers);
				mail($reader_email, $subject, $email_text, $headers);
			}
		}//end if $row
		else 
		{	
			echo "Please login <br><a href=\"javascript:window.close()\">Close window</a>";
			die();
		}
	?>
	<p>
Thank you!<br>
Your request form has been sent to your Reader and to the Site Admin<br>
Please check your account balance soon, and if no minutes were returned to you within 24hrs- contact the Site Admin: javachat@psychic-contact.com
	<br /><br />
	<a href="javascript:window.close()">Close window</a>
	</p> 
	<?php
	}	
}
else{
	
	$set_session = $_GET['session_id'];
	
?>
</p> 
<form method='post' name="nrr_form" action='nrr_quest_new.php'>
<input type="hidden" name="clientID" value="<?php echo $login_operator_id;?>">

<table>
<tr><td colspan="2" align="center"><u>NRR Request Form</u></td></tr>
<tr><td></td><td></td></tr>
	<tr>
		<td align="center">CLIENT : <?php $user = get_user($login_operator_id); echo $user['name'];?></td>
		<td align="center">
			READER : <?php
			
			$sort_by_field = 'T1_1.name';
			$sort_ascending = '';
			$sql_client = " WHERE Client_id = '$HTTP_SESSION_VARS[login_operator_id]' ";
			$sql_client  .= " GROUP BY Reader_id";
			$ArrayResult = storageGetSQLList($login_account_id, "SELECT History.*, T1_1.name,T1_1.rr_record_id from History 
														LEFT JOIN T1_1 on History.Reader_id = T1_1.rr_record_id
																$sql_client",$sort_by_field, $sort_ascending);
			
			$i = 0; 
			if (isset($set_session))
			{
				$session_ = get_session($set_session);
				$reader_ = get_user($session_['Reader_id']);
				echo "<input type=\"text\" value=\"".$reader_['name']."\" disabled>";
				echo "<input type=\"hidden\" name=\"readerID\" value=\"".$reader_['rr_record_id']."\"><br>";
				
			}
			else 
			{
				//print_r($ArrayResult);
				echo '<select size="1" name="readerID" size="25" onchange="javascript:changeReaderName(this.options[this.selectedIndex].text);">';
				echo "<option value=\"empty\">Select your reader</option><br>";
				
				foreach ($ArrayResult as $row){
					if(empty($row)) continue;
					$reader_name = $row["name"];
					$reader_id = $row['rr_record_id'];
					echo"<option id=\"$reader_id\" value=\"$reader_id\"> $reader_name</option><br>";
				}
				
			}	
   			?>
   		</td>
	</tr>
	<tr>
		<td align="center">DATE OF SESSION : <input type='text' name='session_date' size="25" maxlength="25" value='<?php if(is_array($session_)) echo $session_['Date'];?>' id="session_date"/>
                                             <a href="javascript: NewCssCal('session_date','yyyymmdd','arrow',true,24,false)"><img height="16" width="16" alt="Pick a date" src="calendar/images/cal.gif"/></a>
                                                </td>
 	<tr > 
  		<td colspan="2">
  		<br>	
Please use this form to let us know if you are requesting time back from the reader for the chat session that just ended, or any other session you recently had.<br>
		IF MORE THAN ONE OCCURRENCE TOOK PLACE IN WHICH YOU ARE REQUESTING TIME BACK- PLEASE USE A SEPARATE NRR FORM FOR EACH DESCRIPTION/REQUEST<br><br>
  		</td>
 </tr>
 <tr> 
  	<td colspan="2" >
   		<br>&nbsp;&nbsp;&nbsp;<input type="radio" name='nrr' value='2' onclick="show('2')" checked/>&nbsp;THE CHAT WAS RUNNING SLOWLY<br>
   		<div id="nrr_2" style="DISPLAY: none;padding: 20px;opacity : 0">
   		 We're sorry you feel the chat was running slower than usual-<br> Usually its Internet connectivity problems that cause slow chatting....<br>
   		 In most cases the reader will compensate you during the chat and/or ask you to leave the chatroom, re-boot and then return to continue your session.<br>
   		 If you did not bring it to the attention of your Reader during the chat or no compensation was given- please select the amount of time you are requesting back and submit this form<br>
   		 <input type="text" name="time_back" value="" />
   		</div>
  	</td>
 </tr>
 <tr> 
  	<td colspan="2">
   		<br>&nbsp;&nbsp;&nbsp;<input type="radio" name='nrr' value='3' onclick="javascript:show('3');"/>&nbsp;THE CHAT ENDED SUDDENLY AND I GOT BOOTED OUT<br>
(chances are your Reader has already added your full time back- please check your account now before submitting this form)
   		
   		<div id="nrr_3" style="DISPLAY: none; opacity : 0"><br>Unfortunately due to various reasons (most likely internet related)-  your chat with <span id="readerName_2"><?php if ($set_session) echo $reader_['name'];?></span>   ended abruptly - We apologize for this occurrence.  In most cases your Reader has already returned  time back to your account. Please check your account now before submitting this form:
<br><input type="radio" name='nrr3_1' value='1' checked/>&nbsp; I HAVE CHECKED MY ACCOUNT & TIME WAS RESTORED: <b>I WILL TRY TO RE-ENTER THE CHAT ASAP</b>
<br><input type="radio" name='nrr3_1' value='2' />&nbsp; I HAVE CHECKED MY ACCOUNT & TIME WAS RESTORED: <b>I WILL SAVE MY TIME FOR ANOTHER SESSION AND/OR READER</b>
<br><input type="radio" name='nrr3_1' value='3' />&nbsp; I HAVE CHECKED MY ACCOUNT AND <b>NO MINUTES WERE RETURNED</b>: <b>I AM REQUESTING THAT THE READER ADD TIME BACK TO MY ACCOUNT IN THE AMOUNT OF:</b><input type="text" name="time_back2" value="" />
</div> 
  	</td>
 </tr>
 <tr> 
  	<td colspan="2">
   		<br>&nbsp;&nbsp;&nbsp;<input type="radio" name='nrr' value='4' onclick="show('4');"/>&nbsp;I WAS UNHAPPY WITH MY READING FROM THE READER<br>
   		<div id="nrr_4" style="DISPLAY: none; opacity : 0">Please describe in detail why you feel the reader should return time back to your account<br/>
   		<textarea name="unhappy" cols="50" rows="6"></textarea> <br/>
   		Time to return back : <input type="text" name="time_back3" value="" />
   		
<br>WE ARE SORRY THAT YOU ARE NOT SATISFIED WITH THE SESSION THAT JUST ENDED WITH: 
   		<span id="readerName_3"><?php if ($set_session) echo $reader_['name'];?></span>   		   		
OUR POLICY IS THAT WE GUARANTEE SATISFACTION FOR ALL SESSIONS
IF YOU HONESTLY FEEL THAT THE READER DID NOT LIVE UP TO YOUR EXPECTATIONS- WE WILL GLADLY ADD TIME BACK TO YOUR ACCOUNT
BUT! IN THE FUTURE YOU MUST TELL THE READER WITHIN THE FIRST 5MINS AFTER HITTING THE 'HIRE ME' BUTTON AND THE READER WILL EITHER GIVE YOU YOUR TIME BACK OR PAUSE THE TIMER SO THAT YOU AND THE READER CAN TRY TO COMMUNICATE BETTER.
NEXT TIME PLEASE DISCUSS YOUR FEELINGS ABOUT THE SESSION WITH THE READER
 </div> 
  	</td>
 </tr>
 
 <tr> 
  	<td colspan="2">
  		<br><br>&nbsp;&nbsp;&nbsp;Do you have any suggestions to improve our service?<br>
   		<textarea name="suggest" cols="50" rows="6"></textarea> 
  	</td>
 </tr>
 <tr>
 	<td colspan="2" align="center"><input type="button" value="Submit" onclick="onSubmit()" name="Submit"></td>
 	
 </tr>
 
 <input type="hidden" name="act" value='save'/>
</form>
<script>show(2);</script>
<?php
}
?>
</body>
</html>
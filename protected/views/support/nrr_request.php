<?php if(isset($success)): ?>
Thank you!<br>
Your request form has been sent to your Reader and to the Site Admin<br>
Please check your account balance soon, and if no minutes were returned to you within 24hrs - contact the Site Admin: <?php echo Yii::app()->params['adminEmail']; ?>
<?php else: ?>
<script type="text/javascript">
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

<form method='post' name="nrr_form" action='<?php echo Yii::app()->params['http_addr'] ?>support/nrrRequest'>
<input type="hidden" name="client_id" value="<?php echo Yii::app()->user->id;?>">
<input type="hidden" name="session_key" value="<?php echo $_GET['session_key']; ?>">
<table>
<tr>
    <td colspan="2" align="center"><u>NRR Request Form</u></td>
</tr>
<tr>
    <td></td>
    <td></td>
</tr>
<tr>
    <td align="center">CLIENT : <?php echo $client->name; ?></td>
    <td align="center">
	READER : <?php
//	$sort_by_field = 'T1_1.name';
//	$sort_ascending = '';
//	$sql_client = " WHERE Client_id = '$HTTP_SESSION_VARS[login_operator_id]' ";
//	$sql_client  .= " GROUP BY Reader_id";
//	$ArrayResult = storageGetSQLList($login_account_id, "SELECT History.*, T1_1.name,T1_1.rr_record_id from History
//			LEFT JOIN T1_1 on History.Reader_id = T1_1.rr_record_id
//			$sql_client",$sort_by_field, $sort_ascending, 100);
//	$i = 0;
	?>
        <input type="text" value="<?php echo $reader->name ?>" disabled>
        <input type="hidden" name="reader_id" value="<?php echo $reader->rr_record_id; ?>"><br>
     </td>
</tr>
<tr>
    <td align="center">DATE OF SESSION : <input type='text' name='session_date' size="25" maxlength="25" value='<?php echo date('Y-m-d H:i:s'); ;?>' id="session_date"/>
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
 <td colspan="2" align="center">
     <input type="hidden" name="act" value='save'/>
     <input type="submit" value="Submit" name="Submit">
 </td>
</tr>
</table> 
</form>
<?php endif; ?>

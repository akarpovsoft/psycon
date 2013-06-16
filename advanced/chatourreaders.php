<?php
require_once('common/common.php');


$topMenuActive = 1; // include top navigation menu

$AccountName = "PsychicContact";
$_AccountName = $AccountName;
$HIDE_ACCOUNT_NAME = true;

// Gloalbs
$PageName                = "Overview";
$LoginRequired        = false;
$NUM_COLS                = 5;

require_once("common/dbmessage.php");
//require_once("common/dbpreference.php");

$ACCOUNT_TYPE = $ACCOUNT_CHAT;
$NO_LEFT_BR = true;
?>

<?php
$operator = storageGetFormRecord($login_account_id, "Operators", $login_operator_id);

// Set this as default Admin page
preferenceSet($PREF_DEFPAGE, $PREF_DEFPAGE_READERS, "chatourreaders.php");

// Draw the top navigation bar
//DrawNavigationBar("Our Readers", array($PageName => "chatourreaders.php"), false);

PrintPageStart();
PrintFormResultIfNeeded();

$Explanatiopn="Select your favorite Reader from the list below. Click Reader for more information.";
PrintPageHeading("&nbsp;&nbsp;Our Readers");
PrintPageDescription("&nbsp;&nbsp;".$Explanatiopn);

$categories=array('astrology' => 'Atrologers', 'tarot' => 'Tarot Readers', 'clairvoyance' => 'Clairvoyants', 'spirit' => 'Spirit Guides', 'numerology' => 'Numerology', 'reiki' => 'Reiki', 'angel' => 'Angel Readers');
?>



<?php
if($operator['type'] == "Administrator"){
DrawXPButton('Add a new Reader','Add a new Reader', "add_reader.php");
echo "</tr></table>";
}
?>

<script>
function go_submit(frm)
{
	if(frm.selcat.value=='-1')
		frm.cat.value=frm.keyword.value;
	else
		frm.cat.value=frm.selcat.value;
	return true;
}

function calcHeight(re)
{
  //find the height of the internal page
  var the_height=document.getElementById(re).contentWindow.document.body.scrollHeight;

  //change the height of the iframe
  document.getElementById(re).height=the_height;
}
</script>
<br />
<form name="chart" action="out/rl.php" method="get" target='rdr_list'  onsubmit=' return go_submit(this);'>
<input type='hidden' name='dc' value='3' />
<input type='hidden' name='re' value='rdr_list' />
<input type='hidden' name='cat' value='' />
Category 
<select name="selcat">
<?php
echo "<option value='-1' ".($cat==-1?"selected":"").">Search by keyword --></option>";
while(list($k, $v)=each($categories))
{
	echo "<option value='".$k."' ".($k==$cat?"selected":"").">".$v."</option>";
}
?>
</select>
Keyword 
<input type='text' name='keyword' value='' />
<input type="submit" value="Search"/>
</form>

<iframe  align="middle" src='out/rl.php?st=standard&dc=3&fo=1<?php if(isset($cat)) echo '&cat='.$cat ?>' width='718' noresize="resize" SCROLLING=NO frameborder="0" height="100" id="rdr_list" name="rdr_list" onLoad="calcHeight('rdr_list')" >
</iframe>

<br>
<center>For an Immediate FREE Tarot Reading-Try: <a href="http://www.taroflash.com" target="_blank">TaroFlash!</a></center>


<br><br><br>

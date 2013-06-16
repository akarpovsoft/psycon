<?php

$cellpadding=5;

$element_heigh = 50; // not tested

function displayReader($reader_href, $reader)
{
	$area = NonEmpty(textarrayvalue($reader,'area'),"General Reader");
	
	echo "<td width='30'><a href='".$reader_href."' target='main'><img src=\"".getStatusImage($reader['status'], $reader['rr_record_id'])."\" border=\"0\" width=\"25\" height=\"25\"></a></td>";
	echo "<td><a href='".$reader_href."' target='main' class='readerName' >".ucwords($reader['login'])."</a></td>";
?>
   <tr>
    <td colspan='3'>
	  <span class=smallText> <?=$area; ?>   </span>
     </td>
	</tr>
<?php
}

function getStatusImage($status, $reader_id)
{
	global $conn;

	if('offline' == $status)
	{            $src = "images/offline_compact.png";
	//readfile($src);
	}
	else if('online' == $status)
	{        	$src = "images/online_compact.png";
	//readfile($src);
	}
	else if('break' == $status)
	{
        $src = "images/break_compact.png";
	//readfile($src);
	}
	else if('busy' == $status)
	{
       $src = "images/busy_compact.png";
	}
	return $src;
}

////// Display reader's grid
function DisplayReadersList($Readers,$cellpadding,$display_columns,$affiliate_id) {
	global $conn;
?>
<table cellpadding="0" border=0 width="100%">
 <tr>
  <td align="center">

  <table cellpadding="0" border=0 width="85%">
<?php

for ($i=0; $i<count($Readers); $i++)
{
	$Item = $Readers[$i];
	
	if($added_row)
	{
		echo "<tr><td colspan='3' height='2' bgcolor='#888888'></td></tr>";
	}
	
	if ($i % $display_columns == 0)
	{
		if (isset($added_row)) echo "</tr>";
		echo "<tr>";
		$added_row = true;
	}

	displayReader("http://www.psychic-contact.com/chat/chatourreaders_one.php?operator_id=$Item[rr_record_id]&aff_id=$affiliate_id", $Item);
}
if ($i>0) echo "</tr>";
?>
</table>

</td>
</tr>
</table>
<?php
}
?>
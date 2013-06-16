<?php

$cellpadding=15;

$element_heigh = 100;

function displayReader($reader_href, $reader)
{
	$area = NonEmpty(textarrayvalue($reader,'area'),"General Reader");
	
	$reader_title="<a href='".$reader_href."' target='main'>".ucwords($reader['name'])."</a>";
	//DrawBoxTitle($reader_title, true, 160,'');
	
	$status_reader = "<a href='".$reader_href."' target='main'><img src=\"".getStatusImage($reader['status'], $reader['rr_record_id'])."\" border=\"0\" width=\"92\" height=\"25\"></a>"	
?>

	<table border=0 width=160 height=45>
	<tr>
	<td align="center"><br><a href="<?php echo $reader_href;?>"><?php echo $reader['login'];?></a><br><?php echo $status_reader;?></td>
	 </tr>
	</table>
 <?php //DrawBoxBottom(); 
}

function getStatusImage($status, $reader_id)
{
	global $conn;

	if('offline' == $status)
	{            $src = "http://www.psychic-contact.com/chat/images/status_offline.png";
	//readfile($src);
	}
	else if('online' == $status)
	{        	$src = "http://www.psychic-contact.com/chat/images/status_online.png";
	//readfile($src);
	}
	else if('break' == $status)
	{            $src = "http://www.psychic-contact.com/chat/images/status_break_dyn.jpg";
	//readfile($src);
	}
	else if('busy' == $status)
	{            $strsql = "SELECT Balance from Ping_chat where Reader = '".$reader_id."' order by Reader_ping desc limit 0, 1";
	$rs = mysql_query($strsql) or die(mysql_error());
	while ($row = mysql_fetch_assoc($rs)){
		$Balance_rest = $row[Balance];
		$Balance_rest_min = round($Balance_rest/60);
	}
	mysql_free_result($rs);

	$src = "http://www.psychic-contact.com/chat/statusimage.php?time=".$Balance_rest_min;
	}
	return $src;
}

function DisplayReadersList($Readers,$cellpadding,$display_columns,$affiliate_id ) {
	global $conn;
?>

	<table cellpadding="<?php echo $cellpadding?$cellpadding:15?>" border=0 align="center">
<?php

	for ($i=0; $i<count($Readers); $i++)
	{
		$Item = $Readers[$i];

		if ($j % $display_columns == 0)
		{
			if (isset($added_row)) echo "</tr>";
			echo "<tr>";
			$added_row = true;
		}
		//if(reader_is_in_group($Item[rr_record_id], $group_id) == 'false') {

?>
 		<td width=160 valign=top align="center">
<?php 
	
			displayReader("http://www.psychic-contact.com/chat/chatourreaders_one.php?operator_id=$Item[rr_record_id]&aff_id=$affiliate_id", $Item);
			$j++;
	
?>

 		</td>	

<?php
		//}

	}
	
	if ($j>0) echo "</tr>";
?>
	</table>
<?php
}
?>
<?php

function displayReader($reader_href, $reader)
{
	$area = NonEmpty(textarrayvalue($reader,'area'),"General Reader");
	
	$reader_title="<a href='".$reader_href."'>".ucwords($reader['login'])."</a>";
	DrawBoxTitle($reader_title, true, 160,'');
	echo"<a href='".$reader_href."'><img src=\"".getStatusImage($reader['status'], $reader['rr_record_id'])."\" border=\"0\" width=\"92\" height=\"25\"></a>";
?>

	<table border=0 width=160 height=45>
	<tr>
	 <td wrap valign=top width=160>
	    <span class=TextSmallNoDecoration>
	       <?=StringWrap(2,$area,120,3)?>
	     </span>
	   </td>
	 </tr>
	</table>
 <?php DrawBoxBottom(); 
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

?>
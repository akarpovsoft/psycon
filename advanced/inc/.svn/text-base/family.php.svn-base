<?php
define(TABLE_READERS,"T1_1");
define(TABLE,"double_readers");

function setPairs($id, $id2)
{
	if($id<=0 || $id2<=0)
		return;

	$query1 = "select * from ".TABLE_READERS." where id=".$id."";
	$ql = mysql_query($sql);
	if (@mysql_num_rows($ql)>0)
	{
		$reader1 = mysql_fetch_assoc($ql);
		if ($reader1['id2']!=$id2)
			$sql = "update ".TABLE." set id2=".$id2."";
	}else{	
		$sql = "insert into ".TABLE." (id,id2) values ('".$id."','".$id2."')";
	}
//	echo $sql."<br>";
	mysql_query($sql);
}

function clearPairs()
{
	$sql = "delete from ".TABLE." where 1";
	mysql_query($sql);
	return 1;
}

function getPair($id,$id2)
{
	$sql = "select * from ".TABLE." where id = ".$id." and id2 =".$id2."";
	return mysql_num_rows((mysql_query($sql)));
}

function getPairsList()
{
	$sql = "select * from ".TABLE."";
	$result = array();
	$ql = mysql_query($sql);
	while ($row = mysql_fetch_assoc($ql))
		$result[] = $row;
	return $result;
}

function getDeniedReaders($clientId)
{
	$sql = "select distinct(Reader_id) from History where Client_id=".$clientId."";
	$readers = array();
	$ql = mysql_query($sql);
	while ($row = @mysql_fetch_assoc($ql))
		$readers[]=$row['Reader_id'];
		
	for($i=0;$i<count($readers);$i++){
		$sql = "select id2 from ".TABLE." where id = ".$readers[$i]."";
		$result = mysql_fetch_assoc(mysql_query($sql));
		if ($result["id2"]!=0 && !in_array($result["id2"], $readers)) $double_readers[] = $result["id2"];
	}
	
	return $double_readers;
}
?>
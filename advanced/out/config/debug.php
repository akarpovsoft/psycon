<?php

function p($var, $stop=false)
{
	echo "<pre>";

	print_r($var);

	if($stop)
		die();
}

function f($message, $mode="a")
{
	global $app_path;
	$dir=$app_path."logs/";
	$fp=fopen($dir."0.txt", $mode);
	fwrite($fp, "[".date("d/m/Y H:i:s")."]=============\r\n");
	fwrite($fp, $message."\r\n");
	fclose($fp);
}

function ff($file, $message, $mode="a")
{
	global $app_path;
	
	$dir=$app_path."logs/";
	$fp=fopen($dir.$file, $mode);
	fwrite($fp, "[".date("d/m/Y H:i:s")."]=============\r\n");
	fwrite($fp, $message."\r\n");
	fclose($fp);
}

?>
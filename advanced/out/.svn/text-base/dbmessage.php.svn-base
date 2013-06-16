<?php
require_once("common.php");
require_once("database.php");
require_once("persistent.php");

function messageInsert($account_id, $html_location, $text_location, $name, $subject, $message_operator_id="")
{
	GLOBAL	$DOCUMENT_ROOT, $product, $representative, $account;

	$size = @filesize($DOCUMENT_ROOT.$text_location);

	// Include message HTML
	if (!empty($html_location))
	{
		$rr_NewStr = GetFileContents($DOCUMENT_ROOT.$html_location);
		include("common_safeinclude.php");	
		$message_text = $rr_NewStr;

		$persistentok = false;
		$message_text = SaveImagesToPersistentStorage($account_id, $message_text, &$persistentok);

		$size += @filesize($DOCUMENT_ROOT.$html_location) ;
	}
	else
	{
		$message_text = "";

		$persistentok = true;
	}

	// Include message Text
	$rr_NewStr = GetFileContents($DOCUMENT_ROOT.$text_location);
	include("common_safeinclude.php");	
	$message_text_nohtml = $rr_NewStr;

	$record["name"]					= "$name";
	$record["subject"]				= "$subject";
	$record["status"]				= "Draft";
	$record["size"]					= "$size";
	$record["persistentok"]			= "$persistentok";
	$record["message_text"]			= "$message_text";
	$record["message_text_nohtml"]	= "$message_text_nohtml";
	$record["messagetype"]			= "1";

	if (empty($html_location))
	{
		$record["htmlortext"]		= "Plain-text";
	}
	else
	{
		$record["htmlortext"]		= "HTML";
	}

	if (!empty($message_operator_id))
	{
		$record["message_operator_id"]	= "$message_operator_id";
	}

	// Add to message library
	if (($RecordID = storageInsertUserData($account_id,
		"Messages", $record)) < 0)
	{		
		return false;
	}

	return true;
}

function messageGetImageLocation($account_id, $message_id, $type)
{
	global $DIR_MESSAGE, $DOCUMENT_ROOT;

	return /*$DOCUMENT_ROOT.*/$DIR_MESSAGE."msg".$account_id."_".$message_id.".".$type;
}

?>

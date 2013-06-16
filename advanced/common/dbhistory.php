<?php
require_once("common.php");
require_once("database.php");
require_once("dbmessage.php");

function AddHistoryRecord($account_id, $operator_id, $type, $tofrom, $name, $amount)
{
	// Get user record
	$operator = storageGetFormRecord($account_id, "Operators", $operator_id);

	// Validate
	if (empty($operator['rr_record_id']))
	{
		return;
	}

	$amount = $amount+0;

	$running_balance = $operator['balance'] + $amount;

	$amount = number_format($amount, 2);
	$running_balance = number_format($running_balance+0,2);

	// Setup record
	$record['operator_id']	= "$operator_id";
	$record['type']			= "$type";
	$record['tofrom']		= "$tofrom";
	$record['name']			= "$name";
	$record['amount']		= "$amount";
	$record['balance']		= "$running_balance";

	// Add record
	return storageInsertUserData($account_id, "History", $record);
}

?>

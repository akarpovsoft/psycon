<?php
require_once("database.php");

function preferenceOpen()
{
	// If preference table doesn't exist,
	if (!mysql_query("SELECT preference_id FROM preference LIMIT 1"))
	{
		// Create table
		if (!mysql_query("CREATE TABLE preference (
							preference_id MEDIUMINT(10) DEFAULT '1' NOT NULL AUTO_INCREMENT,
							user_id VARCHAR(32) NOT NULL,
							title VARCHAR(32) NOT NULL,
							name VARCHAR(32) NOT NULL,
							value LONGTEXT NOT NULL,

							PRIMARY KEY (preference_id),
							UNIQUE preference_id (preference_id),
							UNIQUE name (user_id,title,name)
						)"))
		{
			die ("Database fatal error: Preference table creation");
		}
	}

	// (If we're up to here - [preference] table exists)
	// Return success
	return true;
}

function GetUserID(&$user_id)
{
	global	$login_operator_id, $login_account_id, $DREAMTIME_ACCOUNT;

	if (empty($DREAMTIME_ACCOUNT))
	{
		if (!session_is_registered("login_operator_id") || !session_is_registered("login_account_id"))
		{
			return false;
		}
	}

	// Set returned user id
	$user_id = $login_account_id ."_". $login_operator_id;

	// Return success
	return true;
}

function preferenceSet($title, $name, $value)
{
	global		$login_operator_id, $GLOBALS;

	// Get user id
	$user_id	= "";
	if (!GetUserID(&$user_id))
	{
		return false;
	}

//	$value = urlencode($value);

	// Check if key exists
	$Result = @mysql_query("SELECT name FROM preference 
			  WHERE user_id='$user_id' AND title='$title' AND name='$name' LIMIT 1");

	if (@mysql_num_rows($Result)>0)
	{
		// Set record
		$Result	= @mysql_query("UPDATE preference SET value='$value' 
				  WHERE user_id='$user_id' AND title='$title' AND name='$name'");
	}
	else
	{
		// Attempt insert
		$Result = @mysql_query("INSERT INTO preference (user_id, title, name, value) VALUES
				  ('$user_id', '$title', '$name', '$value')");
	}


	return $Result;
}

function preferenceDeclare($title, $name, $default_value="")
{
	global	$GLOBALS, $HTTP_GET_VARS, $HTTP_POST_VARS;

	if (isset($HTTP_GET_VARS[$name]))
	{
		preferenceSet($title, $name, $HTTP_GET_VARS[$name]);
	}
	else
	if (isset($HTTP_POST_VARS[$name]))
	{
		preferenceSet($title, $name, $HTTP_POST_VARS[$name]);
	}

	return preferenceGet($title, $name, $default_value);
}

function preferenceGetAll($title)
{
	global	$login_operator_id;

	// Init
	$Str	= "";

	// Get user id
	$user_id	= "";
	if (!GetUserID(&$user_id))
	{
		return false;
	}

	// Get record
	$Result	= @mysql_query("SELECT name,value FROM preference 
			  WHERE user_id='$user_id' AND title='$title'");

	// Found anything?
	$count = mysql_num_rows($Result);
	while ($count > 0)
	{
		$Row = mysql_fetch_row($Result);

		if (isset($NOT_FIRST))
		{
			$Str .= "&";
		}

		$Str .= $Row[0] . "=" . $Row[1];

		$count--;
		$NOT_FIRST = true;
	}

	return $Str;
}

function preferenceGet($title, $name, $default_value = "")
{
	global		$login_operator_id, $GLOBALS;

	// Get user id
	$user_id	= "";
	if (!GetUserID(&$user_id))
	{
		return false;
	}

	// Get record
	$Result	= @mysql_query("SELECT value FROM preference WHERE user_id='$user_id' AND title='$title' AND name='$name'");

	// Found anything?
	$count = @mysql_num_rows($Result);
	if ($count > 0)
	{
		$Row = @mysql_fetch_row($Result);

		return ($Row[0]);
	}

	return $default_value;
}


?>

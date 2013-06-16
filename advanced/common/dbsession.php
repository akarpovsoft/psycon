<?php
//require_once("database.php");

function sessionOpen($save_path, $session_name)
{
	global	$HANDLER;

	// Open database
	if (!$HANDLER = db_connect())
	{	
		die;
	}

	// If session table doesn't exist,
	if (!mysql_query("SELECT session_key FROM sessions LIMIT 1"))
	{
		// Create table
		if (!mysql_query("CREATE TABLE sessions (
							session_key CHAR(32) NOT NULL,
							session_expire INT(11) UNSIGNED NOT NULL,
							session_value TEXT NOT NULL,
							PRIMARY KEY (session_key)
						)"))
		{
			die ($lang['dbsession_msg_1']);
		}
	}

	// (If we're up to here - [sessions] table exists)
	return true;
}

function sessionClose()
{
	return true;
}

function sessionDeclare($session_key, $session_defvalue = "")
{	
	global	$HTTP_GET_VARS;

	if (session_is_registered("$session_key"))
	{
		$value = $GLOBALS["$session_key"];
		return $value;
	}

	if (empty($session_defvalue))
	{
		return "";
	}

	$GLOBALS["$session_key"] = $session_defvalue;

	session_register("$session_key");

	return $session_defvalue;
}

function sessionRead($session_key)
{
	global	$session;

	$session_key = _addslashes($session_key);

	$session_session_value = 
		mysql_query("SELECT session_value FROM sessions WHERE session_key = '$session_key'");

	if (mysql_numrows($session_session_value) == 1)
	{
		return mysql_result($session_session_value, 0);
	}
	else
	{
		return false;
	}
}

function sessionWrite($session_key, $val)
{
	global	$session;
	
	$session_key = _addslashes($session_key);
	$val = _addslashes($val);
	$session = @mysql_result(mysql_query("SELECT COUNT(*) FROM sessions WHERE session_key = '$session_key'"), 0);
	
	if ($session == 0)
	{
		$return = @mysql_query	("INSERT INTO sessions (session_key, session_expire, session_value)
								VALUES ('$session_key', UNIX_TIMESTAMP(NOW()), '$val')");
	}	
	else
	{
		$return = @mysql_query	("UPDATE sessions SET session_value = '$val', session_expire = UNIX_TIMESTAMP(NOW())
								WHERE session_key = '$session_key'");

		if (@mysql_affected_rows() < 0)
		{
			// echo "Failed updating session key $session_key";
		}
	}

	return $return;
}

function sessionDestroyer($session_key)
{
	global	$session;

	$session_key	= _addslashes($session_key);
	
	$return = mysql_query("DELETE FROM sessions WHERE session_key = '$session_key'");

	return $return;
}

function sessionGc ($maxlifetime)
{
	global	$session;

	$expirationTime = time() - $maxlifetime;

	$return = mysql_query("DELETE FROM sessions WHERE session_expire < $expirationTime");

	return $return;
}

session_set_save_handler
(
	'sessionOpen',
	'sessionClose',
	'sessionRead',
	'sessionWrite',
	'sessionDestroyer',
	'sessionGc'
);
?>

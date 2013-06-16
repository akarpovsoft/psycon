<?php 
require_once ("common.php");
require_once ("httpclient.php");

function EchoAllParameters()
{
	global	$HTTP_POST_VARS, $HTTP_GET_VARS, $HTTP_POST_FILES;

	$add_separator	= false;

	if (isset($HTTP_POST_VARS))  $add_separator = EchoArray($HTTP_POST_VARS);
	if (isset($HTTP_GET_VARS))	 $add_separator = EchoArray($HTTP_GET_VARS, $add_separator);
	if (isset($HTTP_POST_FILES)) $add_separator = EchoArray($HTTP_POST_FILES, $add_separator);
}

function EchoArray($Array, $add_separator = false)
{
	global	$GLOBALS;

	if (count($Array)<1)
	{
		return false;
	}

	foreach ($Array as $Name => $Value)
	{
		// Skip if empty
		if (empty($Name))
		{
			continue;
		}

		if ($add_separator)
		{
			echo "&";
		}
		$add_separator = true;

		// If exist in global realm,
		if (isset($GLOBALS["$Name"]))
		{
			// Get value from there
			$Value = $GLOBALS["$Name"];
		}

		echo urlencode("$Name")."=".urlencode(("$Value"));
	}

	return $add_separator;
}


function HTTPPostAll($rr_processor_page, $rr_processor_host = "localhost", $update_params = false)
{
	global	$HTTP_POST_VARS, $HTTP_GET_VARS, $HTTP_POST_FILES;

	$result			= false;

	$ArrayValues	= array();

	// Init
	if ($rr_processor_page[0] != "\\") $rr_processor_page = "\\".$rr_processor_page;

	// Gather all GET/POST variables into <ArrayValues>
	if (isset($HTTP_POST_VARS))  $ArrayValues = array_merge($ArrayValues, $HTTP_POST_VARS);
	if (isset($HTTP_GET_VARS))	 $ArrayValues = array_merge($ArrayValues, $HTTP_GET_VARS);
	if (isset($HTTP_POST_FILES)) $ArrayValues = array_merge($ArrayValues, $HTTP_POST_FILES);

	// Strip slashes
	@array_walk(&$ArrayValues, "StripSlashesFromArray", 0);

	// Search for files
	foreach ($HTTP_POST_FILES as $Name => $Value)
	{
		$FileName		= $Name."_name";
		$FileType		= $Name."_type";

		if (isset($GLOBALS["$FileName"]))
		{
			// Add it
			$ArrayValues = array_merge($ArrayValues, array($FileName => $GLOBALS["$FileName"]));
			$ArrayValues[$Name] = $GLOBALS["$Name"];
		}

		if (isset($GLOBALS["$FileType"]))
		{
			$ArrayValues = array_merge($ArrayValues, array($FileType => $GLOBALS["$FileType"]));
		}
	}

	// Open socket connection
	$http = new Net_HTTP_Client();

	// Connect
	if (!$http->Connect( $rr_processor_host, 80 ))
	{
		return false;
	}

	// No debug messages please
	$http->setDebug(0);//DBGSOCK | DBGOUTDATA);

	// Post
	$status = $http->post( $rr_processor_page, $ArrayValues );

	$headers = $http->getHeaders();

	// document is somewhere else
	if( $status == 301 || $status == 302 || $status == 307 )
	{
		// Go there
		Header($headers["Location"]);

		// Success
		$result = true;
	}
	// (Else - document is here)
	else
	{
		// Set return value
		$result = ($status==200);

//			echo $http->getBody();
//die;
		// If not requested to update params
		if (!$update_params)
		{
			// Just Print body
			echo $http->getBody();
		}
		// (Else - we need to update params)
		else
		{
			// Read returned parameters
			ReadParameters($http->getBody());
		}
	}

	// Return success
	return $result;
}

function URLParametersToFormInputs($Parameters)
{	
	// If first character is '?'
	if (strlen($Parameters)>0)
	if ($Parameters[0] == '?')
	{
		// Remove it
		$Parameters = substr($Parameters, 1, strlen($Parameters)-1);
	}

	do
	{
		if (($next = _strpos($Parameters,"&")) == -1)
		{
			$next = strlen($Parameters);
		}
		
		if (($separator = _strpos($Parameters,"=")) != -1)
		{
			$Name	= trim(substr($Parameters, 0, $separator));
			$Value	= urldecode(substr($Parameters, $separator+1, max(0,$next-($separator+1))));

			// Echo input
			echo "<INPUT TYPE=HIDDEN NAME=$Name VALUE=\"$Value\">\n";
		}
		else
		{
			break;
		}
	
		$Parameters = substr($Parameters, $next+1, strlen($Parameters) - ($next+1));	

	} while (true);
}

function ReadParameters($Parameters)
{
	global	$HTTP_POST_VARS, $HTTP_GET_VARS, $HTTP_POST_FILES;

//	echo $Parameters;
//	echo "<BR><BR>";

//	echo "In Read Parameters:<BR>";

	do
	{
		if (($next = _strpos($Parameters,"&")) == -1)
		{
			$next = strlen($Parameters);
		}
		
		if (($separator = _strpos($Parameters,"=")) != -1)
		{
			$Name	= trim(substr($Parameters, 0, $separator));
			$Value	= urldecode(substr($Parameters, $separator+1, max(0,$next-($separator+1))));

			// Update parameters
			if (isset($HTTP_POST_VARS["$Name"]))	$HTTP_POST_VARS["$Name"]	= $Value;
			if (isset($HTTP_POST_FILES["$Name"]))	$HTTP_POST_FILES["$Name"]	= $Value;
			if (isset($HTTP_GET_VARS["$Name"]))		$HTTP_GET_VARS["$Name"]		= $Value;
			if (isset($GLOBALS["$Name"]))			$GLOBALS["$Name"]			= $Value;

//			echo "$Name = $Value<BR>";
		}
		else
		{
			break;
		}
	
		$Parameters = substr($Parameters, $next+1, strlen($Parameters) - ($next+1));	

	} while (true);
}
?>
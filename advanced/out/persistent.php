<?php 
require_once ("common.php");
require_once ("httpclient.php");

function UpdateIndexes(&$ImageArray, $posstart, $delta)
{
	for ($i=0; $i<count($ImageArray); $i++)
	{
		$count		= $ImageArray[$i]["count"];

		for ($j=0; $j<$count; $j++)
		{
			$index	= $j+1;
			$start	= $ImageArray[$i]["start".$index];
			$end	= $ImageArray[$i]["end".$index];

			if ($start >= $posstart)
			{
				$ImageArray[$i]["start".$index] = $start + $delta;
				$ImageArray[$i]["end".$index]	= $end + $delta;
			}
		}
	}
}

function SaveImagesToPersistentStorage($account_id, $message_text, &$persistentok)
{
	global	$DOCUMENT_ROOT, $RR_HOST, $DIR_DATA;

	// Turn on <Persistentok> to mark all images have been copied to 
	// persistent storage successfuly
	// (If an error occurs later, we will turn off this flag)
	$persistentok	= true;

	// Set these for easier access
	$delta			= 0;
	
	// Get index of all embedded images inside <message_text>
	$ImageArray		= GetEmbeddedImages($message_text);

	// Iterate through all images,
	// Downloading them to persistent storage
	for ($i=0; $i<count($ImageArray); $i++)
	{
		// Set these for easier access
		$src		= $ImageArray[$i]["src"];
		$count		= $ImageArray[$i]["count"];

		// If this image is already in persistent storage,
		$PERSISTENT_STR	= $RR_HOST.$DIR_DATA.$account_id."/persistent/";
		if (Strcasecmp(substr($src, 0, strlen($PERSISTENT_STR)), $PERSISTENT_STR)==0)
		{
			// Skip this one
			continue;
		}

		// Download to persistent storage
		$newsrc = DownloadToPersistentStorage($account_id, $ImageArray[$i]["src"]);

		// If failed
		if (empty($newsrc))
		{
			// Turn on error flag
			$persistentok = false;

			// Skip to next one
			continue;
		}

		// Replace image src
		for ($j=0; $j<$count; $j++)
		{
			$index	= $j+1;
			$start	= $ImageArray[$i]["start".$index];
			$end	= $ImageArray[$i]["end".$index];

			$message_text = substr($message_text, 0, $start) . $newsrc . substr($message_text, $end);

			// Calculate delta between original <src> and <newsrc>
			// (So that future modifications to <message_text> will work properly
			$delta = strlen($newsrc) - ($end - $start);

			UpdateIndexes(&$ImageArray, $start, $delta);
		}
	}

	return $message_text;
}

function DownloadToPersistentStorage($account_id, $src)
{
	global	$DOCUMENT_ROOT, $DIR_DATA, $RR_HOST;

	// Initialize
	$HTTP_STR		= "http://";
	$dest			= "";
	$tempfilename	= "";

	$orig_src = $src;

	do
	{

		// Validate
		if (empty($src))
		{
			break;
		}

		// If local file
		if ($src[0] == '/' ||
			$src[0] == '\\')
		{
			// Add 'localhost' (otherwise fopen will not work properly)
			$src = "http://localhost" . $src;
		}

		// If doesn't start with 'http://'
		if (Strcasecmp(substr($src, 0, strlen($HTTP_STR)), $HTTP_STR) != 0)
		{
//			echo "cant handle: $src ".substr($src, 0, strlen($HTTP_STR))."<BR>";
			// We can't handle that
			break;
		}

		// Strip 'http://'
		$src = substr($src, strlen($HTTP_STR));

		// Get host
		if (($host_end = _strpos($src, "/")) == -1)
		{
//echo "can't handle";
			// We can't handle that
			break;
		}

		$host	= substr($src, 0, $host_end);
		$url	= substr($src, $host_end);

		// Open socket connection
		$http = new Net_HTTP_Client();

		// Get temp file name
		$tempfilename = tempnam($DOCUMENT_ROOT."/temp", "FOO");

		// If failed,
		if (Strcasecmp($tempfilename,"false")==0)
		{
//			echo "temp file name failed<BR>";
			// Can't get temp file name
			break;
		}

		// Connect
		if (!$http->Connect( $host, 80 ))
		{
//			echo "failed connecting: $host<BR>";
			break;
		}

		// No debug messages please
		$http->setDebug(0);//DBGSOCK|DBGINDATA|DBGOUTDATA);//DBGSOCK | DBGOUTDATA);

//$url = substr($url,1);

		// Post
		$status = $http->get( $orig_src );

		// If failed,
		if ($status != 200)
		{
//echo "fail $status [$orig_src] [$host]";
			// Can't handle that
			break;
		}

		// Get headers and content length
		$headers		= $http->getHeaders();
		$contentLength	= $headers["Content-Length"];

		// Get body
		$body = $http->getBody();

		// If size doesn't match content-length
		if (strlen($body) != $contentLength)
		{
//echo "bad length";
			// Can't handle that
			break;
		}

		// Write destination
		$file = @fopen($tempfilename, "w+");
		@fwrite($file, $body, $contentLength);
		@fclose($file);

		// Verify size
		if (@filesize($tempfilename) != $contentLength)
		{
//			echo "Write failed: ".@filesize($dest)." $contentLength<BR>";

			// Failed writing
			break;
		}

		// Insert to persistent storage
		{
			// Set dbrecord array
			$dbrecord = array();
			$dbrecord["origname"]	= $host.$url;
			$dbrecord["size"]		= $contentLength;

			// Insert
			$RecordID	= storageInsertUserData($account_id, "Persistent", 
						  $dbrecord);
		}

		// If unsuccessful
		if ($RecordID <= 0)
		{
//			echo "Persistent Store fail<BR>";
			// Failed storing
			break;
		}

		// Set target filename
		$targetfilename = "/persistent/$RecordID.".FileGetType($url);

		// Successful,
		// Copy to target directory
		if (!@copy($tempfilename, $DOCUMENT_ROOT.$DIR_DATA.$account_id.$targetfilename))
		{
//			echo "copy fail: $tempfilename ==> $targetfilename<BR>";
			// Failed copying
			break;
		}

		// Successful - set destination file name
		$dest = "http://".$RR_HOST.$DIR_DATA.$account_id.$targetfilename;

	} while (false);

	// Delete temp file
	if (!empty($tempfilename))
	{
		unlink($tempfilename);
	}

	// Return destination filename
	// (Or if unsuccessful - empty string)
	return $dest;
}

// --- Internal functions

function IGetEmbeddedImages($text, $Prefix, $Suffix)
{
	// Initialize
	$pos				= 0;
	$str				= $text;

	$ImageArray=array();

	// Find image start
	while (($start = _strpos($str,$Prefix)) != -1)
	{
		$strtemp =  substr($str, $start, 512);
	
		// Find image end
		if (($end = _strpos($strtemp, $Suffix)) == -1)
		{
			// Didn't find image end
			$ResultStr = "ERROR - <img> tag not closed properly: '".htmlspecialchars(strcut($str, 50))."'";
			break;
		}

		// Add src to array
		{
			$imgtag = substr($str, $start, $end+1);

			do
			{
				$src_start = 0;

				// Find 'src'
				if (($i = _strpos($imgtag, "src")) == -1)
				{
					if (($i = _strpos($imgtag, "background")) == -1)
					{
						break;
					}
				}
				$imgtag		= substr($imgtag, $i);
				$src_start	+= $i;

				// Find '='
				if (($i = _strpos($imgtag,"=")) == -1)
				{
					break;
				}
				$i++;

				// Find first character
				while ($imgtag[$i]==' ' && $i+1<strlen($imgtag))
				{
					$i++;
				}
				$imgtag		= substr($imgtag, $i);
				$src_start	+= $i;

				// If first character is a quote
				if ($imgtag[0]=='"')
				{
					$imgtag		= substr($imgtag, 1);
					$src_start++;

					// Find end
					if (($src_end = _strpos($imgtag, '"')) == -1)
					{
						break;
					}
				}
				// (Else - not a quote)
				else
				// If first character is an unpercent
				if ($imgtag[0]=="'")
				{
					$imgtag		= substr($imgtag, 1);
					$src_start++;

					// Find end
					if (($src_end = _strpos($imgtag, "'")) == -1)
					{
						break;
					}
				}
				// (Else - not an unpercent)
				else
				// Just look for next space
				{
					$src_end = 0;
					while ($imgtag[$src_end]!=' ' && $src_end+1<strlen($imgtag))
					{
						$src_end++;
					}
				}

				// Cut
				$imgtag = substr($imgtag, 0, $src_end);
//				echo "Imgtag = $imgtag<BR>";

				// Add
				AddToImageArray(&$ImageArray, $imgtag, ($start+$src_start+$pos), ($src_end+$pos+$start+$src_start));

			} while (false);
		}

		// Cut string and update counter
		$str	= substr($str, $end+$start);
		$pos	+= $end+$start;
	}

	// Return image array
	return $ImageArray;
}

function GetEmbeddedImages($text)
{
	$Array1 = IGetEmbeddedImages($text, "<img ", ">");
	$Array2 = IGetEmbeddedImages($text, ".src", ";");
	$Array3 = IGetEmbeddedImages($text, "background", ">");

	return array_merge(array_merge($Array1, $Array2), $Array3);
}

function AddToImageArray(&$ImageArray, $src, $start, $end)
{
	$RecordID	= -1;
	$count		= 0;

	// Iterate through <ImageArray>, searching if we already
	// have an image with the same <src>
	// (This is done to ensure we don't download the same file for
	// persistent storage more than once)
	for ($i=0; $i<count($ImageArray); $i++)
	{
		if (Strcasecmp($ImageArray[$i]["src"], $src)==0)
		{
			// Found match
			$RecordID	= $i;
			$count		= $ImageArray[$i]["count"];
			break;
		}
	}

	// Prepare record
	$count++;
	$dbrecord = array("src" => $src, "count" => $count, "start".$count => $start, "end".$count => $end);

	// Insert record
	if ($RecordID != -1)
	{
		$ImageArray[$RecordID] = array_merge(&$ImageArray[$RecordID], $dbrecord);
	}
	else
	{
		$ImageArray[] = $dbrecord;
	}

	// Return success
	return true;
}

?>
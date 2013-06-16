<?php

function _addslashes($Item)
{
        $NewItem = "";

        for ($i=0; $i<strlen($Item); $i++)
        {
                if ($Item[$i] == "\r")
                {
                        $NewItem .= "\\"."r";
                }
                else
                if ($Item[$i] == "\n")
                {
                        $NewItem .= "\\"."n";
                }
                else
                {
                        $NewItem .= $Item[$i];
                }
        }
        $Item = $NewItem;

        $Item = addslashes($Item);

        return $Item;
}

function get_cc_user($cc_number)
{
	global $conn;
	
	$sql = "SELECT T1_1.*  from T1_1 , T1_4 where T1_1.rr_record_id=T1_4.rr_record_id AND cardnumber LIKE '".preg_replace("/[\*]{1,}/", "%", $cc_number)."'";
	$res = mysql_query($sql) or die(mysql_error());
	$result=array();
	while($row = mysql_fetch_array($res))
		$result[]=$row;
	mysql_free_result($res);
	
	return $result;
}


function _stripslashes($Item)
{
        $NewItem = "";

        $i = 0;
        while ($i < strlen($Item))
        {
                if ($i+1 < strlen($Item) &&
                    $Item[$i] == "\\" &&
                    $Item[$i+1] == "r")
                {
                        $NewItem .= "\r";
                        $i+=2;
                }
                else
                if ($i+1 < strlen($Item) &&
                    $Item[$i] == "\\" &&
                    $Item[$i+1] == "n")
                {
                        $NewItem .= "\n";
                        $i+=2;
                }
                else
                {
                        $NewItem .= $Item[$i];
                        $i++;
                }
        }
        $Item = $NewItem;

        $Item = stripslashes($Item);

        return $Item;
}

function StripSlashesFromArray(&$Value, $Key, $Temp)
{
        $Value = _stripslashes($Value);
}

function StrRemoveDirectories($Str, $Num_Dirs_To_Remove)
{
        for ($i=0; $i<$Num_Dirs_To_Remove; $i++)
        {
                $SlashPos = _strpos($Str, "/");

                if ($SlashPos != -1)
                {
                        $Str = substr($Str, $SlashPos+1, strlen($Str) - ($SlashPos+1));
                }
        }

        return $Str;
}

function AddSlashesToArray(&$Value, $Key, $Temp)
{
        $Value = _addslashes($Value);
}

function htmlsafe($Value)
{
        return htmlspecialchars(_stripslashes($Value));
}

function _strrpos($haystack, $needle)
{
        $result        = (int)strrpos(strtolower($haystack), strtolower($needle));

        // If result is zero,
        // We need to check for PHP bug where '0' is returned both when there is a match
        // beginning in the first character AND when there are no matches at all
        if ($result == 0)
        {

                // Is this a match beginning in the first character?
                if (Strcasecmp(substr($haystack, 0, strlen($needle)), $needle) == 0)
                {
                        // Yes - return zero
                        return 0;
                }
                else
                {
                        // No - return (-1) to indicate error
                        return -1;
                }
        }

        return $result;
}

function _strpos($haystack, $needle)
{
        $result        = (int)strpos(strtolower($haystack), strtolower($needle));

        // If result is zero,
        // We need to check for PHP bug where '0' is returned both when there is a match
        // beginning in the first character AND when there are no matches at all
        if ($result == 0)
        {

                // Is this a match beginning in the first character?
                if (Strcasecmp(substr($haystack, 0, strlen($needle)), $needle) == 0)
                {
                        // Yes - return zero
                        return 0;
                }
                else
                {
                        // No - return (-1) to indicate error
                        return -1;
                }
        }

        return $result;
}

function firstword($s)
{
        $first_space = _strpos($s," ");

        // If we have a space
        if ($first_space != -1)
        {
                // Return all characters up to this space
                return substr($s, 0, $first_space);
        }

        // (Else - no space)
        // Return entire string
        return $s;
}
function strcut($Str, $Len)
{
        if ((strlen($Str)<$Len) || ($Len<5) || ($Len==-1))
        {
                return $Str;
        }

        $Str[$Len-3] = '.';
        $Str[$Len-2] = '.';
        $Str[$Len-1] = '.';
        $Str = substr($Str, 0, $Len);//$Str[$Len-1] = 0;

        return $Str;
}

function reformat_string($str)
{
        // Verify if this is a date
        $tmp = reformat_date($str, "");
        $is_date = !empty($tmp);

        // If this is a timestamp,
        if (strlen($str) == 14 &&
                is_numeric($str))
        {
                // Format as timestamp
                $temp = reformat_timestamp($str,"");

                // If successful,
                if ( !empty($temp) )
                {
                        // Store
                        $str = $temp;
                }
        }
        // (Else - not a timestamp)
        else
        // If this is a date
        if ($is_date)
        {
                // Format as a date
                $str = reformat_date($str, "");
        }
        else
        // If this is a number
        if (is_numeric($str))
        {
                $str = number_format($str);
        }

        return $str;
}

function longdate_from_timestamp($timestamp, $fail_str = "N/A")
{
  // Validate
  if (strlen($timestamp) < 14)
  {
        return $fail_str;
  }

  $year                = 0+substr($timestamp, 0, 4);
  $month        = 0+substr($timestamp, 4, 2);
  $day                = 0+substr($timestamp, 6, 2);

  return date("l, F jS, Y", mktime(0,0,0,$month,$day,$year));
}

function shortdate_from_timestamp($timestamp, $fail_str = "N/A")
{
  // Validate
  if (strlen($timestamp) < 14)
  {
        return $fail_str;
  }

  $year                = 0+substr($timestamp, 0, 4);
  $month        = 0+substr($timestamp, 4, 2);
  $day                = 0+substr($timestamp, 6, 2);

  return date("M j y", mktime(0,0,0,$month,$day,$year));
}

function reformat_timestamp($timestamp, $fail_str = "N/A", $include_time = true)
{
  // Validate
  if (strlen($timestamp) < 14)
  {
        return $fail_str;
  }

  $year                = substr($timestamp, 0, 4)+0;
  $month        = substr($timestamp, 4, 2)+0;
  $day                = substr($timestamp, 6, 2)+0;
  $hour                = substr($timestamp, 8, 2)+0;
  $minute        = substr($timestamp, 10, 2)+0;

  return strftime("%b %e, %Y %H:%M", mktime($hour,$minute,$second,$month,$day,$year));
}

function StripCRLF($Str, $Replacement = "")
{
        $Str = str_replace("\r", "", $Str);
        $Str = str_replace("\n", $Replacement, $Str);

        return $Str;
}

function reformat_date($datetime, $fail_str = "N/A")
{
  $Months = array($lang['Jan'], $lang["Feb"], $lang["Mar"], $lang["Apr"], $lang["May"], $lang["Jun"], $lang["Jul"], $lang["Aug"], $lang["Sep"], $lang["Oct"], $lang["Nov"], $lang["Dec"]);

  // put date in US format, discard seconds
  @list($year, $month, $day, $hour, $min, $sec) = split( '[.: -]', $datetime );

  // Validate
  if (!isset($year) || !isset($month) || !isset($day))
  {
        return $fail_str ;
  }

  $month = $month+0;
  $day = $day+0;
  $year = $year+0;

  if ($month<1 || $month>12 || $day<1 || $day>31)
  {
    return $fail_str;
  }

  if ($year<2000)
  {
        $year += 2000;
  }

  $month--;
  if ($day[0]=='0' && strlen($day)>1)
  {
        $day = substr($day, 1, 1);
  }

  return "$Months[$month] $day, $year";
}

function arrayvalue($Array, $Fieldname, $Len = -1, $Safe = "")
{
        $Str        = $Safe;

        if (!isset($Array))
        {
                return $Str;
        }
        if (empty($Array))
        {
                return $Str;
        }

        if (!empty($Array[$Fieldname]))
        {
                $Item = $Array[$Fieldname];

                if ($Len != -1)
                {
                        $Item = strcut($Item, $Len);
                }

                $Str = $Item;
        }

        return $Str;
}

function textarrayvalue($Array, $Fieldname, $Len = -1, $Safe = "")
{
        $Str        = $Safe;

        if (!isset($Array))
        {
                return $Str;
        }
        if (empty($Array))
        {
                return $Str;
        }

        if (!empty($Array[$Fieldname]))
        {
                $Item = $Array[$Fieldname];

                if ($Len != -1)
                {
                        $Item = strcut($Item, $Len);
                }

                $Str = $Item;

                $Str = htmlspecialchars($Str);

        }

        return $Str;
}

function htmlarrayvalue($Array, $Fieldname, $Len = -1, $Safe = "", $AddBR=false)
{
        $Str        = $Safe;

        if (!isset($Array))
        {
                return $Str;
        }
        if (empty($Array))
        {
                return $Str;
        }

        if (!empty($Array[$Fieldname]))
        {
                $Item = $Array[$Fieldname];

                if ($Len != -1)
                {
                        $Item = strcut($Item, $Len);
                }

                $Str = $Item;

                $Str = htmlspecialchars($Str);

                if ($AddBR)
                {
                        $Str = stri_replace("\r", "", $Str);
                        $Str = stri_replace("\n", "<BR>", $Str);
                }
                else
                {
                        $Str = stri_replace("\r", "\\r", $Str);
                        $Str = stri_replace("\n", "\\n", $Str);
                }
        }

        return $Str;
}

function get_user($userid)
{
	global $conn;
	
	$sql = "SELECT *  from T1_1 where rr_record_id='$userid'";
	$res = mysql_query($sql) or die(mysql_error());

	$row = mysql_fetch_array($res);
	mysql_free_result($res);
	
	return $row;
}

function get_unique($length)
{
	srand ((double) microtime() * 10000000);
	$input = array("1","2","3","4","5","6","7","8","9");
	$unique="";
	$rand_keys = array_rand ($input, $length);
	for ($c_counter = 0; $c_counter < $length; $c_counter++){
		$unique.=$input[$rand_keys[$c_counter]];
	}
	return $unique;
}

function sql_values($data, $escape=false)
{
	$fields="";
	while(list($key, $value)=each($data))
	{
		if($value===null || $value==='' || $value===false)
			$fields.=(($fields!='')?", ":"")."`".$key."`=NULL";
		else
		{
			if($escape)
				$value=addcslashes($value, "\\\'");
			$fields.=(($fields!='')?", ":"")."`".$key."`='".$value."'";
		}
	}
	return $fields;
}


function maskChars($data, $len_unmasked)
{
	$clen=strlen($data);
	$res = preg_replace("/^\d{".($clen-$len_unmasked)."}/", "*", $data);
	return $res;
}

function displayInfo($data)
{
	$forbidden_keys=array('ccard', 'cvv', 'card_number', 'cnp_security', 'address');
	while(list($sign_key, $sign_val) = each($data))
	{
		if(!empty($sign_val) && !in_array($sign_key, $forbidden_keys))
		{
			$all_info .= ucfirst($sign_key).": $sign_val<br>";
		}
	}
	return $all_info;
}

function getAffiliate()
{
	global $affiliate;
	$affiliate = @$_COOKIE["Affiliate"];
	if(strlen($affiliate) < 1){
		$affiliate = $_SESSION["affiliate"];
	}
	return $affiliate;
}

function stri_replace( $find, $replace, $string )
{
        $new_string = "";

        // Validate
        if (empty($find))
        {
                return $string;
        }

        $cnt=0;
        $length = strlen($string);
        for ($i=0; $i<$length; $i++)
        {
                // If this is a character match
                if (strtolower($string[$i]) == strtolower($find[$cnt]))
                {
                        // Increase counter
                        $cnt++;

                        // If this is a full match
                        if ($cnt >= strlen($find))
                        {
                                // Reset
                                $cnt = 0;

                                // Add <replace> to our new string
                                $new_string .= $replace;
                        }
                }
                // (Else - not a character match)
                else
                // Just add it
                {
                        // If we have any pending leftovers
                        while ($cnt>0)
                        {
                                $new_string .= $string[$i-$cnt];
                                $cnt--;
                        }

                        $new_string .= $string[$i];
                }
        }

        // If we have any pending leftovers
        if ($cnt>0)
        {
                // Add it
                while ($cnt>0)
                {
                        $new_string .= $string[strlen($string)-$cnt];
                        $cnt--;
                }
        }

    return( $new_string );
}

function DrawWizardStep($step, $text, $selected = false, $URL)
{
        if (empty($text))
        {
                return;
        }

//        if (empty($URL))
        {
                if ($selected)
                {
                        echo "<SPAN class=TextStepSelected><img src=\"images/".$step."r.gif\" width=22 height=19 align=top>";
//                        echo "<img src=\"images/".$step."r.gif\" width=22 height=19 align=absmiddle>";
                }
                else
                {
                        echo "<SPAN class=TextStep><img src=\"images/".$step."b.gif\" width=22 height=19 align=top>";
//                        echo "<img src=\"images/".$step."b.gif\" width=22 height=19 align=absmiddle>";
                }
        }
//        else

        if (!empty($URL)) echo "onMouseOver=\"window.status='$text'; return true;\""."onMouseOut=\"window.status=''; return true;\">";
        echo $text;
        if (!empty($URL)) echo "</a>";

        echo "</SPAN>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
}

function CheckChatfileExists( $account_id, $is_client, $reader_id,
$client_id)
{
        global $DOCUMENT_ROOT ;

        if ($is_client)
        {
                $prefix = "client";
        }
        else
        {
                $prefix = "reader";
        }

        $sp = "_";

        return file_exists("$DOCUMENT_ROOT/temp/$prefix$sp$reader_id$sp$client_id.html");
}

function ReadFromChatfile( $account_id, $is_client, $reader_id,
$client_id)
{
        global $DOCUMENT_ROOT ;

        if ($is_client)
        {
                $prefix = "client";
        }
        else
        {
                $prefix = "reader";
        }

        $sp = "_";

        if (!file_exists("$DOCUMENT_ROOT/temp/$prefix$sp$reader_id$sp$client_id.html"))
        {
                return "";
        }

        @mysql_query("GET LOCK('$reader_id$client_id',60)");

        $fp = @fopen("$DOCUMENT_ROOT/temp/$prefix$sp$reader_id$sp$client_id.html", "r");
        $result = @fread( $fp, 5000 ) ;
        @fclose( $fp ) ;

        @unlink("$DOCUMENT_ROOT/temp/$prefix$sp$reader_id$sp$client_id.html");

        @mysql_query("RELEASE LOCK('$reader_id$client_id',60)");

        return $result;
}

function DeleteChatfiles( $account_id, $reader_id,
$client_id)
{
        global $DOCUMENT_ROOT ;

        $sp = "_";

        @mysql_query("GET LOCK('$reader_id$client_id',60)");

        @unlink("$DOCUMENT_ROOT/temp/client$sp$reader_id$sp$client_id.html");
        @unlink("$DOCUMENT_ROOT/temp/reader$sp$reader_id$sp$client_id.html");

        @mysql_query("RELEASE LOCK('$reader_id$client_id',60)");
}

function AppendToChatfile( $account_id, $is_client, $reader_id,
$client_id, $script_id, $string )
{
        if ( ( empty($string) ) )
        {
                return false ;
        }

        if ($is_client==1)
        {
                $prefix = "client";
        }
        else
        {
                $prefix = "reader";
        }

        $sp = "_";

        global $DOCUMENT_ROOT ;

        @mysql_query("GET LOCK('$reader_id$client_id',60)");


        $fp = @fopen("$DOCUMENT_ROOT/temp/$prefix$sp$reader_id$sp$client_id.html", "a");
        @fwrite( $fp, _stripslashes($string), strlen( $string ) ) ;
        @fclose( $fp ) ;

        $size = @filesize("$DOCUMENT_ROOT/data/$account_id/chatsessions/$reader_id$sp$client_id.html");

        $fp = @fopen("$DOCUMENT_ROOT/data/$account_id/chatsessions/$reader_id$sp$client_id$sp$script_id.html", "a");
        // If file empty
        if ($size<1)
        {
                $header = PrintHeader("","",false);
                @fwrite($fp, $header, strlen($header));
                // Add header
        }
        @fwrite( $fp, _stripslashes($string), strlen( $string ) ) ;
        @fclose( $fp ) ;

        @mysql_query("RELEASE LOCK('$reader_id$client_id',60)");

        return true ;
}

function GetTypeStr($type)
{
	global $lang;
        switch ($type)
        {
                case 'Administrator':
                        return "<font color=green><b>".$lang['Administrator'] ."</b></font>";
                break;

                case 'reader':
                        return "<font color=blue><b>".$lang['Reader'] ."</b></font>";
                break;

                case 'client':
                        return $lang['Client'];
                break;

                default:
                        return $type;
                break;
        }

}


function redirect($url)
{
	@mysql_close();
	header("Location:".$url);
	die();
}

function TrimAllSpaces($Str)
{
        $NewStr = "";

        // Set these for easier access
        $length = strlen($Str);

        // Trim in between
        for ($i=0; $i<$length; $i++)
        {
                // If this is the last character
                if ($i == $length-1)
                {
                        // Just grab it
                        $NewStr .= $Str[$i];
                        continue;
                }

                // (Not last character)
                // If this is NOT space or it's a space but the next character is NOT  a space
                if ($Str[$i]!=" " ||
                    ($Str[$i]==" " && $Str[$i+1]!=" "))
                {
                        // Grab it
                        $NewStr .= $Str[$i];
                }
        }

        // Trim prefix and suffix
        $NewStr = trim($NewStr);

        return $NewStr;
}

function NonEmpty($A, $B)
{
        if (!empty($A))
        {
                return $A;
        }

        return $B;
}


?>

<?php

if (!function_exists('StripCRLF'))
{
function StripCRLF($Str, $Replacement = "")
{
        $Str = str_replace("\r", "", $Str);
        $Str = str_replace("\n", $Replacement, $Str);

        return $Str;
}
}

if (!function_exists('_stripslashes'))
{
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
//        $Item = stripcslashes($Item);

        return $Item;
}
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
        switch ($type)
        {
                case 'Administrator':
                        return "<font color=green><b>$type</b></font>";
                break;

                case 'reader':
                        return "<font color=blue><b>Psychic Reader</b></font>";
                break;

                case 'client':
                        return "Client";
                break;

                default:
                        return $type;
                break;
        }

}

function DrawStatus($account_id, $id)
{
    global $http_addr;
    srand((double)microtime()*1000000);

        $randomid = rand(0,50000);

?>

        <img src="<?php echo $http_addr;?>/chatgetstatus.php?account_id=<?=$account_id?>&operator_id=<?=$id?>&unique=<?=$randomid?>" name=statusimage<?=$id?> width=92 height=25 border=0>
        <SCRIPT LANGUAGE="JavaScript">
        <!--

        //-->
        </SCRIPT>
<?php
}

function PrintLineSpace($ignore=true)
{
        return "<table cellspacing=0 cellpadding=0 border=0><tr><td height=5 nowrap><img src=/images/transp.gif height=5></td></tr></table>";
}

function PrintSysMessage($text, $lineseparator=true)
{
        if ($lineseparator)
        {
                $lineseparator = "\"+\n\"";
        }
        else
        {
                $lineseparator = "\n";
        }

        return "<table width=100% border=0 bgcolor=#ffffff cellspacing=1 cellpadding=1>$lineseparator".
                   "<tr>$lineseparator".
                   "<td ><span class=TextSmall><font color=green><img src=/images/rightarrow.gif> ".$text."</font></span></td>$lineseparator".
                   "</tr>$lineseparator".
                   "</table>$lineseparator".PrintLineSpace()." $lineseparator";

}

function PrintHeader($name, $is_client, $lineseparator=true)
{
        $oldlineseparator = $lineseparator;

        if ($lineseparator)
        {
                $lineseparator = "\"+\n\"";
        }
        else
        {
                $lineseparator = "\n";
        }

        $header = "<HTML><HEAD><STYLE>$lineseparator".
                                  ".TextButton $lineseparator".
                                  "{ $lineseparator".
                                        "FONT-SIZE: 11px; $lineseparator".
                                        "COLOR: black;  $lineseparator".
                                        "FONT-FAMILY: Tahoma, sans-serif; $lineseparator".
                                        "text-decoration:none;  $lineseparator".
                                   "} $lineseparator".
                                        ".TextButtonGray $lineseparator".
                                        "{ $lineseparator".
                                        "FONT-SIZE: 11px;  $lineseparator".
                                        "COLOR: #888888;  $lineseparator".
                                        "FONT-FAMILY: Tahoma, sans-serif; $lineseparator".
                                        "text-decoration:none;  $lineseparator".
                                        "} $lineseparator".
                                        ".TextSmall  $lineseparator".
                                        "{ $lineseparator".
                                        "FONT-SIZE: 11px;  $lineseparator".
                                        "COLOR: black;  $lineseparator".
                                        "FONT-FAMILY: Verdana, Arial, sans-serif $lineseparator".
                                        "} $lineseparator".
                                        "</STYLE> $lineseparator".
                                        "<SCRIPT LANGUAGE=JavaScript> $lineseparator".
                                        " $lineseparator".
                                        "function ignoreme() $lineseparator".
                                        "{ $lineseparator".
                                        "} $lineseparator".
                                        " $lineseparator".
                                        "</SCRIPT> $lineseparator".
                                        "</HEAD><BODY>";

        if (!empty($name))
        {
                if ($is_client)
                {
                        $header .= PrintSysMessage("Please wait, Establishing Communication...", $oldlineseparator);
                        $header .= PrintLine($name, "Hello, My name is ".urldecode($name)." and I will be your Reader today. How are you doing?", $oldlineseparator);
                }
                else
                {
                        $header .= PrintSysMessage("Please wait, Establishing Communication...", $oldlineseparator);
                        $header .= PrintSysMessage("You are currently chatting with ".urldecode($name), $oldlineseparator);
                }
        }

        return $header;
}

function PrintLine($name, $text, $is_reader, $lineseparator=true)
{
        if ($lineseparator)
        {
                $lineseparator = "\"+\n\"";
        }
        else
        {
                $lineseparator = "\n";
        }

        $text = StripCRLF($text," <BR> ");

        $text = str_replace('"',htmlspecialchars('"'), $text);
        $text = str_replace("'",htmlspecialchars("'"), $text);

        if ($is_reader)
        {
                return "<table  cellspacing=0 cellpadding=1 border=0 bgcolor=#ADAAAD>$lineseparator".
                           "<tr>$lineseparator".
                           "<td>$lineseparator".
                           "<table  border=0 bgcolor=#EFEFEF cellspacing=0 cellpadding=6>$lineseparator".
                           "<tr>$lineseparator".
                           "<td ><span class=TextSmall><font color=blue><b>".htmlspecialchars(ucwords($name)).":</b></font> ".($text)."</span></td>$lineseparator".
                           "</tr>$lineseparator".
                           "</table>$lineseparator".
                           "</td>$lineseparator".
                           "</tr>$lineseparator".
                           "</table>$lineseparator".PrintLineSpace();
        }
        else
        {
                return "<table  border=0 bgcolor=#ffffff cellspacing=0 cellpadding=1>$lineseparator".
                           "<tr>$lineseparator".
                           "<td ><span class=TextSmall><font color=black><b>".htmlspecialchars(ucwords($name)).":</b></font> ".($text)."</span></td>$lineseparator".
                           "</tr>$lineseparator".
                           "</table>$lineseparator".PrintLineSpace();
        }
}


?>
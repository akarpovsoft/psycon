<?php
require_once ("config/config.php");
require_once("email_messages.php");

if (isset($WINDOWS) && isset($DL_EXTENSIONS))
{
dl("php_gd.dll");
}
//'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
$month_name[1]="Jan";
$month_name[2]="Feb";
$month_name[3]="Mar";
$month_name[4]="Apr";
$month_name[5]="May";
$month_name[6]="Jun";
$month_name[7]="Jul";
$month_name[8]="Aug";
$month_name[9]="Sep";
$month_name[10]="Oct";
$month_name[11]="Nov";
$month_name[12]="Dec";
$month_name[all]="All months";
$month_name[year]="Last 12 months";

require_once ("dbpreference.php");
require_once ("dbstorage.php");
require_once ("dbaccount.php");
require_once ("network.php");
require_once ("config/config.php");

global $GD2INSTALLED;

require_once("common_legacy_support.php");

$aff_percent["A-1"] = 5;
$aff_percent["A-2"] = 10;

$ROWS_PER_PAGE                = 20;

$MAX_CHART_FIELDS        = 5;
$MAX_ENUM_RECORDS        = 100;
$MAX_ENUM_FIELDSIZE        = 60;

$MAX_RECORDS_NONINDEX_SEARCH = 100000;

$MAX_LIST_FIELDLENGTH = 100;

$MAX_IMAGE_FILESIZE                                = 500000;

$MAX_MESSAGE_FILESIZE                        = 2000000;

$MAX_NUM_PAGES                                = 100;

// Max width/height
$MAXWIDTH_LOGO                                = 200;
$MAXHEIGHT_LOGO                                = 48;
$MAXWIDTH_MEDIAFILE                        = 118;
$MAXHEIGHT_MEDIAFILE                = 118;
$MAXWIDTH_REPRESENTATIVE        = 118;
$MAXHEIGHT_REPRESENTATIVE        = 118;
$MAXWIDTH_PRODUCTLOGO                = 118;
$MAXHEIGHT_PRODUCTLOGO                = 118;

// Account type
$ACCOUNT_RR                                        = 0;
$ACCOUNT_MLM                                = 1;
$ACCOUNT_DOWNLINE                        = 3;
$ACCOUNT_CHAT                                = 2;
$ACCOUNT_DTF                                = 4;

// Message type
$MESSAGE_EMAIL                                = 1;
$MESSAGE_APP                                = 0;
$MESSAGE_SMS                                = 2;

// Restricted fields (not logged)
$ARRAY_RESTRICTED_FIELDS = array(
'rr_account_id',
'rr_formid',
'rr_missingvaluesasempty',
'rr_record_id_alias',
'_accountid',
'_redirect',
'_send_email',
'rr_redirect1',
'PHPSESSID',
'rr_redirect0',
'rr_recordname',
'rr_preprocess',
'rr_recordfield',
'rr_action',
'rr_params',
'rr_createdate',
'rr_lastaccess',
'rr_redirect1_resultstr',
'rr_redirect0_resultstr',
'rr_redirect0dup_resultstr',
'rr_redirect1del_resultstr',
'rr_tablename',
'_error_url',
'rr_emailsent',
'rr_constraint',
'rr_synchronized',
'submit'
);

if (isset($GLOBALS['HTTP_USER_AGENT']))
{
        if (strstr(strtolower($GLOBALS['HTTP_USER_AGENT']), "msie") != "")
        {
                $IEBROWSER        = true;
        }
        else
        {
                $IEBROWSER        = false;
        }
}
else
{
        $IEBROWSER        = true;
}

if (!$IEBROWSER)
{
        $NS_IMG = "<img src=\"images/transp.gif\">";
}
else
{
        $NS_IMG = "";
}

if (!$IEBROWSER)
{
$TABLE_PADDING                        = "";
}
else
{
$TABLE_PADDING                        = "cellspacing=0 cellpadding=1";
}

$DIR_REPRESENTATIVE                = "/data/representative/";
$DIR_MESSAGE                        = "/data/message/";
$DIR_ACCOUNT                        = "/data/account/";
$DIR_DATA                                = "/data/";
if (empty($RR_HOST)) $RR_HOST                                = "www.relevantreach.com";


$FILE_EMPTY_REPRESENTATIVE                        = $DIR_REPRESENTATIVE."empty.jpg";
$FILE_EMPTY_REPRESENTATIVE_WTEXT        = $DIR_REPRESENTATIVE."empty_wtext.jpg";

$FILE_EMPTY_ACCOUNT                                        = $DIR_ACCOUNT."empty.jpg";
$FILE_EMPTY_ACCOUNT_WTEXT                        = $DIR_ACCOUNT."empty_wtext.jpg";

$FILE_EMPTY_MEDIAFILE                        = "/data/empty_file.jpg";

$SESSION_SIGNUP_ACCOUNTID                = "signup_account_id";
$SESSION_SIGNUP_REPRESENTATIVEID        = "signup_representative_id";
$SESSION_SIGNUP_PRODUCTID                = "signup_product_id";

$PREF_FAVORITE                        = "Favorite";

$PREF_DEFPAGE                        = "DefaultPage";
$PREF_DEFPAGE_HOME                = "DefPageHome";
$PREF_DEFPAGE_JOIN                = "DefPageJoin";
$PREF_DEFPAGE_CHAT                = "DefPageChat";
$PREF_DEFPAGE_CHATMAIN        = "DefPageMain";
$PREF_DEFPAGE_DOWNLINE        = "DefDownline";
$PREF_DEFPAGE_LEADS        = "DefLeads";
$PREF_DEFPAGE_MYWEBSITE        = "DefPageMain";
$PREF_DEFPAGE_SPONSOR        = "DefSponsor";
$PREF_DEFPAGE_RESOURCES = "DefResources";
$PREF_DEFPAGE_COMMUNICATE = "DefCommunicate";
$PREF_DEFPAGE_ADMIN                = "DefPageAdmin";
$PREF_DEFPAGE_REPOSITORY= "DefPageRepository";
$PREF_DEFPAGE_DELIVERY  = "DefPageDelivery";
$PREF_DEFPAGE_REPORTS        = "DefPageReports";
$PREF_DEFPAGE_MESSAGES         = "DefPageMessages";
$PREF_DEFPAGE_READERS        = "DefPageReaders";
$PREF_DEFPAGE_POWERLINE = "DefPagePowerline";

$PREF_SIGNUPS                        = "SignupsChart";
$PREF_GLOBAL                        = "Global";
$PREF_CHATHISTORY                = "ChatHistoryChart";
$PREF_CATEGORIES                = "CategoriesChart";
$PREF_DOWNLINE                        = "DownlineChart";
$PREF_POWERLINE                        = "PowerlineChart";
$PREF_CHAT                                = "ChatChart";
$PREF_LEADS                                = "LeadsChart";
$PREF_ORDERS                        = "OrdersChart";
$PREF_EXTRACT                        = "ExtractChart";
$PREF_ATTACHMENTS                = "AttachmentsChart";
$PREF_SEGMENTS                        = "SegmentsChart";
$PREF_REPOSITORY                = "RepositoryChart";
$PREF_OPERATORS                        = "OperatorsChart";
$PREF_VIEWDATA                        = "ViewDataChart";
$PREF_REPRESENTATIVES        = "RepresentativeChart";
$PREF_PRODUCTS                        = "ProductsChart";
$PREF_CLIENTS                        = "ClientsChart";
$PREF_MESSAGES                        = "MessageChart";
$PREF_HISTORY                        = "HistoryChart";
$PREF_PAYMENTS                        = "PaymentsChart";
$PREF_MEDIA                                = "MediaChart";
$PREF_DPAGES                        = "DPagesChart";
$PREF_DMENUS                        = "DMenusChart";
$PREF_CAMPAIGNS                        = "CampaignsChart";
$PREF_HISTORY                        = "HistoryChart";
$PREF_CAMPAIGNMESSAGES        = "CampaignMessages";
$PREF_START_FROM_PAGE        = "start_from_page";
$PREF_SEGMENTS_ONE                = "SegmentsOneChart";
$PREF_VIEWDATA_ONE                = "ViewDataOneChart";
$PREF_SORT_ASCENDING        = "sort_ascending";
$PREF_SORT_BY_FIELD                = "sort_by_field";
$PREF_HIDELEFT                        = "hide_left";
$PREF_FILTER                        = "filter";
$PREF_FRAMES                        = "Frames";
$PREF_LASTPAGE                        = "LastPage";

// Tables
$PREF_MYMEMBERS                        = "MyMembers";

$STR_CHAT                                = "Chat";
$STR_OVERVIEW                        = "Overview";
$STR_HISTORY                        = "History";
$STR_PAYMENTS                        = "Payments";
$STR_SEND_MESSAGES                = "Communicate";
$STR_CAMPAIGNS                        = "Campaigns";
$STR_AUDIENCES                        = "User Groups";
$STR_CHANNELS                        = "Categories";
$STR_REPRESENTATIVES        = "Representatives";

$STR_DOWNLINE                        = "Downline";
$STR_POWERLINE                        = "Powerline";
$STR_LEADS                        = "Leads";
$STR_ORDERS                        = "Order History";

$STR_MANAGE_CONTENT                = "Manage Content";
$STR_MESSAGES                        = "Messages";
$STR_MEDIA                                = "Graphics Library";
$STR_DYNAMIC_PAGES                = "Dynamic Pages";

$STR_MANAGE_USERS                = "Manage Users";
$STR_MANAGE                                = "Manage";
$STR_LISTS                                = "Database";

$STR_REPORTS                        = "Reports";
$STR_RESPONSE_RATE                = "Response Rate";

$STR_SETTINGS                        = "Admin";
$STR_PROFILE                        = "Profile";
$STR_LIFEFORCE                        = "LifeForce";
$STR_PAYPAL                                = "PayPal";
$STR_NOTIFICATIONS                = "Notifications";
$STR_ACCOUNT                        = "Account Info";
$STR_OPERATORS                        = "Account Managers";
$STR_PRODUCTS                        = "Products";
$STR_DOWNLINE                        = "Downline";

$STR_IGNORETHIS                        = "_ignorethis";
$STR_IGNORETHISFIELD        = "rr_ignorethisvalue";
$STR_FIELDTYPE                        = "_rr_fieldtype";

$STR_DESC_VARCHAR                = "Text (Oneline)";
$STR_DESC_LONGTEXT                = "TextArea (Multiple lines)";
$STR_DESC_INT                        = "Number (123)";
$STR_DESC_DOUBLE                = "Decimal (123.45)";
$STR_DESC_TIMESTAMP                = "Time/Date";
$STR_DESC_ENUM                        = "Enumeration";
$STR_CHARTFIELD                        = "rr_chartfield_";

function _addslashes($Item)
{
//        $Item = addcslashes($Item,"\r");
//        $Item = addcslashes($Item,"\n");

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

function DrawNoRecordsMessage($str)
{
        DrawChartYSpace();

        echo "<tr>";

        DrawChartXSpace();

        echo "<td nowrap colspan=1><SPAN class=TextTiny><img src=images/transp.gif height=17 width=1 align=top>$str</SPAN></td>";
        echo "</tr>";
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

function SafeDIVOpen($id, $align = "left", $style = "")
{
        global $IEBROWSER;

        $IDStr = "";
        if (!empty($id))
        {
                $IDStr = "id=$id";
        }

        if ($IEBROWSER)
        {
                echo "<DIV $IDStr align=\"$align\" style=\"$style\">";
        }
        else
        {
                echo "<ILAYER id=ilayer width=100%>";
                echo "<LAYER $IDStr width=100%>";
        }
}

function SafeDIVClose()
{
        global $IEBROWSER;

        if ($IEBROWSER)
        {
                echo "</DIV>";
        }
        else
        {
                echo "</LAYER>";
                echo "</ILAYER>";
        }
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
  $Months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

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

function DrawArrowLink($Name, $URL, $Icon="")
{
        global $PageName;
        if ($PageName)
        {
                $Self = Strcasecmp($PageName, str_replace("<BR>", " ", $Name))==0;
        }
        else
        {
                $Self = false;
        }

        if ($Self && $Icon)
        {
        ?>
<table border=0 cellspacing=0 cellpadding=0 align=center width="80%"><tr><td><table border=0 cellspacing=0 cellpadding=0 width="100%" >
<tr height=6>
<td nowrap width=5 height=6 background="images/button_topleft.gif"><img src="images/transp.gif"></td>
<td nowrap width="100%" height=6 background="images/button_top.gif"><img src="images/transp.gif"></td>
<td nowrap width=5 height=6 background="images/button_topright.gif"><img src="images/transp.gif"></td>
</tr>
<tr>
<td nowrap width=5  background="images/button_left.gif"><img src="images/transp.gif"></td>
<td nowrap width="100%" background="images/button_bg.gif"  ><table border=0 cellspacing=0 cellpadding=0 align=center >
<tr>
<td nowrap >
        <?php
        }

        echo "<table border=0 cellspacing=0 width=\"100%\" cellpadding=0  ";
        echo "><tr>";

        // Set span styles
        if ($Icon == "")
        {
                echo "<td align=left width=\"100%\">";
                $TextSpan = "TextSmall";
        }
        else
        {
                echo "<td align=center width=\"100%\">";
                $TextSpan = "TextButton";
        }

        // Draw

        if (!$Self)
        {
                echo "<a class=LinkButton href=\"$URL\">";
        }

        if ($Icon == "")
        {
        if ($Self)
        {
                ImgTag("", "index_files/arrow.gif", 0, "align=absmiddle");
                echo "";
        }
        else
        {
                ImgTag("", "index_files/arrowgray.gif", 0, "align=absmiddle");
                echo "";
        }
        }
        else
        {
                echo "";
                ImgTag("", $Icon, 0, "valign=middle");
                echo "";
        }

        if (!$Self)
        {
                echo "</a>";
        }

        if ($Icon != "")
        {
                echo "</td></tr><tr><td nowrap width=\"100%\" align=center><SPAN class=$TextSpan>";
        }

        if ($Self)
        {
                echo "<b><SPAN class=$TextSpan>";
                echo $Name;
                echo "</SPAN></b>";
        }
        else
        {
                echo "<a class=TextButton href=\"$URL\">";

                echo "";
                echo $Name;
                echo "</a>";
                echo "</SPAN>";
        }

        echo "</td></tr></table>";


        if ($Self && $Icon)
        {
        ?>
</td>
</tr>
</table></td>
<td nowrap width=5  background="images/button_right.gif"><img src="images/transp.gif"></td>
</tr>

<tr height=5>
<td nowrap width=5 height=5 background="images/button_bottomleft.gif"><img src="images/transp.gif"></td>
<td nowrap width="100%" background="images/button_bottom.gif"><img src="images/transp.gif"></td>
<td nowrap width=5 height=5 background="images/button_bottomright.gif"><img src="images/transp.gif"></td>
</tr>
</table></td><td width=4><img src="images/transp.gif"></td></tr></table>
        <?php
        }

        if ($Icon != "")
        {
                echo "<br>";
        }

}

function IDrawFoldersChat(&$Folders, &$FoldersIn)
{
        global        $PREF_DEFPAGE, $PREF_DEFPAGE_HOME, $PageName;
        global        $PREF_DEFPAGE_REPOSITORY, $PREF_DEFPAGE_ADMIN, $PREF_DEFPAGE_CHAT, $PREF_DEFPAGE_REPORTS, $PREF_DEFPAGE_MESSAGES;
        global        $LoginRequired, $CurrentFolder, $login_account_id, $login_operator_id, $INTERNAL_PAGE, $INSIDE_FRAME;
        global        $MAXWIDTH_LOGO, $MAXHEIGHT_LOGO, $DOCUMENT_ROOT, $FILE_EMPTY_ACCOUNT, $PREF_DEFPAGE_CHATMAIN, $PREF_DEFPAGE_READERS;
global $http_addr;
        $defpageMain                = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_CHATMAIN, "chatmain.php");
        $defpageReaders                = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_READERS, "chatourreaders.php");
        $defpageChat                = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_CHAT, "chat.php");
        $defpageAdmin                = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_ADMIN, "chatclients.php");

        $Folders        = array(
                                  "My Account"        => array("url" => "$http_addr/$defpageMain",                  "img" => "_my_account.gif"),
                                  "Chat"                => array("url" => "$http_addr/$defpageChat",                  "img" => "_chats.gif"));

        $operator = array();

        if (!empty($login_operator_id) && !empty($login_account_id))
        {
                $operator = storageGetFormRecord($login_account_id, "Operators", $login_operator_id);

                if (Strcasecmp(arrayvalue($operator,'type'),'reader')!=0)
                {
                $Folders = array_merge($Folders, array(
                        "Our Readers" => array("url" => "$http_addr/$defpageReaders", "img" => "_ourreaders.gif")));
                }

                if (Strcasecmp(arrayvalue($operator,'type'),'administrator')==0)
                {
                $Folders = array_merge($Folders, array(
                        "Manage" => array("url" => $defpageAdmin, "img" => "_manage.gif")));
                }
        }

        if (!session_is_registered("login_account_id"))
        {
        $Folders        = array(
                                  "Home"                                => array("url" => "$http_addr/chatwelcome.php",                  "img" => "_welcome.gif"),
                                  "Our Readers"                => array("url" => "$http_addr/chatourreaders_guest.php?account_id=1",                "img" => "_ourreaders.gif"));
        }

        if (Strcasecmp(arrayvalue($operator,'type'),'reader')==0)
        {
        $FoldersIn        = array(
                                        "My Account" =>
                                                array("Overview"                => array("url" => "chatmain.php",    "img" => "_overview.gif"),
                                                          "Withdraw"                => array("url" => "withdraw.php",          "img" => "_withdraw.gif"),
                                                          "History"                        => array("url" => "dtfhistory.php",  "img" => "_history.gif"),
                                                          "Profile"                        => array("url" => "chatprofile.php", "img" => "_profile.gif")),
                                        "Chat" =>
                                                array("History"        => array("url" => "chat.php",    "img" => "_history.gif")));
        }
        else
        {
        $FoldersIn        = array(
                                        "My Account" =>
                                                array("Overview"                => array("url" => "$http_addr/chatmain.php",    "img" => "_overview.gif"),
                                                          "Add Funds"                => array("url" => "$http_addr/chataddfunds.php",          "img" => "_add_funds.gif"),
                                                          "History"                        => array("url" => "$http_addr/dtfhistory.php",  "img" => "_history.gif"),
                                                          "Profile"                        => array("url" => "$http_addr/chatprofile.php", "img" => "_profile.gif")),
//                                                          "Withdraw"                => array("url" => "$http_addr/withdraw.php",          "img" => "_withdraw.gif")),
                                        "Chat" =>
                                                array("Start Chat"      => array("url" => "chatstart.php", "img" => "_start.gif"),
                                                      "History"        => array("url" => "chat.php",    "img" => "_history.gif")),
                                        "Our Readers" =>
                                            array("Overview" => array("url" => "chatourreaders.php", "img" => "_overview.gif")),
                                        "Manage" =>
                                                array("Clients"                => array("url" => "chatclients.php", "img" => "_clients.gif"),
                                                      "Readers"                => array("url" => "chatreaders.php", "img" => "_readers.gif"),
                                                          "Account Managers"        => array("url" => "chatoperators.php",    "img" => "_operators.gif"),
                                                          "Payments"                                        => array("url" => "payments.php", "img" => "_payments.gif"),
                                                          "Order History"        => array("url" => "dtforders.php", "img" => "_orderhistory.gif")));
        }
}

function IDrawFoldersRR(&$Folders, &$FoldersIn)
{
        global        $PREF_DEFPAGE, $PREF_DEFPAGE_HOME, $PageName, $STR_SEND_MESSAGES;
        global        $PREF_DEFPAGE_REPOSITORY, $PREF_DEFPAGE_ADMIN, $PREF_DEFPAGE_DELIVERY, $PREF_DEFPAGE_REPORTS, $PREF_DEFPAGE_MESSAGES;
        global        $LoginRequired, $CurrentFolder, $login_account_id, $login_operator_id, $INTERNAL_PAGE, $INSIDE_FRAME;
        global        $MAXWIDTH_LOGO, $MAXHEIGHT_LOGO, $DOCUMENT_ROOT, $FILE_EMPTY_ACCOUNT;

        $defpageHome                = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_HOME, "home.php");
        $defpageDelivery        = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_DELIVERY, "campaigns.php");
        $defpageReports                = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_REPORTS, "responserate.php");
        $defpageRepository        = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_REPOSITORY, "viewdata.php");
        $defpageAdmin                = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_ADMIN, "operators_one.php?mysettings=true");
        $defpageMessages        = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_MESSAGES, "messages.php");

        $Folders        = array(
                                  "Statistics"                                => array("url" => $defpageReports,          "img" => "_statistics.gif"),
                                  "Manage Content"                        => array("url" => $defpageMessages,   "img" => "_manage_content.gif"),
                                  "Manage Users"                        => array("url" => "viewdata.php", "img" => "_manage_users.gif"),
                                  $STR_SEND_MESSAGES        => array("url" => $defpageDelivery,   "img" => "_communicate.gif"),
                                  "Admin"                                        => array("url" => $defpageAdmin,          "img" => "_settings.gif"));

        $FoldersIn        = array(
                                        "Statistics" =>
                                                array("Response Rate" => array("url" => "responserate.php","img" => "_responserate.gif"),
                                                "User Groups"                => array("url" => "segments.php",     "img" => "_audiences.gif")),
                                        $STR_SEND_MESSAGES =>
                                                array("Campaigns"        => array("url" => "campaigns.php",    "img" => "_campaigns.gif"),
                                                      "History"     => array("url" => "history.php",      "img" => "_history.gif")),
                                        "Manage Content" =>
                                                array("Messages"        => array("url" => "messages.php",     "img" => "_messages.gif"),
                                                "Categories"                => array("url" => "categories.php","img" => "_channels.gif"),
                                                "Representatives"        => array("url" => "representatives.php","img" => "_representatives.gif"),
                                                "Dynamic Pages"                => array("url" => "dpages.php",       "img" => "_dynamic_pages.gif"),
                                                "Graphics Library"        => array("url" => "media.php",        "img" => "_graphics.gif")),
                                        "Manage Users" =>
                                                array(
                                                "Database"                => array("url" => "viewdata.php",     "img" => "_database.gif"),
                                                "User Information" => array("url" => "products_extract.php", "img" => "_lists.gif")),
                                        "Admin" =>
                                                array("Profile"                => array("url" => "operators_one.php?mysettings=true", "img" => "_profile.gif"),
                                                "Account Info"                => array("url" => "admin.php",        "img" => "_account.gif"),
                                                "Account Managers"        => array("url" => "operators.php",    "img" => "_operators.gif"),
                                                "Products"                        => array("url" => "products.php",     "img" => "_products.gif")));
}

function GetAccountTypeByServerName()
{
        global        $SERVER_NAME, $ACCOUNT_MLM, $ACCOUNT_RR, $ACCOUNT_DOWNLINE, $ACCOUNT_CHAT, $ACCOUNT_DTF;

        if (_strpos(strtolower($SERVER_NAME),"downlinesystem")!=-1)
        {
                return $ACCOUNT_DOWNLINE;
        }

        if (_strpos(strtolower($SERVER_NAME),"richandhealthy")!=-1)
        {
                return $ACCOUNT_MLM;
        }

        if (_strpos(strtolower($SERVER_NAME),"psychicdoor")!=-1)
        {
                return $ACCOUNT_CHAT;
        }

        if (_strpos(strtolower($SERVER_NAME),"psychic-contact")!=-1)
        {
                return $ACCOUNT_CHAT;
        }

        if (_strpos(strtolower($SERVER_NAME),"debttofreedom")!=-1)
        {
                return $ACCOUNT_DTF;
        }

        return $ACCOUNT_RR;
}

function GetFullServerName()
{
        global        $SERVER_NAME;

        $result = $SERVER_NAME;

        if (Strcasecmp(substr($result,0,4),"www.")==0)
        {
                $result = substr($result,4);
        }

        if (strlen($result)>4)
        if (_strpos(substr($result,strlen($result)-4),".com")==-1)
        {
                if (_strpos(strtolower($result),"richandhealthy")!=-1)
                {
                $result .= ".net";
                }
                else
                {
                $result .= ".com";
                }
        }

        return $result;
}

function GetSupportEmail()
{
        global        $ACCOUNT_TYPE, $ACCOUNT_CHAT;

        switch ($ACCOUNT_TYPE)
        {
                case        $ACCOUNT_CHAT:
                        return "javachat@psychic-contact.com";
                break;
        }

        return "support@".GetFullServerName();
}

function GetSystemHeaderWidth()
{
        global        $ACCOUNT_TYPE, $ACCOUNT_MLM, $ACCOUNT_DOWNLINE, $ACCOUNT_CHAT, $ACCOUNT_DTF, $ACCOUNT_RR;

        switch ($ACCOUNT_TYPE)
        {
                case $ACCOUNT_DTF:
                        return "100%";
                break;

                default:
                        return "600";
                break;
        }
}

function GetSystemHeader()
{
        global        $ACCOUNT_TYPE, $ACCOUNT_MLM, $ACCOUNT_DOWNLINE, $ACCOUNT_CHAT, $ACCOUNT_DTF, $ACCOUNT_RR, $http_addr;

        // Setup folder structure
        switch ($ACCOUNT_TYPE)
        {
                case $ACCOUNT_DOWNLINE:
                case $ACCOUNT_MLM:
                case $ACCOUNT_CHAT:
                case $ACCOUNT_RR:
                        return " <img src=\"$http_addr/images/top_logo.jpg\" border=\"0\" width=\"468\" height=\"60\">";
                break;

                case $ACCOUNT_DTF:
                        return '<TABLE cellspacing=0 cellpadding=0 border=0 width=100% height="100%">
  <TR>
    <TD width=217 ><IMG src=/debttofreedom/images/ppl.jpg width=217 height=206></TD>
    <TD  valign=top>
      <TABLE cellspacing=0 cellpadding=0 border=0 width=100%>
        <TR>
          <TD background=/debttofreedom/images/top_back.gif align=right height=29> <FONT color=#045182 size=2 face=arial class="txt">Members
            Area&nbsp;</FONT></TD>
        </TR>
        <TR>
          <TD background=/debttofreedom/images/title_back.gif><IMG src=/debttofreedom/images/debt_title.jpg height=61 width=551></TD>
        </TR>
        <TR>
          <TD background=/debttofreedom/images/slogan_back.gif>
            <TABLE cellspacing=0 cellpadding=0 border=0 width=100%>
              <TR>
                <TD width=551><IMG src=/debttofreedom/images/slogan.jpg height=85 width=551></TD>

              </TR>
            </TABLE>
          </TD>
        </TR>
        <TR>
          <TD valign=top background=/debttofreedom/images/menu_back.gif height=31 ></TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
</table></td></tr></table>';//<center><img src=images/dtfmain.png width=778 height=200></center>";
                break;

                default:
                        return "Relevant Reach";
                break;
        }
}

function GetSystemName()
{
        global        $ACCOUNT_TYPE, $ACCOUNT_MLM, $ACCOUNT_DOWNLINE, $ACCOUNT_CHAT, $ACCOUNT_DTF;

        // Setup folder structure
        switch ($ACCOUNT_TYPE)
        {
                case $ACCOUNT_DOWNLINE:
                case $ACCOUNT_MLM:
                        return "Downline System";
                break;

                case $ACCOUNT_CHAT:
                        return "Psychic Contact";
                break;

                case $ACCOUNT_DTF:
                        return "DebtToFreedom";
                break;

                default:
                        return "Relevant Reach";
                break;
        }
}

function GetCampaignsPage()
{
        global        $ACCOUNT_TYPE, $ACCOUNT_MLM, $ACCOUNT_DOWNLINE, $ACCOUNT_CHAT, $ACCOUNT_DTF;

        // Setup folder structure
        switch ($ACCOUNT_TYPE)
        {
                case $ACCOUNT_DOWNLINE:
                case $ACCOUNT_MLM:
                        return "mlmcampaigns.php";
                break;

                case $ACCOUNT_DTF:
                        return "mlmcampaigns.php";
                break;

                default:
                        return "campaigns.php";
                break;
        }
}

function GetUnsubscribeLink($acount_id,$id, $notify)
{
        return GetFullServerName()."/unsubscribe.php?account_id=$account_id&operator_id=$id&remove=".urlencode($notify);
}

function GetSystemLoginPage()
{
        global        $ACCOUNT_TYPE, $ACCOUNT_DOWNLINE, $ACCOUNT_MLM, $ACCOUNT_CHAT, $ACCOUNT_DTF;

        // Setup folder structure
        switch ($ACCOUNT_TYPE)
        {
                case $ACCOUNT_DOWNLINE:
                case $ACCOUNT_MLM:
                        return "/mlmlogin.php";
                break;

                case $ACCOUNT_CHAT:
                        return "chatlogin.php";
                break;

                case $ACCOUNT_DTF:
                        return "/dtflogin.php";
                break;

                default:
                        return "/rrlogin.php";
                break;
        }
}

function GetSystemLogoutPage()
{
        global        $ACCOUNT_TYPE, $ACCOUNT_MLM, $ACCOUNT_CHAT, $ACCOUNT_DOWNLINE, $ACCOUNT_DTF;
        global        $LOGOUT_PAGE;

        if (!empty($LOGOUT_PAGE))
        {
                return $LOGOUT_PAGE;
        }

        if (empty($ACCOUNT_TYPE))
        {
                $ACCOUNT_TYPE = GetAccountTypeByServerName();
        }

        // Setup folder structure
        switch ($ACCOUNT_TYPE)
        {
                case $ACCOUNT_DOWNLINE:
                case $ACCOUNT_MLM:
                        return "/mlmlogout.php";
                break;

                case $ACCOUNT_CHAT:
                        return "chatlogout.php";
                break;

                case $ACCOUNT_DTF:
                        return "/dtflogout.php";
                break;

                default:
                        return "/rrlogout.php";
                break;
        }
}

function GetSystemBalancePopupWin()
{
        global        $ACCOUNT_TYPE, $ACCOUNT_DOWNLINE, $ACCOUNT_MLM, $ACCOUNT_CHAT, $ACCOUNT_DTF;

        $str = GetSystemBalancePage();

        // Setup folder structure
        switch ($ACCOUNT_TYPE)
        {
                case $ACCOUNT_DOWNLINE:
                case $ACCOUNT_MLM:
                        return "Javascript:popUpErrorWin('$str');";
                break;

                case $ACCOUNT_DTF:
                case $ACCOUNT_CHAT:
                        return "Javascript:popUpWin('$str',300,150);";
                break;

                default:
                        return "Javascript:popUpErrorWin('$str');";
                break;
        }
}

function GetSystemBalancePage()
{
        global        $ACCOUNT_TYPE, $ACCOUNT_DOWNLINE, $ACCOUNT_MLM, $ACCOUNT_CHAT, $ACCOUNT_DTF;

        // Setup folder structure
        switch ($ACCOUNT_TYPE)
        {
                case $ACCOUNT_DOWNLINE:
                case $ACCOUNT_MLM:
                        return "balance.html";
                break;

                case $ACCOUNT_CHAT:
                        return "chatbalance.html";
                break;

                case $ACCOUNT_DTF:
                        return "dtfbalance.html";
                break;

                default:
                        return "balance.html";
                break;
        }
}

function GetSystemFirstPage()
{
        global        $ACCOUNT_TYPE, $ACCOUNT_DOWNLINE, $ACCOUNT_MLM, $ACCOUNT_CHAT, $ACCOUNT_DTF;

        // Setup folder structure
        switch ($ACCOUNT_TYPE)
        {
                case $ACCOUNT_DOWNLINE:
                case $ACCOUNT_MLM:
                        return "mywebsite.php";
                break;

                case $ACCOUNT_DTF:
                        return "dtfmain.php";
                break;

                case $ACCOUNT_CHAT:
                        return "chatmain.php";
                break;

                default:
                        return "campaigns.php";
                break;
        }
}

function IDrawFoldersMLM(&$Folders, &$FoldersIn)
{
        global        $PREF_DEFPAGE, $PREF_DEFPAGE_HOME, $PageName;
        global        $PREF_DEFPAGE_REPOSITORY, $PREF_DEFPAGE_ADMIN, $PREF_DEFPAGE_DELIVERY, $PREF_DEFPAGE_REPORTS, $PREF_DEFPAGE_MESSAGES;
        global        $PREF_DEFPAGE_DOWNLINE, $PREF_DEFPAGE_MYWEBSITE, $PREF_DEFPAGE_SPONSOR, $PREF_DEFPAGE_COMMUNICATE, $PREF_DEFPAGE_LEADS;
        global        $LoginRequired, $CurrentFolder, $login_account_id, $login_operator_id, $INTERNAL_PAGE, $INSIDE_FRAME;
        global        $MAXWIDTH_LOGO, $MAXHEIGHT_LOGO, $DOCUMENT_ROOT, $FILE_EMPTY_ACCOUNT;

        $defpageMyWebsite        = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_MYWEBSITE, "mywebsite.php");
        $defpageReports                = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_REPORTS, "comingsoon.php");
        $defpageSponsor                = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_SPONSOR, "sponsor.php");
        $defpageDownline        = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_DOWNLINE, "downline.php");
        $defpageLeads                = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_LEADS, "leads.php");
        $defpageAdmin                = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_ADMIN, "operators_one.php?mysettings=true");
        $defpageCommunicate        = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_COMMUNICATE, "mlmmessages.php");
        $defpageJoin                = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_JOIN, "joinnow.php");
        $defpagePowerline        = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_POWERLINE, "powerline.php");

        $Folders        = array("My Account"        => array("url" => $defpageMyWebsite,  "img" => "_my_account.gif"));//,
//                                  "Downline"                        => array("url" => $defpageDownline,   "img" => "_downline.gif"),
//                                  "Join Now"                        => array("url" => $defpageJoin,                  "img" => "_joinnow.gif"));
//                                "Communicate" => array("url" => $defpageCommunicate, "img" => "_communicate.gif")
//                                "Sponsor" => array("url" => $defpageSponsor, "img" => "_sponsor.gif"));

        if (!empty($login_operator_id) && !empty($login_account_id))
        {
                $operator = storageGetFormRecord($login_account_id, "Operators", $login_operator_id);

                if (!empty($operator['lastordertime']) && Strcasecmp(arrayvalue($operator,'lastordertime'),'00000000000000')!=0)//IsPaid($operator['lastordertime']))
                {
                $Folders = array_merge($Folders, array(
                        "Powerline" => array("url" => $defpagePowerline, "img" => "_powerline.gif")));
                $Folders        = array_merge($Folders, array(
                                  "Communicate"                        => array("url" => $defpageCommunicate, "img" => "_communicate.gif"),
                                  "Admin"                                => array("url" => $defpageAdmin,          "img" => "_settings.gif")));

                }
                else
                {
                $Folders = array_merge($Folders, array(
                        "Powerline Explained" => array("url" => "powerlineexplained.php", "img" => "_powerlineexplained.gif")));
                }

                if (Strcasecmp(arrayvalue($operator,'type'),'administrator')==0)
                {
                $Folders = array_merge($Folders, array(
                        "Manage" => array("url" => $defpageLeads, "img" => "_manage.gif")));
                }
        }


        if (!empty($operator) &&
            !empty($operator['lastordertime']) && Strcasecmp(arrayvalue($operator,'lastordertime'),'00000000000000')!=0)//IsPaid($operator['lastordertime']))
        {
        $FoldersIn        = array(
                                        "My Account" =>
                                                array("www.richandhealthy.net"                => array("url" => "mywebsite.php",    "img" => "_richandhealthy.gif"),
//                                                          "Add Funds"                                        => array("url" => "addfunds.php",          "img" => "_add_funds.gif"),
                                                          "Buy Leads"                                        => array("url" => "buyleads.php",          "img" => "_buyleads.gif"),
//                                                          "Withdraw"                                        => array("url" => "withdraw.php",          "img" => "_withdraw.gif"),
                                                          "My Orders"                                        => array("url" => "myorders.php",     "img" => "_myorders.gif"),
                                                          "History"                                                => array("url" => "dtfhistory.php",                  "img" => "_history.gif")),
                                        "Downline" =>
                                                array("Downline"                        => array("url" => "downline.php",     "img" => "_members.gif")),
                                        "Powerline" =>
                                            array("Powerline"                    => array("url" => "powerline.php",    "img" => "_overview.gif")),
//                                        "Sponsor" =>
//                                                array("User Information"                                => array("url" => "sponsor.php",      "img" => "_lists.gif")),
                                        "Communicate" =>
                                                array("Campaigns"        => array("url" => "mlmcampaigns.php",    "img" => "_campaigns.gif"),
                                                          "Messages"                                            => array("url" => "mlmmessages.php",  "img" => "_messages.gif"),
                                                          "History"                                => array("url" => "history.php",      "img" => "_history.gif"),
                                                          "User Groups"                        => array("url" => "mlmusers.php",     "img" => "_audiences.gif")),
//                                        "Reports" =>
//                                                array("Commissions"        => array("url" => "comingsoon.php","img" => "_commisions.gif")),
                                        "Manage" =>
                                                array("My Members"                => array("url" => "mymembers.php","img" => "_members.gif"),
                                                          "Leads"                        => array("url" => "leads.php","img" => "_leads.gif"),
                                                      "Order History"        => array("url" => "orders.php", "img" => "_orderhistory.gif"),
                                                          "Signups"                        => array("url" => "signups.php", "img" => "_signups.gif")),
//                                                          "Send Broadcast"        => array("url" => "sendbroadcast.php", "img" => "_sendbroadcast.gif")),
//                                                          "Payments"                => array("url" => "payments.php", "img" => "_payments.gif")),
                                        "Admin" =>
                                                array("Profile"                => array("url" => "operators_one.php?mysettings=true", "img" => "_profile.gif"),
                                                          "LifeForce"        => array("url" => "lifeforce.php", "img" => "_lifeforce.gif"),
//                                                          "PayPal"                => array("url" => "setpaypal.php", "img" => "_paypal.gif"),
                                                          "Notifications"=> array("url" => "notifications.php", "img" => "_notifications.gif")));
        }
        else
        {
        $FoldersIn        = array(
                                        "My Account" =>
                                                array("www.richandhealthy.net"                => array("url" => "mywebsite.php",    "img" => "_richandhealthy.gif")),
                                        "Downline" =>
                                                array("Downline"                        => array("url" => "downline.php",     "img" => "_members.gif")),
                                        "Powerline Explained" =>
                                                array("Overview" => array("url" => "powerlineexplained.php", "img" => "_overview.gif")),
                                        "Powerline" =>
                                            array("Powerline"                    => array("url" => "powerline.php",    "img" => "_overview.gif")),
//                                        "Sponsor" =>
//                                                array("User Information"                                => array("url" => "sponsor.php",      "img" => "_lists.gif")),
                                        "Communicate" =>
                                                array("Campaigns"        => array("url" => "mlmcampaigns.php",    "img" => "_campaigns.gif"),
                                                      "Leads"                                                => array("url" => "downline.php?leads=true", "img" => "_leads.gif"),
                                                          "Messages"                                            => array("url" => "mlmmessages.php",  "img" => "_messages.gif"),
                                                          "User Groups"                        => array("url" => "mlmusers.php",     "img" => "_audiences.gif")),
//                                        "Reports" =>
//                                                array("Commissions"        => array("url" => "comingsoon.php","img" => "_commisions.gif")),
                                        "Manage" =>
                                                array("My Members"                => array("url" => "mymembers.php","img" => "_members.gif"),
                                                          "Leads"                        => array("url" => "leads.php","img" => "_leads.gif"),
                                                      "Order History"        => array("url" => "orders.php", "img" => "_orderhistory.gif"),
                                                          "Signups"                        => array("url" => "signups.php", "img" => "_signups.gif")),
//                                                          "Send Broadcast"        => array("url" => "sendbroadcast.php", "img" => "_sendbroadcast.gif")),
//                                                          "Payments"                => array("url" => "payments.php", "img" => "_payments.gif")),
                                        "Admin" =>
                                                array("Profile"                => array("url" => "operators_one.php?mysettings=true", "img" => "_profile.gif"),
                                                          "LifeForce"        => array("url" => "lifeforce.php", "img" => "_lifeforce.gif"),
//                                                          "PayPal"                => array("url" => "setpaypal.php", "img" => "_paypal.gif"),
                                                          "Notifications"=> array("url" => "notifications.php", "img" => "_notifications.gif")));
        }

}

function IDrawFoldersDTF(&$Folders, &$FoldersIn)
{
        global        $PREF_DEFPAGE, $PREF_DEFPAGE_HOME, $PageName;
        global        $PREF_DEFPAGE_REPOSITORY, $PREF_DEFPAGE_ADMIN, $PREF_DEFPAGE_DELIVERY, $PREF_DEFPAGE_REPORTS, $PREF_DEFPAGE_MESSAGES;
        global        $PREF_DEFPAGE_DOWNLINE, $PREF_DEFPAGE_MYWEBSITE, $PREF_DEFPAGE_SPONSOR, $PREF_DEFPAGE_RESOURCES, $PREF_DEFPAGE_COMMUNICATE;
        global        $LoginRequired, $CurrentFolder, $login_account_id, $login_operator_id, $INTERNAL_PAGE, $INSIDE_FRAME, $PREF_DEFPAGE_POWERLINE;
        global        $MAXWIDTH_LOGO, $MAXHEIGHT_LOGO, $DOCUMENT_ROOT, $FILE_EMPTY_ACCOUNT, $PREF_DEFPAGE_LEADS;

        $defpageMyWebsite        = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_MYWEBSITE, "dtfmain.php");
        $defpageResources        = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_RESOURCES, "dtftraining.php");
        $defpageLeads                = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_LEADS, "leads.php");
        $defpageDownline        = "dtfdownline.php";//preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_DOWNLINE, "dtfdownline.php");
        $defpageAdmin                = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_ADMIN, "operators_one.php?mysettings=true");
        $defpageCommunicate        = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_COMMUNICATE, "mlmmessages.php");
        $defpagePowerline        = preferenceDeclare($PREF_DEFPAGE, $PREF_DEFPAGE_POWERLINE, "powerline.php");

        $Folders        = array("My Account"        => array("url" => $defpageMyWebsite,  "img" => "_my_account.gif"),
                                  "Resources" => array("url" => $defpageResources, "img" => "_resources.gif"),
                                  "Downline"                        => array("url" => $defpageDownline,   "img" => "_downline.gif"),
                                  "Powerline" => array("url" => $defpagePowerline, "img" => "_powerline.gif"),
                                "Communicate" => array("url" => $defpageCommunicate, "img" => "_communicate.gif"));

        if (!empty($login_operator_id) && !empty($login_account_id))
        {
                $operator = storageGetFormRecord($login_account_id, "Operators", $login_operator_id);

                if (Strcasecmp(arrayvalue($operator,'type'),'administrator')==0)
                {
                $Folders = array_merge($Folders, array(
                        "Manage" => array("url" => $defpageLeads, "img" => "_manage.gif")));
                }
        }

        $Folders        = array_merge($Folders, array(
                                  "Admin"                                => array("url" => $defpageAdmin,          "img" => "_settings.gif")));

        $FoldersIn        = array(
                                        "My Account" =>
                                                array("Overview"                                        => array("url" => "dtfmain.php",    "img" => "_overview.gif"),
                                                          "Add Funds"                                        => array("url" => "dtfaddfunds.php",          "img" => "_add_funds.gif"),
//                                                          "Buy Leads"                                        => array("url" => "dtfbuyleads.php",          "img" => "_buyleads.gif"),
                                                          "History"                                                => array("url" => "dtfhistory.php",                  "img" => "_history.gif"),
                                                          "Withdraw"                                        => array("url" => "dtfwithdraw.php",          "img" => "_withdraw.gif"),
                                                          "My Orders"                                        => array("url" => "myorders.php",     "img" => "_myorders.gif"),
                                                          "My Membership"                                => array("url" => "dtfmembership.php",          "img" => "_membership.gif")),
                                        "Downline" =>
                                                array("Overview"                                        => array("url" => "dtfdownline.php",     "img" => "_overview.gif"),
                                                      "Sponsor"                                                => array("url" => "sponsor.php",      "img" => "_sponsor.gif")),
                                        "Powerline" =>
                                            array("Powerline"                    => array("url" => "powerline.php",    "img" => "_overview.gif"),
                                                      "Leg 1"                                => array("url" => "powerline1.php",   "img" => "_leg1.gif"),
                                                          "Leg 2"                                => array("url" => "powerline2.php",   "img" => "_leg2.gif"),
                                                          "Leg 3"                                => array("url" => "powerline3.php",   "img" => "_leg3.gif"),
                                                          "Leg 4"                                => array("url" => "powerline4.php",   "img" => "_leg4.gif"),
                                                          "Leg 5"                                => array("url" => "powerline5.php",   "img" => "_leg5.gif")),
                                        "Communicate" =>
                                                array("Marketing"                                        => array("url" => "dtfmarketing.php",    "img" => "_marketing.gif"),
                                                      "Campaigns"                                        => array("url" => "mlmcampaigns.php",    "img" => "_campaigns.gif"),
                                                      "Messages"                                            => array("url" => "mlmmessages.php",  "img" => "_messages.gif"),
//                                                      "Leads"                                                        => array("url" => "myleads.php", "img" => "_leads.gif"),
                                                          "History"                                => array("url" => "history.php",      "img" => "_history.gif"),
                                                          "User Groups"                                        => array("url" => "mlmusers.php",     "img" => "_audiences.gif")),
                                        "Manage" =>
                                                array("My Members"                                        => array("url" => "dtfmymembers.php","img" => "_members.gif"),
                                                          "Leads"                                                => array("url" => "leads.php","img" => "_leads.gif"),
                                                          "Order History"                                => array("url" => "dtforders.php", "img" => "_orderhistory.gif"),
                                                          "Leads Orders"                                => array("url" => "orders.php", "img" => "_leadsorders.gif"),
                                                          "Trainers"                                        => array("url" => "trainers.php", "img" => "_trainers.gif"),
//                                                          "Send Broadcast"                                => array("url" => "sendbroadcast.php", "img" => "_sendbroadcast.gif"),
                                                          "Payments"                                        => array("url" => "payments.php", "img" => "_payments.gif")),
                                        "Admin" =>
                                                array("Profile"                => array("url" => "operators_one.php?mysettings=true", "img" => "_profile.gif"),
                                                          "Notifications"=> array("url" => "notifications.php", "img" => "_notifications.gif")),
                                        "Resources" =>
                                                array("QuickStart"                                        => array("url" => "dtfquickstart.php", "img" => "_quickstart.gif"),
                                                          "Training"                                        => array("url" => "dtftraining.php", "img" => "_training.gif"),
                                                          "FAQ"                                                        => array("url" => "dtffaq.php", "img" => "_faq.gif"),
                                                          "Contributions"                                => array("url" => "dtfcontributions.php", "img" => "_contributions.gif"),
                                                          "Compensation"                                => array("url" => "dtfcompensation.php", "img" => "_compensation.gif")));
}

function DrawFolders($ActiveFolder, $draw_inner = true)
{
        global        $PREF_DEFPAGE, $PREF_DEFPAGE_HOME, $PageName;
        global        $PREF_DEFPAGE_REPOSITORY, $PREF_DEFPAGE_ADMIN, $PREF_DEFPAGE_DELIVERY, $PREF_DEFPAGE_REPORTS, $PREF_DEFPAGE_MESSAGES;
        global        $LoginRequired, $CurrentFolder, $login_account_id, $login_operator_id, $INTERNAL_PAGE, $INSIDE_FRAME;
        global        $MAXWIDTH_LOGO, $MAXHEIGHT_LOGO, $DOCUMENT_ROOT, $FILE_EMPTY_ACCOUNT, $ACCOUNT_TYPE, $ACCOUNT_MLM, $ACCOUNT_DOWNLINE, $ACCOUNT_CHAT, $ACCOUNT_DTF;
        global        $DREAMTIME_ACCOUNT;

        if (isset($INTERNAL_PAGE))
        {
                return;
        }

        // Store
        $CurrentFolder = $ActiveFolder;

        $helpstr = "";

        $target = "target=_top";

        // Setup folder structure
        switch ($ACCOUNT_TYPE)
        {
                case $ACCOUNT_DOWNLINE:
                case $ACCOUNT_MLM:
                        IDrawFoldersMLM(&$Folders, &$FoldersIn);

                        $helpstr = "";//<a class=LinkMedium onfocus='blur();' href=\"Javascript:popUpWin('rich/training.html',600,400)\" >I&nbsp;Need&nbsp;Help</a>&nbsp;|&nbsp;";
                break;

                case $ACCOUNT_CHAT:
                        IDrawFoldersChat(&$Folders, &$FoldersIn);
                break;

                case $ACCOUNT_DTF:
                        $helpstr = "<a class=LinkMedium href='http://www.debttofreedom.com/exec/webbs/webbbs_files/webbbs_config.pl' >Member Forum</a> <b>&nbsp;|&nbsp;</b> ";
                        $helpstr.= "<a class=LinkMedium href='http://www.acrisp.com/debttofreedom/' target='_blank'>Financial Center</a> <b>&nbsp;|&nbsp;</b> ";

                        IDrawFoldersDTF(&$Folders, &$FoldersIn);
                break;

                default:
                        IDrawFoldersRR(&$Folders, &$FoldersIn);
                break;
        }


?>
<table cellpadding=0 cellspacing=0 border=0 width="<?=GetSystemHeaderWidth();?>">
<tr>
        <td align="left"><?=GetSystemHeader(); ?></td>
        <td align=center class="pptext"></td>
        <td class="pptext" ><?php

        if (empty($DREAMTIME_ACCOUNT))
        {
                if (session_is_registered("login_account_id"))
                {
                ?>
                <?=$helpstr?><a class="LinkMedium" onfocus="blur();" href="<?=GetSystemLogoutPage()?>?Reason=OnRequest" <?=$target?>>Log&nbsp;Out</a>&nbsp;<!--|&nbsp;<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_help&source_page=_login-done">Help</a>-->
                <?php
                }
                echo "</td>";
        }
        else
        {
                if (session_is_registered("login_account_id"))
                {
                ?>
                </td></tr>
                <tr><td colspan=5 width="100%" valign=top><table cellspacing=0 cellpadding=0 border=0 width="100%"><tr><td width="100%"></td><td nowrap>
                <?=$helpstr?><a class="LinkMedium" onfocus="blur();" href="<?=GetSystemLogoutPage()?>?Reason=OnRequest" <?=$target?>>Log&nbsp;Out</a>&nbsp;</td><td width=15 nowrap><img src=images/transp.gif></td></tr></table></td><!--|&nbsp;<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_help&source_page=_login-done">Help</a>--></td>
                <?php
                }
        }
        ?>
</tr>
</table><table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
<tr>
        <td background="images/tabs/bg.gif" width=100%>
        <table border=0 cellpadding=0 cellspacing=0 align=center>
        <tr>
<?php

        while (list($FolderName, $Item) = each($Folders))
        {
                $url        = $Item["url"];
                $img        = $Item["img"];
                $on                = (Strcasecmp($FolderName, $CurrentFolder)==0);

                if ($on)
                {

                        $img = "images/tabs/P_on" . $img;
                }
                else
                {
                        $img = "images/tabs/P_off" . $img;
                }
?>
                <td><a href="<?=$url?>" <?=$target?>><?=ImgTag("", $img, 0, "alt='$FolderName'");?></a></td>
                <td><img src="images/pixel.gif" width=1 height=1></td>
<?php
        }
?>
        </tr>
        </table>

        <img src="images/pixel.gif" width=4 height=4><br>

<?php
        if (!$draw_inner)
        {
        $ActiveFolder="";//echo "</td></tr></table>";
        //return true;

        }
?>

        <table border=0 cellpadding=0 cellspacing=0 align=center>
        <tr>
        <?php
                $on_last = true;
                if (!empty($FoldersIn["$ActiveFolder"]))
                while (list($FolderName, $Item) = each($FoldersIn["$ActiveFolder"]))
                {

                        $url         = $Item["url"];
                        $img         = $Item["img"];
                        $on                 = (Strcasecmp($FolderName, $PageName)==0);

                        if (!$on_last && !$on)
                        {
                        ?>
                                <td bgcolor="#ffffff"><img src="images/pixel.gif" width=1 height=1></td>
                        <?php
                        }

                        $on_last = $on;

                        if ($on)
                        {
                                $img = "images/tabs/SA_on" . $img;
                        }
                        else
                        {
                                $img = "images/tabs/SA_off" . $img;
                        }
        ?>
                        <td><a href="<?=$url?>" <?=$target?>><?=ImgTag("", $img, 0, "alt='$ActiveFolder - $FolderName'");?></a></td>
        <?php
                }
        ?>
                        <td><img src="images/tabs/SA_none.gif" width=91 height=19 alt=""></td>
                </tr>
        </table>

        </td>
        <td><img src="images/pixel.gif" width=1 height=59></td>

</tr>
</table>

<?php
        // Start table
}

function PrintPageHeading($Str, $full=true)
{
        if ($full)
        {
                $width = "100%";
        }
        else
        {
                $width = "650";
        }
?>
        <table width="<?=$width?>" cellpadding=0 cellspacing=0 border=0 align=center>
        <tr>
          <td width="100%" class="ppheading"><?=$Str?></TD>
        </tr>
        <tr>
                <td><img src="images/pixel.gif" width=2 height=2></td>
        </tr>
        </table>
        <table cellpadding=0 cellspacing=0 border=0 align=center width="<?=$width?>">
        <tr>
                <td><img src="images/pixel.gif" width=6 height=6></td>
        </tr>
        <tr>
                <td bgcolor="#999999"><img src="images/pixel.gif" width=1 height=2></td>
        </tr>
        <tr>
                <td><img src="images/pixel.gif" width=6 height=6></td>
        </tr>
        </table>
<?php
}

function Str2Bool($A)
{
        if (empty($A))
        {
                return false;
        }

        if (Strcasecmp($A, "on")==0 ||
            Strcasecmp($A, "true")==0)
        {
                return true;
        }

        return false;
}

function DrawWebsiteFolders($ActiveFolder)
{
        global        $LoginRequired, $CurrentFolder, $HomeURL, $ACCOUNT_TYPE, $ACCOUNT_MLM, $ACCOUNT_DOWNLINE, $ACCOUNT_CHAT, $ACCOUNT_DTF;

        // Setup folder structure
        switch ($ACCOUNT_TYPE)
        {
                case $ACCOUNT_DOWNLINE:
                case $ACCOUNT_MLM:
                        IDrawFoldersMLM(&$Folders, &$FoldersIn);
                break;

                case $ACCOUNT_DTF:
                        IDrawFoldersDTF(&$Folders, &$FoldersIn);
                break;

                case $ACCOUNT_CHAT:
                        IDrawFoldersChat(&$Folders, &$FoldersIn);
                break;

                default:
                        IDrawFoldersRR(&$Folders, &$FoldersIn);
                break;
        }


?>
<table cellpadding=0 cellspacing=0 border=0 align=center width="<?=GetSystemHeaderWidth();?>">
<tr>
        <td nowrap align=left width="100%"><SPAN class="ppheading"><i><b><?=GetSystemHeader(); ?></b></i></SPAN></td>
        <td width=100% align=center class="pptext">&nbsp;</td>
</tr>
<tr height=15 nowrap><td><img src="images/transp.gif"></td></tr>
</table>



<table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
<tr>
        <td background="images/tabs/bg.gif" width=100%>
        <table border=0 cellpadding=0 cellspacing=0 align=center>
        <tr>
<?php

        while (list($FolderName, $Item) = each($Folders))
        {
                $img        = $Item["img"];
                $on                = (Strcasecmp($FolderName, $CurrentFolder)==0);

                if ($on)
                {

                        $img = "images/tabs/P_on" . $img;
                }
                else
                {
                        $img = "images/tabs/P_off" . $img;
                }
?>
                <td><?=ImgTag("", $img, 0, "alt='$FolderName'");?></td>
                <td><img src="images/pixel.gif" width=1 height=1></td>
<?php
        }
?>
        </tr>
        </table>

        <img src="images/pixel.gif" width=4 height=4><br>

        <table border=0 cellpadding=0 cellspacing=0 align=center>
        <tr>
                        <td><img src="images/tabs/SA_none.gif" width=91 height=19 alt=""></td>
                </tr>
        </table>

        </td>
        <td><img src="images/pixel.gif" width=1 height=59></td>

</tr>
</table>

<?php
}

function printChartPageLink($URL, $new_start_from_page, $OptionalParams, $ShowArrow = 0)
{
        global $start_from_page, $sort_by_field, $sort_ascending;
        global $PREF_START_FROM_PAGE, $PREF_SORT_ASCENDING, $PREF_SORT_BY_FIELD;

        if ($ShowArrow != 0)
        {
                echo "";
        }

        if ($ShowArrow == 1)
        {
                echo "&nbsp;";
        }

        if (!empty($URL))
        {
                echo        "<a class=LinkSmall onfocus=\"blur()\" href=".
                                "\"". $URL. "?";

                if (!empty($OptionalParams))
                {
                        echo $OptionalParams."&";
                }

                echo        "$PREF_START_FROM_PAGE=$new_start_from_page";//#chart";

                echo        "\">";
        }

        if ($ShowArrow==0)
        {
                echo "<b>$new_start_from_page</b>";

                if (!empty($URL))
                {
                        echo "</a>";
                }
        }
        else
        if ($ShowArrow==-1)
        {
                echo "<img border=0 src=images/btnprev.gif alt=\"Previous Page\" width=57 height=20></a>&nbsp;";
//
//                echo "<b><<</b></a>&nbsp;";
        }
        else
        if ($ShowArrow==1)
        {
                echo "<img border=0 src=images/btnnext.gif alt=\"Next Page\" width=57 height=20></a>&nbsp;";
//                echo "<b>>></b></a>";
        }

        if ($ShowArrow != 0)
        {
//                echo "</td>";
        }

}

function printChartSortByLink($URL, $new_sort_by_field, $Name, $AlignRight = false, $Cols = 1)
{
        global $PREF_START_FROM_PAGE, $PREF_SORT_ASCENDING, $PREF_SORT_BY_FIELD;
        global $start_from_page, $sort_ascending, $sort_by_field, $filter;

        // Set this for easier access
        $current_sort_by_field = !empty($sort_by_field) && (Strcasecmp($sort_by_field, $new_sort_by_field)==0);

        echo "<td nowrap background=\"images/chart_topbg.gif\" height=21 colspan=$Cols ";

        if ($AlignRight)
        {
                echo "align=right ";
        }

        echo ">";

        // If this is current sort_by field
        if ($current_sort_by_field)
        {
                if ($sort_ascending)
                {
                        $new_sort_ascending = 0;
                }
                else
                {
                        $new_sort_ascending = 1;
                }
        }
        else // Not current sort_by field
        {
                // Order is ascending by default
                $new_sort_ascending = 1;
        }

        // If filter, no URL
        $filter = stri_replace("&","",$filter);
        if (!empty($filter)) $URL="";

        // Print link
        //if (!empty($URL)){
                echo        "<a class=LinkChart onfocus=\"blur();\" title=\"";
                echo        "Sort by ". $Name;
                if ($current_sort_by_field && $sort_ascending)
                {
                        echo " (Descending)";
                }
                echo        "\" ";
                echo        "href=\"". $URL;

                if (_strpos($URL,"?")==-1)
                {
                        echo "?";
                }
                else
                {
                        echo "&";
                }

                echo        "$PREF_SORT_BY_FIELD=$new_sort_by_field&$PREF_SORT_ASCENDING=$new_sort_ascending&$PREF_START_FROM_PAGE=1";//#chart";

                echo         "\">";

/*
        }
        else
        {
                echo        "<SPAN class=LinkChart>";
        }*/

        // Print field name
        if (!empty($URL))
        {
                echo        "$Name";
        }
        else
        if (!empty($Name))
        {
                echo        "$Name";
        }

        // If this is current sort_by field
        if ($current_sort_by_field && empty($filter))
        {
                // If ascending
                if ($sort_ascending)
                {
                        echo "&nbsp;<img src=\"images/asc.gif\"  valign=\"middle\" height=11 border=0>&nbsp;";
                }
                else
                {
                        echo "&nbsp;<img src=\"images/desc.gif\" valign=\"middle\" height=11 border=0>&nbsp;";
                }
        }

        if ($URL != "")
        {
                echo "</a>";
        }

//        echo "&nbsp;&nbsp;";
        if (!$current_sort_by_field) echo "&nbsp;&nbsp;&nbsp;";

        echo "</td>";
}

function getNumPages($count, $rows_per_page)
{
        $NumPages        = $count / $rows_per_page;
        settype($NumPages, "integer");
        if ($count % $rows_per_page > 0)
        {
                $NumPages++;
        }

        return $NumPages;
}

function printChartCell($Str, $Icon="", $AlignRight=false, $ExtraPrefix="", $ExtraSuffix="", $ExtraBeforePrefix="")
{
        global $IEBROWSER;

        if (empty($Str))
        {
                $Str = "&nbsp;";
        }

        echo "<td nowrap ";

        if ($AlignRight==1)
        {
                echo "align=right ";
        }
        else
        if ($AlignRight==2)
        {
                echo "align=center ";
        }
        else
        if ($AlignRight==true)
        {
                echo "align=right ";
        }

        echo "><SPAN class=TextTiny>";

        if (!empty($ExtraBeforePrefix))
        {
                echo "$ExtraBeforePrefix";
        }

        if ($Icon != "")
        {
                ImgTag("", $Icon, 0, $IEBROWSER ? "align=absmiddle" : "align=absmiddle");
        }
        echo "<img src=images/transp.gif width=4>";
        echo "$ExtraPrefix".($Str)."$ExtraSuffix";

        if ($AlignRight)
        {
//                echo "&nbsp;";
        }

        echo "<img src=images/transp.gif width=4>";

        echo "</SPAN></td>";
}

function printChartPageLinks($count, $URL, $OptionalParams = "")
{
        global        $ROWS_PER_PAGE, $start_from_page, $MAX_NUM_PAGES, $PREF_START_FROM_PAGE, $filter;

        if ($start_from_page>1 && !empty($filter))
        {
                echo "<SPAN class=TextSmall>";
                echo "<a href=\"". $URL. "?";
                if (!empty($OptionalParams))
                {
                        echo $OptionalParams."&";
                }
                echo        "$PREF_START_FROM_PAGE=".($start_from_page-1);
                echo        "\">";
                echo "<img border=0 src=images/btnprev.gif alt=\"Previous Page\" width=57 height=20></a>&nbsp;</SPAN>";
        }
        if ($count==-3 || $count==-4)
        {
                echo "<SPAN class=TextSmall>";
                echo "<a href=\"". $URL. "?";
                if (!empty($OptionalParams))
                {
                        echo $OptionalParams."&";
                }
                echo        "$PREF_START_FROM_PAGE=".($start_from_page+1);
                echo        "\">";
                echo "<img border=0 src=images/btnnext.gif alt=\"Next Page\" width=57 height=20></a>&nbsp;</SPAN>";
        }
        if ($count==-2 || $count==-3 || $count==-4)
        {
                return true;
        }


        echo "<SPAN class=TextSmall>";
        echo "Page ";

        // Sanity check
        if ($count<1)
        {
                echo "1 of 1 [<b>1</b>]</SPAN>";
                return true;
        }

        // Set these for easier access
        $NumPages        = getNumPages($count, $ROWS_PER_PAGE);

        echo "<b>$start_from_page</b> of ".number_format($NumPages). "&nbsp;";

                echo "[";

                for ($i=0; $i<min($NumPages, $MAX_NUM_PAGES); $i++)
                {
                        $Page = $i+1;

                        if ($i != 0)
                        {
                                echo " ";
                        }

                        if ($Page == $start_from_page)
                        {
                                echo "</SPAN><SPAN class=TextMedium><b>". $Page. "</b></SPAN><SPAN class=TextSmall>";
                        }
                        else
                        {
                                printChartPageLink ($URL, $Page, $OptionalParams);
                        }
                }

                if ($NumPages>$MAX_NUM_PAGES)
                {
                        echo " ";
                        printChartPageLink("", "...", $OptionalParams);
                }

                echo "]";

                echo "<BR>";

                if ($count>0)
                {
                        echo "(Total ".number_format($count);
                        if ($count > 1)
                                echo " records)";
                        else
                                echo " record)";
                }

        echo "</SPAN>";
        return true;
}

function printChartTopPageLinks($count, $URL, $OptionalParams = "", $ShowTotal = true)
{
        global        $ROWS_PER_PAGE, $start_from_page, $PREF_START_FROM_PAGE, $filter;

        if ($start_from_page>1 && !empty($filter))
        {
                echo "<SPAN class=TextSmall>";
                echo "<a href=\"". $URL. "?";
                if (!empty($OptionalParams))
                {
                        echo $OptionalParams."&";
                }
                echo        "$PREF_START_FROM_PAGE=".($start_from_page-1);
                echo        "\">";
                echo "<img border=0 src=images/btnprev.gif alt=\"Previous Page\" width=57 height=20></a>&nbsp;</SPAN>";
        }
        if ($count==-3 || $count==-4)
        {
                echo "<SPAN class=TextSmall>";
                echo "<a href=\"". $URL. "?";
                if (!empty($OptionalParams))
                {
                        echo $OptionalParams."&";
                }
                echo        "$PREF_START_FROM_PAGE=".($start_from_page+1);
                echo        "\">";
                echo "<img border=0 src=images/btnnext.gif alt=\"Next Page\" width=57 height=20></a>&nbsp;</SPAN>";
        }
        if ($count==-2 || $count==-3 || $count==-4)
        {
                return true;
        }

        // Sanity check
        if ($count<1)
        {
                echo "<SPAN class=TextSmall>Page 1 of 1</SPAN>";
                return true;
        }

        echo "<SPAN class=TextSmall>";

        // Set these for easier access
        $NumPages        = getNumPages($count, $ROWS_PER_PAGE);

        if ($start_from_page > 1)
        {
                printChartPageLink ($URL, $start_from_page-1, $OptionalParams, -1);
        }

        echo "</td><td nowrap><span class=TextSmall>Page <b>$start_from_page</b> of ".number_format($NumPages)."</td><td nowrap><span class=TextSmall>";

        if ($start_from_page < $NumPages)
        {
                printChartPageLink ($URL, $start_from_page+1, $OptionalParams, 1);
        }

        echo "</SPAN>";

        return true;
}


function GetAllNamesFrom2DArray($Array)
{
        $Fields = array();

        for ($i=0; $i<count($Array); $i++)
        {
                $Item        = $Array[$i];

                $cnt = 0;

                foreach ($Item as $Name => $Value)
                {
                        // If numeric array index,
                        if (Strcasecmp($Name, $cnt)==0)
                        {
                                // Ignore
                                $cnt++;
                                continue;
                        }

                        // If empty
                        if (empty($Name))
                        {
                                // Ignore
                                continue;
                        }

                        // If we don't already have this field
                        if (!isStringInArray($Name, $Fields))
                        {
                                // Add it
                                $Fields[] = $Name;
                        }
                }
        }

        return $Fields;
}

// Check if a given string appears in a 2-dimensional associative array
// (Used to ensure specific restricted name/value pairs are not added)
function isStringIn2DArray($Str, $Array)
{
        for ($i=0; $i<count($Array); $i++)
        {
                $Item        = $Array[$i];

                if (isset($Item["$Str"]))
                {
                        return true;
                }
        }

        return false;
}


// Check if a given string appears in a string array
// (Used to ensure specific restricted name/value pairs are not added)
function isStringInArray($Str, $Array)
{
        $cnt = count($Array);
        for ($i=0; $i<$cnt; $i++)
        {
                if (Strcasecmp($Str, $Array[$i])==0)
                {
                        return true;
                }
        }

        return false;
}

// Draw XP-style button image
function DrawXPButton($text, $name, $action="", $align="left")
{
global $IEBROWSER, $http_addr;

//echo "<input type=button name='$name' value='&nbsp;&nbsp;$text&nbsp;&nbsp;'>";
//return;

?><table border=0 cellspacing=0 cellpadding=0>
<tr height=6>
<td nowrap width=5 height=6 background="<?=$http_addr?>/images/button_topleft.gif"><img src="<?=$http_addr?>/images/transp.gif" width=5 height=6></td>
<td nowrap height=6 background="<?=$http_addr?>/images/button_top.gif"><img src="<?=$http_addr?>/images/transp.gif" height=6></td>
<td nowrap width=5 height=6 background="<?=$http_addr?>/images/button_topright.gif"><img src="<?=$http_addr?>/images/transp.gif" width=5 height=6></td>
</tr>
<tr>
<td nowrap width=5  background="<?=$http_addr?>/images/button_left.gif"><img src="<?=$http_addr?>/images/transp.gif" width=5></td>
<td nowrap background="<?=$http_addr?>/images/button_bg.gif">
<?php
if (!empty($action))
{
        echo "<a class=TextButton href=\"$action\"\n";
        echo "onMouseOver=\"window.status='$text'; return true;\" onClick=\"window.status=''; return true;\" onMouseOut=\"window.status=''; return true;\" ";
        echo ">";
}
else
{
        echo "<span class=TextButtonGray>";
}

echo "&nbsp;&nbsp;$text&nbsp;&nbsp;";

if (!empty($action)) echo "</a>";
else echo "</span>";
?></td>
<td nowrap width=5 background="<?=$http_addr?>/images/button_right.gif"><img src="<?=$http_addr?>/images/transp.gif" width=5></td>
</tr>

<tr height=5>
<td nowrap width=5 height=5 background="<?=$http_addr?>/images/button_bottomleft.gif"><img src="<?=$http_addr?>/images/transp.gif" width=5 height=5></td>
<td nowrap background="<?=$http_addr?>/images/button_bottom.gif"><img src="<?=$http_addr?>/images/transp.gif"></td>
<td nowrap width=5 height=5 background="<?=$http_addr?>/images/button_bottomright.gif"><img src="<?=$http_addr?>/images/transp.gif" width=5 height=5></td>
</tr>
</table></td><?php
}

function DrawTableBG($src, $width_tile = 0, $height_tile = 0)
{
        $bbox = GetImageSize($GLOBALS["DOCUMENT_ROOT"] . $src);
        $width = $bbox[0];
        $height = $bbox[1];

        echo "<table cellspacing=0 cellpadding=0 nowrap width=\"100%\" border=0 background=\"images/transp.gif\"><tr><td nowrap >";
        if ($width_tile!=0 && $height_tile!=0)
        {
                echo "<img src=\"tile_image.php?src=$src&width=$width_tile&height=$height_tile\">";
        }
        else
        {
                echo "<img src=\"$src\">";
        }
        echo "</td></tr></table>";
}

function DrawFormTitle($title, $nowrap = false)
{
?>
  <table border=0 cellspacing=0 cellpadding=0 <?php if ($nowrap) echo "width=\"100%\""; else echo "width=1"; ?>>

  <tr>
  <td width=25 height=31 nowrap background="index_files/boxldevelop1.gif"><img src="images/transp.gif" width=25 height=31></td>
  <td width="100%">
  <table cellpadding=0 width="100%" cellspacing=0 border=0  >
  <tr>
  <td nowrap background="index_files/boxdevelop1.gif" valign="top">
<table border=0 cellpadding=0 cellspacing=0 background=""><tr height=11><td width=1><img src="images/transp.gif" width=1 height=11></td></tr><tr>
<td nowrap><span class=TextButton><font color="#42595A"><b><?php echo $title?></b></font></span></td></tr></table></td>
  <td width=18 height=31 nowrap background="index_files/boxudevelop1.gif" ><img src="images/transp.gif" width=18 height=31></td>
  <td nowrap width="100%" height=31 background="index_files/boxuu1.gif"><img src="images/transp.gif" height=31></td>
  </tr>
  </table>
  </td>
  <td width=14 height=31 nowrap background="index_files/boxrdevelop1.gif"><img src="images/transp.gif" width=14 height=31></td>
  <td nowrap width=1 ><img src="images/transp.gif" width=1></td>
  <td width=1 nowrap><img src="images/transp.gif" ></td>
  </tr>

<tr>
<td width=25 nowrap background="index_files/boxl1.gif"><img src="images/transp.gif" width=25></td>
<td <?php if ($nowrap) echo "nowrap";?> bgcolor="#F7F7F7"><SPAN class=TextTiny><br></SPAN>
<?php
}

function Draw2ColumnStart()
{
?>
        <table border=0 width="100%" cellspacing=0 cellpadding=0>
        <tr>
        <td valign=top>
                <table width=155 cellspacing=0 cellpadding=1 border=0 align=center>
                        <tr>
                                <td bgcolor=#CCCCCC >

<?php
}

function Draw2ColumnSeparator($full = true)
{
        $width_str = "";
        if ($full) $width_str = "width=100%";
?>
                        </td>
                        </tr>
                </table>

                </td>
                <td><img src="images/pixel.gif" width=10 height=1 border=0></td>
                <?php
                ?>
                <td valign=top <?=$width_str?>>

<?php
}

function Draw2ColumnEnd()
{
?>

</td>
</tr>
</table>

<?php
}

function DrawGrayBoxTitle($title)
{
?>
                                <table width=100% cellspacing=0 cellpadding=4 border=0 align=center>
                                        <tr>
                                                <td bgcolor=#EEEEEE align=center class="pptext"><b><?=$title?></b></td>
                                        </tr>
                                </table>

                                <table bgcolor=#FFFFFF width="100%" cellspacing=0 cellpadding=5 border=0 align=center>

<?php
}

function DrawGrayBoxLink($title, $link, $new_window = false)
{
        $text_color = "pptext";
        if (empty($link))
        {
                $text_color = "TextSmall";
//                $link = "Javascript:void(0);";
        }

        $target_str = "";
        if ($new_window) $target_str = "target='_blank'";
?>
        <tr>
                <td class="<?=$text_color?>">
                <?php
                if (empty($link))
                {
                        echo $title;
                }
                else
                {
                        echo "<a href=$link $target_str>$title</a>";
                }
                ?>
                </td>
        </tr>

<?php
}

function DrawGrayBoxTextLink($title, $link, $text, $new_window = false)
{
        $text_color = "pptext";
        if (empty($link))
        {
                $text_color = "TextSmall";
//                $link = "Javascript:void(0);";
        }

        $target_str = "";
        if ($new_window) $target_str = "target='_blank'";
?>
        <tr>
                <td class="<?=$text_color?>">
                <a href=<?=$link?> <?=$target_str?>><?=$title?></a><br><span class=TextTiny><?=$text?><br></span>
                </td>
                </td>
        </tr>

<?php
}

function DrawGrayBoxBottom()
{
?>
                                </table>

                                <table bgcolor=#ffffff width="100%" cellspacing=0 cellpadding=0 border=0 align=center>
                                        <tr>
                                                <td class="ppsmalltext">&nbsp;</td>
                                        </tr>
                                </table>

<?php
}

function DrawBoxTitle($title, $nowrap = true, $width="", $height="")
{
	global $out_addr;
        global $IEBROWSER;

        $height_str = "";
        $width_str = "width=100%";

        if (!empty($width)) $width_str = "width=$width";
        if (!empty($height)) $height_str = "height=$height";
?>
  <table border=0 cellspacing=0 cellpadding=0 <?=$width_str?> <?=$height_str?>>
  <tr >
  <td width=15 nowrap background="<?php echo $out_addr; ?>images/boxldevelop.gif"><img src="<?php echo $out_addr; ?>images/transp.gif" width=15 ></td>
  <td width="100%" background="<?php echo $out_addr; ?>images/boxdevelop.gif" height=33 valign="top" nowrap><table height=33 border=0 cellpadding=0 cellspacing=0  ><tr>
  <td nowrap valign=middle><span class=TextButton><font color="#42595A"><b><?php echo $title?></b></font></span></td></tr></table></td>
  <td width=14 nowrap background="<?php echo $out_addr; ?>images/boxrdevelop.gif"><img src="<?php echo $out_addr; ?>images/transp.gif" width=14 ></td>
  <td nowrap width=1><img src="<?php echo $out_addr; ?>images/transp.gif" width=1></td>
  <td width=1 nowrap><img src="<?php echo $out_addr; ?>images/transp.gif" ></td>
  </tr>

<tr >
<td width=15 nowrap background="<?php echo $out_addr; ?>images/boxl.gif"><img src="<?php echo $out_addr; ?>images/transp.gif" width=15></td>
<td  <?php if ($nowrap) echo "nowrap";?> bgcolor="#FFFFFF" valign=top >
<?php
}

function DrawFormBottom()
{
        global $IEBROWSER;

?>
            </td>
<td nowrap width=14 nowrap background="index_files/boxright1.gif"><img src="images/transp.gif" width=14></td>
<td nowrap width=1 ><img src="images/transp.gif" width=1></td>
<?php
if ($IEBROWSER)
{
        echo "<td nowrap width=1><img src=\"images/transp.gif\" width=1></td>";
}
?>
</tr>
  <tr>
  <td width=15 height=12 nowrap background="index_files/boxlddevelop1.gif"><img src="images/transp.gif" width=15 height=12></td>
  <td nowrap width="100%" height=12 background="index_files/boxdddevelop1.gif" ><img src="images/transp.gif" height=12></td>
  <td width=14 height=12 nowrap background="index_files/boxrddevelop1.gif"><img src="images/transp.gif" width=14 height=12></td>
  <td width=1 nowrap height=12><img src="images/transp.gif" width=1 height=12></td>
  <td width=1 nowrap height=12><img src="images/transp.gif" width=1 height=12></td>
  </tr>
  </table>
<?php
}

function DrawBoxBottom()
{
	global $out_addr;
        global $IEBROWSER;

?>
            </td>
<td nowrap width=14 nowrap background="<?php echo $out_addr; ?>images/boxright.gif"><img src="<?php echo $out_addr; ?>images/transp.gif" width=14></td>
<td nowrap width=1 ><img src="<?php echo $out_addr; ?>images/transp.gif" width=1></td>
<?php
if ($IEBROWSER)
{
        echo "<td nowrap width=1><img src=\"$out_addr/images/transp.gif\" width=1></td>";
}
?>
</tr>
  <tr>
  <td width=15 height=12 nowrap background="<?php echo $out_addr; ?>images/boxlddevelop.gif"><img src="<?php echo $out_addr; ?>images/transp.gif" width=15 height=12></td>
  <td nowrap width="100%" height=12 background="<?php echo $out_addr; ?>images/boxdddevelop.gif" ><img src="<?php echo $out_addr; ?>images/transp.gif" height=12></td>
  <td width=14 height=12 nowrap background="<?php echo $out_addr; ?>images/boxrddevelop.gif"><img src="<?php echo $out_addr; ?>images/transp.gif" width=14 height=12></td>
  <td width=1 nowrap height=12><img src="<?php echo $out_addr; ?>images/transp.gif"></td>
  <td width=1 nowrap height=12><img src="<?php echo $out_addr; ?>images/transp.gif" ></td>
  </tr>
  </table>
<?php
}

function DrawInfoBox($title, $description)
{
?>
<table border=0 cellspacing=0 cellpadding=0 width="100%">
<tr height=6>
<td nowrap width=6 height=6 background="images/box_topleft.gif"><img src="images/transp.gif" width=6 height=6></td>
<td nowrap width="100%" height=6 background="images/box_top.gif"><img src="images/transp.gif" height=6></td>
<td nowrap width=12 height=6 background="images/box_topright.gif"><img src="images/transp.gif" width=12 height=6></td>
</tr>
<tr>
<td width=6 height=6 background="images/box_left.gif" nowrap><img src="images/transp.gif" width=6 height=6></td>
<td nowrap bgcolor="#F7F7F7">


<table border=0 cellspacing=0 cellpadding=0 width="100%">

<tr>
<td nowrap align=center valign="top"><span class=TextInfoTitle><img src="/image_title.php?type=infobox&background=images/infoboxbg.png&text=<?=urlencode($title); ?>"></span></td>
</tr>

<tr height=5>
<td colspan=3><img src="images/transp.gif" height=10 width=1></td>
</tr>

</table>


</td>
<td width=12 nowrap background="images/box_right.gif"><img src="images/transp.gif" width=12></td>
</tr>

<tr height=6>
<td nowrap width=6 height=6 background="images/box_bottomleft.gif"><img src="images/transp.gif" width=6 height=6></td>
<td nowrap width="100%" background="images/box_bottom.gif"><img src="images/transp.gif"></td>
<td nowrap width=12 height=6 background="images/box_bottomright.gif"><img src="images/transp.gif" width=12 height=6></td>
</tr>
</table><br>
<?php
}

function DrawChartVisibleBorder($height=21)
{
        echo "<td align=center height=$height>";
        echo "<table cellspacing=0 cellpadding=0 border=0 height=$height><tr>";
        echo "<td nowrap width=1 bgcolor='#aaaaaa'><img src=images/transp.gif width=1></td>";
        echo "</tr></table>";
        echo "</td>";
}

function DrawChartBorder($height=0)
{
        echo "<td><img src=images/transp.gif></td>";
}

function DrawChartTopBorder()
{
?>
<td nowrap height=21 width="1%" background="images/chart_topbg.gif"><img src="images/chart_border.gif" width=10 valign="top"></td>
<?php
}

function DrawChartBottom()
{
?>


</table>

</td>
</tr>

</table>


</td>
</tr>
</table>

<?php
}

function DrawChartTop($count, $URL, $Params="", $ShowSearch=true, $FormName="Chart", $countNoFilter=-1, $width="", $height="")
{

        global        $start_from_page, $sort_ascending, $sort_by_field, $filter;
        global        $MaxPageNum, $ROWS_PER_PAGE, $CLOSE_FORM;
        global        $MAX_RECORDS_NONINDEX_SEARCH, $ChartName;
global $HTTP_COOKIE_VARS, $month_name, $operator, $dbhost, $dbusername, $dbuserpassword, $default_dbname,
$this_year, $last_year;
        $height_str = "";
        $width_str = "width=100%";

        if (!empty($width)) $width_str = "width=$width";
        if (!empty($height)) $height_str = "height=$height";

        // Initialize
        if ($countNoFilter==-1)
        {
                $countNoFilter = $count;
        }

        // Verify start_from_page is okay
        if ($count>=0)
        {
                // Get max number of pages
                $MaxPageNum = getNumPages($count, $ROWS_PER_PAGE);

                // Validate
                if ($start_from_page < 1)
                {
                        $start_from_page = 1;
                }
                if ($start_from_page > $MaxPageNum)
                {
                        $start_from_page = $MaxPageNum;
                }
        }

        $CLOSE_FORM        = true;
?>

<?php
URLParametersToFormInputs($Params);
?>
<table width=100% border=0 cellspacing=1 cellpadding=0>
<tr>
<?php
if($FormName == "history" or $FormName == "invoice"){
$month = $HTTP_COOKIE_VARS["filter_month"];
$f_reader = $HTTP_COOKIE_VARS["filter_reader"];
$reader_arr[all]="All Readers";

?>
<td width=15><form name="month" action="month.php" method="post">
  <select size="1" name="month">
  <option value="<?=$month?>"><?=$month_name[$month]?></option>
  <option value="all">All months</option>
  <option value="1">Jan</option>
  <option value="2">Feb</option>
  <option value="3">Mar</option>
  <option value="4">Apr</option>
  <option value="5">May</option>
  <option value="6">Jun</option>
  <option value="7">Jul</option>
  <option value="8">Aug</option>
  <option value="9">Sep</option>
  <option value="10">Oct</option>
  <option value="11">Nov</option>
  <option value="12">Dec</option>

  </select></td>
<?php
if($operator[type] == "Administrator"){
echo"<td><select size=\"1\" name=\"reader\">";
echo"<option value=\"all\">All Readers</option>";
$conn = mysql_connect($dbhost, $dbusername, $dbuserpassword);
mysql_select_db($default_dbname);
$strsql = "SELECT * FROM T1_1 WHERE type = 'reader' order by name";
$rs = mysql_query($strsql, $conn) or die(mysql_error());
while ($row = mysql_fetch_assoc($rs)){
$name_r= $row["name"];
$rr_record_id_r= $row["rr_record_id"];
$reader_arr[$rr_record_id_r]=$name_r;

echo"<option value=\"$rr_record_id_r\">$name_r</option>";
}
mysql_free_result($rs);
mysql_close($conn);
echo"<option value=\"$f_reader\" selected>$reader_arr[$f_reader]</option>";
echo"</select></td>";
}
if($FormName == "history" or $FormName == "invoice"){
echo"<td><select size=\"1\" name=\"period\">";
echo"<option value=\"$HTTP_COOKIE_VARS[filter_period]\" SELECTED>$HTTP_COOKIE_VARS[filter_period]</option>";
echo"<option value=\"1-15\">1-15</option>";
echo"<option value=\"16-31\">16-31</option>";
echo"<option value=\"All\">All</option>";
echo"</select></td>";
}
?>
<td width=100>
<?=$this_year?> <input type="radio" value="<?=$this_year?>"
<?php
if($HTTP_COOKIE_VARS[filter_year] == $this_year){
echo" checked ";
}
?>
name="filter_year">
</td>
<td width=40>
<?=$last_year?> <input type="radio" value="<?=$last_year?>"
<?php
if($HTTP_COOKIE_VARS[filter_year] == $last_year){
echo" checked ";
}
?>
name="filter_year">
</td>

<td>
<?php DrawXPButton('Go','Go', "Javascript:document.forms['month'].submit();"); ?></form>
</td>
<?php
}
?>
<form name="<?=$FormName?>" action="<?=$URL?>" method="post">
<?php
if (isset($ChartName))
{
        echo "<td nowrap><SPAN class='pptext'>&nbsp;<b>$ChartName</b></SPAN></td>";
}
?>
<td nowrap width="100%"><img src="images/transp.gif"></td>
<?php
$FilterOn = false;
if (isset($filter)) if (!empty($filter)) $FilterOn = true;

if ($FilterOn)
{
        $ShowRecordsContaining = "Showing ONLY fields Starting with:";
}
else
{
        $ShowRecordsContaining = "Search for fields Starting with:";
}

if ($ShowSearch){
?>
        <td nowrap ><SPAN class=TextTiny><?php
        if ($FilterOn) echo "<b>";?>
        <?=$ShowRecordsContaining?>&nbsp;<?php if ($FilterOn) echo "</b>";
        ?></SPAN></td>
        <td nowrap ><input class=InputBoxTiny name=filter size=10 type=text <?php if ($FilterOn) printf("value=\"%s\"", htmlspecialchars($filter)); ?>></td>
        <td nowrap width=1><?php DrawXPButton('Go','Go', "Javascript:document.forms['$FormName'].submit();");  ?></td>
        <td nowrap width=1><?php if ($FilterOn) DrawXPButton('Show All','ShowAll', URLCat($URL)."filter=&$Params"); ?></td>
        <td nowrap width=10><img src='images/transp.gif' width=10></td>
        <td nowrap><SPAN class=TextTiny><?php
        printChartTopPageLinks($count, $URL, $Params, false);
        ?></SPAN></td>
        <td nowrap width=15><img src="images/transp.gif"></td>

</tr>
<?php
}
?>
</form>
</table>

<table <?=$width_str?> <?=$height_str?> border=0 cellspacing=0 cellpadding=1 bgcolor="#7F9DB9" >
<tr>
<td width="100%" valign=top >

<table width="100%" border=0 cellspacing=0 cellpadding=0 bgcolor=white>

<tr>
<td>
<table <?=$width_str?> <?=$height_str?> border="0" cellspacing="0" cellpadding="0" >

<?php
}

function DrawChartXSpace($Border = false)
{
        $Image = "";

        if ($Border)
        {
                $Image = "images/chart_topbg.gif";
        }
?>
        <td nowrap width="1%" background="<?=$Image?>"><img src="images/transp.gif" ></td>
<?php
}

function DrawChartYSpace($NUM_COLS=0, $bg_color=false, $horiz_border=false, $suffix="", $vert_border=true)
{
        if ($bg_color)
        {
                $color = "bgcolor='#ADAAAD'";
        }

        if ($NUM_COLS>0 && $vert_border)
        {
                echo "<tr height=1 $color><td colspan=".($NUM_COLS*2) ."><img src=images/transp.gif height=1></td></tr>";
        }

        echo "<tr height=9 $suffix>";

                echo "<td><img src=images/transp.gif height=9></td>";

        for ($i=0; $i<$NUM_COLS; $i++)
        {
                echo "<td><img src=images/transp.gif height=9></td>";

                if ($i+1<$NUM_COLS)
                {

                        if ($horiz_border)
                        {
                                DrawChartVisibleBorder(9);
                        }
                        else
                        {
                                echo "<td><img src=images/transp.gif height=9></td>";
                        }
                }
        }

        echo "</tr>";
}

function DrawHR()
{
?>
        <table cellpadding=0 cellspacing=0 border=0 width="100%">
        <tr height=5><td><img src="images/transp.gif"></td></tr>

        <tr height=1><td bgcolor="#CECECE" width="100%" nowrap><img src="images/transp.gif"></td></tr>
        <tr height=1><td bgcolor="white" width="100%" nowrap><img src="images/transp.gif"></td></tr>
        <tr height=5><td><img src="images/transp.gif"></td></tr>
        </table>

<?php
}

function GetFileContents($filename)
{
        $buf = "";

        $size = @filesize($filename);
        $file = @fopen($filename, "r");
        $buf = @fread($file, $size);
        @fclose($file);

        return $buf;
}

function reformat_filesize($Size)
{
        if ($Size == 0)
        {
                return "0K";
        }

        if ($Size < 1024)
        {
                return "1K";
        }
        else
        {
                $Size = $Size / 1024;
                SetType($Size,"integer");
                $Size .= "K";
        }

        return $Size;
}

function DrawImageBestFit($image_location, $width, $height)
{
        global        $DOCUMENT_ROOT;

        $bbox = @GetImageSize($DOCUMENT_ROOT . $image_location);

        if (!empty($bbox))
        {
                $width_orig = $bbox[0];
                $height_orig = $bbox[1];

                GetAspectResize($width_orig, $height_orig, $width, $height, $tn_width, $tn_height);
        }
        else
        {
                $tn_width="";
                $tn_height="";
        }

        DrawImageLink($image_location, "", "", "", 3, $tn_width, $tn_height);
}

function DrawFormImageCell($form, $text, $image_location, $image_empty, $width=0, $height=0, $image_name="new_picture")
{
        global        $MAX_IMAGE_FILESIZE, $DOCUMENT_ROOT;

        if ($width == 0)
        {
                $width = 180;
        }

        if ($height == 0)
        {
                $height = 90;
        }

        $image_location_resized = "image_aspectresize.php?filename_orig=".urlencode($DOCUMENT_ROOT.$image_location).
                                                          "&max_width=$width&max_height=$height&dest_type=PNG";

        if (!empty($image_location))
        {
                $bbox_big = @GetImageSize($DOCUMENT_ROOT . $image_location);
        }

        $image_empty_resized        = "image_aspectresize.php?filename_orig=".urlencode($DOCUMENT_ROOT.$image_empty).
                                                          "&max_width=$width&max_height=$height&dest_type=PNG";

        if (empty($image_location))
        {
                $bbox_big = @GetImageSize($DOCUMENT_ROOT . $image_empty);
        }

        if (!empty($bbox_big))
        {
                $width_orig = $bbox_big[0];
                $height_orig = $bbox_big[1];
                GetAspectResize($width_orig, $height_orig, $width, $height, $tn_width, $tn_height);
        }
        else
        {
                $tn_width="";
                $tn_height="";
        }

?>


        <td nowrap valign="top">
        <SPAN class=TextSmall>
        <?=$text?>&nbsp;&nbsp;
        </SPAN>
        </td>
        <td nowrap valign="top">

        <table border=0 cellspacing=0 cellpadding=0>
        <tr>
        <td valign="top">
        </td>
        <td nowrap valign="top">

        <?php
        if (!empty($image_location))
        {
                DrawImageLink($image_location_resized, $image_location, $text, "", 3, $tn_width, $tn_height);
                echo "</td><td nowrap width=5><img src=images/transp.gif width=5>";
        }
        else
        if (!empty($image_empty))
        {
                DrawImageLink($image_empty_resized, "", $text, "", 3, $tn_width, $tn_height);
                echo "</td><td nowrap width=5><img src=images/transp.gif width=5>";

        }
        ?>
        </td>

        <?php

        // Skip to next line if image too long
//        if ($width > 180)
//        {
//                echo "</tr><tr>";
//        }
        ?>

        <td nowrap valign="top">

        <input type=file name='<?=$image_name?>' size=13> <input type=button name=preview value="Preview" onclick="Javascript:OnImagePreview('<?=$form?>','<?=$image_name?>');"><BR>
        <SPAN class=TextTiny>
        Image properties: <?=$MAX_IMAGE_FILESIZE/1000?>Kb maximum size. <br>Supported formats: JPEG, PNG<br>Type "none" and Save Changes for no image
        <INPUT TYPE=HIDDEN NAME=<?=$image_name?>_image VALUE="">
        </SPAN>
        <br>

        </td></tr>
        <tr>
        <td colspan=4>
        </td>
        </tr>
        </table>

        </td>


<?php
}


function DrawImageLink($src, $src_big, $title, $align="middle", $border=0, $width, $height)
{
        global $IEBROWSER, $DOCUMENT_ROOT;

        $bbox_big = @GetImageSize($DOCUMENT_ROOT . $src_big);
        $width_big = $bbox_big[0];
        $height_big = $bbox_big[1];

        // Remove trailing ':'
        if (!empty($title))
        if ($title[strlen($title)-1]==":")
        {
                $title = substr($title, 0, strlen($title)-1);
        }

        // For netscape, we need to manually increase width/height to accomodate for
        // scrollbars
        if ($IEBROWSER != true)
        {
                $width_big += 20;
                $height_big += 30;
        }

        // Upon click, we will open the image in a new window
        $url = "Javascript:popUpImage('$src_big','".urlencode($title)."', $width_big, $height_big)";

        // If we have a URL for the big image
        if (!empty($src_big))
        {
                if ($IEBROWSER)
                {
                        echo "<a href=\"$url\"\n";
                }
                else
                {
                        echo "<a href=\"javascript:void(null);\" ";
                }

                echo "onMouseOver=\"window.status='$title'; return true;\" onClick=\"window.status=''; return true;\" onMouseOut=\"window.status=''; return true;\" ";
                if (!$IEBROWSER)
                {
                        echo "onclick=\"$url\"";
                }
                echo " title='Click to Zoom'>";
        }

        // Output the image itself
        empty($align) ? $align_str = "" : $align_str = "align=$align";
        echo "<img $align_str border=0 src=$src ";

        if (!empty($width) && !empty($height))
        {
                echo "width=$width height=$height";
        }
        echo ">";

        // If we have a URL for the big image
        if (!empty($src_big))
        {
                echo "</a>";
        }
}

function GetChartFilter($chartName)
{
        global        $PREF_FILTER, $filter;

        $filter                                = preferenceDeclare($chartName, $PREF_FILTER, "");
}

function GetChartPreferences($chartName, $defChartSort, $count = -1)
{
        global        $start_from_page, $sort_ascending, $sort_by_field, $filter;
        global        $MaxPageNum, $ROWS_PER_PAGE, $HTTP_GET_VARS;
        global        $PREF_START_FROM_PAGE, $PREF_SORT_ASCENDING, $PREF_SORT_BY_FIELD, $PREF_FILTER;

        $start_from_page        = preferenceDeclare($chartName, $PREF_START_FROM_PAGE, "1");
        $sort_ascending                = preferenceDeclare($chartName, $PREF_SORT_ASCENDING, "1");
        $sort_by_field                = preferenceDeclare($chartName, $PREF_SORT_BY_FIELD, $defChartSort);

        // If we don't have a new start_from_page, reset it
        if (empty($HTTP_GET_VARS['start_from_page']))
        {
                preferenceSet($chartName, $PREF_START_FROM_PAGE, "1");
                $start_from_page = 1;
        }

        // If we received the number of record
        // Do some validations
        if ($count >= 0)
        {
                // Get max number of pages
                $MaxPageNum = getNumPages($count, $ROWS_PER_PAGE);

                // Validate
                if ($start_from_page < 1)
                {
                        $start_from_page = 1;
                }
                if ($start_from_page > $MaxPageNum)
                {
                        $start_from_page = $MaxPageNum;
                }
        }
}

function IsFramed()
{
        global        $PREF_GLOBAL, $PREF_FRAMES, $use_frames;

        return $use_frames;
}

function reformat_shortfiletype($type)
{
        if ($type[0]=='.')
        {
                $type = substr($type, 1, strlen($type)-1);
        }

        $type = strtolower($type);

        if (Strcasecmp($type,"jpeg")==0)
        {
                return "jpg";
        }
        else
        if (Strcasecmp($type,"pjpeg")==0)
        {
                return "jpg";
        }
        else
        if (Strcasecmp($type,"x-png")==0)
        {
                return "png";
        }
        else
        if (Strcasecmp($type,"jif")==0)
        {
                return "jpg";
        }
        else
        {
                return $type;
        }
}

function FileGetType($filename)
{
        $Type = "";

        if (($ext_pos = _strrpos($filename ,".")) != -1)
        {
                $Type = Substr($filename, $ext_pos+1);
        }

        return $Type;
}

function FileGetName($filename)
{
        $Name = $filename;

        if (($ext_pos = _strpos($filename ,".")) != -1)
        {
                $Name = substr($filename, 0, $ext_pos-1);
        }

        return $Name;
}

function GetAspectResize($width, $height, $max_width, $max_height, &$tn_width, &$tn_height)
{
        // Get aspect ratio
        $x_ratio        = $max_width / $width;
        $y_ratio        = $max_height / $height;

        // If current width/height smaller than max,
        if ( ($width <= $max_width) && ($height <= $max_height) )
        {
                // Use given width/height
                $tn_width        = $width;
                $tn_height        = $height;
        }
        // (Else - current width/height bigger than max)
        else
        // If height is smaller
        if ( ($x_ratio * $height) < $max_height )
        {
                $tn_height        = ceil($x_ratio * $height);
                $tn_width        = $max_width;
        }
        else
        {
                $tn_width        = ceil($y_ratio * $width);
                $tn_height        = $max_height;
        }
}

// Resize image, maintaining aspect ratio
function ImageAspectResize($filename_orig, $filename_dest, $max_width, $max_height, $dest_type="jpg", $orig_type="")
{
        global $DOCUMENT_ROOT, $GD2INSTALLED;

        if (empty($orig_type))
        {
                $orig_type = FileGetType($filename_orig);
        }

        // Set target type
        switch (strtolower($dest_type))
        {
                // Okay
                case "jpg":
                case "png":
                break;

                // Invalid type - default to JPG
                default:
                        $dest_type = "jpg";
                break;
        }

        // Load original image
        switch (reformat_shortfiletype(basename($orig_type)))
        {
                case "jpg":
                        $Image = ImageCreateFromJPEG($filename_orig);
                break;

//                case "gif":
//                        $Image = ImageCreateFromPNG($filename_orig);
//                break;

                case "png":
                        $Image = ImageCreateFromPNG($filename_orig);
                break;

                default:
                        return false;
                break;
        }

        // If error occured,
        if (empty($Image))
        {
                return false;
        }

        // Get original image size
        $size        = GetImageSize($filename_orig);
        $width        = $size[0];
        $height        = $size[1];

        // Get aspect ratio
        $x_ratio        = $max_width / $width;
        $y_ratio        = $max_height / $height;

        // If current width/height smaller than max,
        if ( ($width <= $max_width) && ($height <= $max_height) )
        {
                // Use given width/height
                $tn_width        = $width;
                $tn_height        = $height;
        }
        // (Else - current width/height bigger than max)
        else
        // If height is smaller
        if ( ($x_ratio * $height) < $max_height )
        {
                $tn_height        = ceil($x_ratio * $height);
                $tn_width        = $max_width;
        }
        else
        {
                $tn_width        = ceil($y_ratio * $width);
                $tn_height        = $max_height;
        }

        // Create destination JPEG
        $DestImage = ImageCreateTrueColor($tn_width, $tn_height);

        // Resize
if ($GD2INSTALLED)
{
        ImageCopyResampled($DestImage, $Image, 0, 0, 0, 0, $tn_width, $tn_height,
                $width, $height);
}
else
{
        ImageCopyResized($DestImage, $Image, 0, 0, 0, 0, $tn_width, $tn_height,
                $width, $height);
}

        // Cleanup source
        ImageDestroy($Image);

        // Output to file
        switch ($dest_type)
        {
                case "jpg":
                        if (!empty($filename_dest))
                        {
                                ImageJPEG($DestImage, $filename_dest, -1);
                        }
                        else
                        {
                                ImageJPEG($DestImage, null, -1);
                        }
                break;

                default:
                        if (!empty($filename_dest))
                        {
                                ImagePNG($DestImage, $filename_dest);
                        }
                        else
                        {
                                ImagePNG($DestImage);
                        }
                break;
        }

        // Cleanup dest
        ImageDestroy($DestImage);

        // Return success
        return true;
}

// This method verifies that this is not an upload attack
// (User trying to trick us by uploading /etc/passwd)
function IsUploadedFile($filename)
{
        if (!$tmp_file = get_cfg_var('upload_tmp_dir'))
        {
                $tmp_file = dirname(tempnam('',''));
        }

        $tmp_file .= '/' . basename($filename);

        for ($i=0; $i<strlen($filename); $i++)
        {
                if ($filename[$i] == '\\')
                {
                        $filename[$i] = '/';
                }
        }
        for ($i=0; $i<strlen($tmp_file); $i++)
        {
                if ($tmp_file[$i] == '\\')
                {
                        $tmp_file[$i] = '/';
                }
        }

        /* User might have trailing slash in php.ini... */
        return (Strcasecmp(ereg_replace('/+','/',$tmp_file), $filename) == 0);
}


function ValidateFileUpload($new_picture, $username, $allowed_types = array("jpg","png"))
{
        global        $MAX_IMAGE_FILESIZE, $DOCUMENT_ROOT;

        // Init
        $ActionResult = "";

        // If no picture provided,
        if (empty($username))
        {
                // We're done
                return $ActionResult;
        }

        do
        {
                // Make sure file exists
                if (!file_exists($new_picture) ||
                        filesize($new_picture) == 0)
                {
                        $ActionResult = "Error in uploading file ($username).";
                        break;
                }

                // Validate size
                if (filesize($new_picture) > $MAX_IMAGE_FILESIZE)
                {
                        $ActionResult = "File uploaded is too big. (".($MAX_IMAGE_FILESIZE/1000) ."Kb maximum)";
                        break;
                }

                // Possible upload attack?
//                if (!IsUploadedFile($new_picture))
//                {
//                        $ActionResult = "Possible upload attack.";
//                        break;
//                }

                // Validate type
                if (!empty($allowed_types))
                {
                        if (!isStringInArray(reformat_shortfiletype(FileGetType($new_picture)), $allowed_types))
                        {
                                $ActionResult = "Invalid file type uploaded (Supported formats: ";
                                for ($i=0; $i<count($allowed_types); $i++)
                                {
                                        $ActionResult .= $allowed_types[$i];
                                        if ($i+1<count($allowed_types))
                                        {
                                                $ActionResult .= ", ";
                                        }
                                }
                                $ActionResult .= ")";
                                break;
                        }
                }

        } while (false);

        return $ActionResult;
}

function ImgTagResize($name, $src, $border, $max_width, $max_height, $empty, $enlarge=true,$align="")
{
        global        $DOCUMENT_ROOT;

        // If no image source provided,
        if (empty($src))
        {
                // Use empty image
                $src = $empty;

                // No max width/height limitations on empty image
                $max_width        = 9999;
                $max_height        = 9999;

                $EMPTY_SRC = true;
        }

        // Get image dimensions
        $bbox = GetImageSize($GLOBALS["DOCUMENT_ROOT"] . $src);
        $real_width = $bbox[0];
        $real_height = $bbox[1];

        $ImgSrc = "/image_aspectresize.php?filename_orig=".urlencode($DOCUMENT_ROOT.$src).
        "&max_width=$max_width&max_height=$max_height&dest_type=PNG";

        $url = "Javascript:popUpWin('/show_image.php?src=$src&title=$name', $real_width, $real_height)";

        if (!empty($align))
        {
                $align_str="align=$align";
        }
        else
        {
                $align_str="";
        }
        if (!isset($EMPTY_SRC) && $enlarge) echo "<a href=\"$url\" title=\"Click to enlarge\">";
        echo "<img src=\"$ImgSrc\" name=\"$name\" border=$border bordercolor=black $align_str>";
        if (!isset($EMPTY_SRC) && $enlarge) echo "</a>";
}

function ImgTag($name, $src, $border=0, $params="", $force_no_size = false)
{
        global        $DOCUMENT_ROOT;

        // Validate
        if (empty($src))
        {
                return;
        }

        // Get original image size
        $size        = GetImageSize($DOCUMENT_ROOT.$src);
        $width        = $size[0];
        $height        = $size[1];

        echo "<img src=\"$src\" ";
        if (!empty($name))
        {
                echo "name=\"$name\" ";
        }

        if (!$force_no_size)
        {
                echo "width=$width height=$height ";
        }

        echo "border=$border $params>";
}

/*function stri_replace($searchFor, $replaceWith, $string, $offset = 0) {
$lsearchFor = strtolower($searchFor);
$lstring = strtolower($string);
$newPos = strpos($lstring, $lsearchFor, $offset);
if (strlen($newPos) == 0) {
return($string);
} else{
$left = substr($string, 0, $newPos);
$right = substr($string, $newPos + strlen($searchFor));
$newStr = $left . $replaceWith . $right;
return stri_replace($searchFor, $replaceWith, $newStr, $newPos + strlen($replaceWith));
}
}
*/
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

function DrawWizardSteps ($ArraySteps, $OnCancel, $CurrentStep, $AllowJumps=false)
{
        global        $rr_redirect0, $rr_redirect1, $rr_redirect_cancel, $LAST_WIZARD_STEP, $rr_redirect1_resultstr, $rr_redirect_back;

        $rr_redirect_cancel                = $OnCancel;
        $rr_redirect1_resultstr        = "";

        $i = 0;
        foreach ($ArraySteps as $Name => $URL)
        {
                $i++;
                DrawWizardStep($i, $Name, $i == $CurrentStep, $AllowJumps ? $URL : "");

                // If this is the previous step
                if ($i == $CurrentStep-1)
                {
                        // Set redirects
                        $rr_redirect_back                = $URL;
                }

                // If current step
                if ($i == $CurrentStep)
                {
                        // Set redirects
                        $rr_redirect0                        = $URL;
//                        $rr_redirect1                        = $URL;
                }

                // If this is the next step
                if ($i == $CurrentStep+1)
                {
                        // Set redirects
                        $rr_redirect1                        = $URL;
                }
        }

        // If we are on the last step
        if (($i == $CurrentStep) ||
            (($i == $CurrentStep+1) && ($Name=="") ) )
        {
                // Remember it
                $LAST_WIZARD_STEP = true;
        }

        echo "<br><br>";
}


function PrintFormResultIfNeeded($AddBR = false, $_ResultStr="", $_result=-1)
{
        global $ResultStr, $result;

        if ($_ResultStr)        $ResultStr        = $_ResultStr;
        if ($_result!=-1)        $result                = $_result;

        if (empty($ResultStr))
        {
                return;
        }

        if ($AddBR) echo "<SPAN class=TextTiny><BR></SPAN>";
?>
        <table width="100%" border=0 cellspacing=0 cellpadding=1 bgcolor="#aaaaaa">
        <tr>
        <td>

                <table width="100%" border=0 cellspacing=0 cellpadding=4 bgcolor="#ffffcc">
                <tr>
                        <td><?php

                        if (empty($result))
                        {
                                ImgTag("","images/iconinformation.gif","", "align=absmiddle");
                        }
                        else
                        {
                                ImgTag("", "images/checkmark_big.gif", "","align=absmiddle");
                        }
                        ?></td>
                        <td><img src="images/transp.gif" width=5 height=1></td>
                        <td width="100%" class="pperrorbold"><?=_stripslashes(urldecode($ResultStr));?></td>
                </tr>
                </table>

        </td>
        </tr>
        </table>
<?php

        echo "<br>";
}

function PrintPageDescription($Text, $full=true)
{
        if ($full)
        {
                $width = "100%";
        }
        else
        {
                $width = "650";
        }
?>
<table border=0 width="<?=$width?>" cellspacing=0 cellpadding=0>
<tr>
<td><SPAN class=TextMedium><?=$Text?><BR><BR></SPAN></td>
</tr>
</table>

<?php
}

function PrintPageLeftHeader($Text)
{
?>
  <tr height=1>
  <td width=1 colspan=3 nowrap bgcolor="#FFFFFF"><img src="images/transp.gif"></td>
  <td width=1 nowrap bgcolor="#ACA899"><img src="images/transp.gif"></td>
  </tr>
  <tr height=3>
  <td width=1 nowrap bgcolor="#FFFFFF"><img src="images/transp.gif"></td>
  <td width=1 colspan=2 nowrap ><img src="images/transp.gif"></td>
  <td width=1 nowrap bgcolor="#ACA899"><img src="images/transp.gif"></td>
  </tr>
  <tr height=10>
  <td width=1 nowrap bgcolor="#FFFFFF"><img src="images/transp.gif"></td>
  <td width="100%" align=center><SPAN class=TextSmall><?=$Text?></SPAN></td>
  <td width=4 nowrap ><img src="images/transp.gif"></td>
  <td width=1 nowrap bgcolor="#ACA899"><img src="images/transp.gif"></td>
  </tr>
  <tr height=3>
  <td width=1 nowrap bgcolor="#FFFFFF"><img src="images/transp.gif"></td>
  <td width=1 colspan=2 nowrap ><img src="images/transp.gif"></td>
  <td width=1 nowrap bgcolor="#ACA899"><img src="images/transp.gif"></td>
  </tr>
  <tr height=1>
  <td width=1 colspan=3 nowrap bgcolor="#ACA899"><img src="images/transp.gif"></td>
  <td width=1 nowrap bgcolor="#ACA899"><img src="images/transp.gif"></td>
  </tr>
<?php
}

function PrintPageStart($full = true)
{
        global $INTERNAL_PAGE, $INSIDE_FRAME;
        global $ACCOUNT_TYPE, $ACCOUNT_DTF, $PAGE_START;

        $PAGE_START = true;

        $HEIGHT_STR = "";
        if (IsFramed())
        {
                $HEIGHT_STR = "height=\"100%\"";
        }

//        $BG = "#EFEDDE";
        $BG = "#F7F7F7";

        $width_str = "";
        if (empty($INTERNAL_PAGE) && $full) $width_str = "width=100%";

?><table cellspacing=0 cellpadding=0 border=0 align=center <?=$width_str?> <?=$HEIGHT_STR?>><tr >

  <TD valign="top" align="center" <?=$width_str?> >

  <?php
  if (empty($ACCOUNT_TYPE))
  {
        $ACCOUNT_TYPE = GetAccountTypeByServerName();
  }
  if ($ACCOUNT_TYPE == $ACCOUNT_DTF)
  {
  ?>
        <table cellspacing=0 cellpadding=0 border=0 width="100%"><tr><td width=10 nowrap><img src=images/transp.gif></td>
        <td valign="top" align="center" <?=$width_str?> >
  <?php
  }
  ?>

  <SPAN class="TextTiny"><BR></SPAN>
<?php

}

function GetAccountID()
{
        global $INTERNAL_PAGE, $login_account_id, $account_id, $LoginRequired, $DREAMTIME_ACCOUNT;

        if (isset($LoginRequired) && !isset($INTERNAL_PAGE))
        {
                if (!empty($DREAMTIME_ACCOUNT))
                {
                        return $DREAMTIME_ACCOUNT;
                }

                if (session_is_registered('login_account_id'))
                {
                        return $login_account_id;
                }
        }
        else
        {
                return $account_id;
        }

        return "";
}

function InputDisabled($name, $value, $tiny=false)
{
        global        $IEBROWSER;

        if (empty($size))
        {
                $size_str = "style=\"width:100%\"";
        }
        else
        {
                $size_str = "size=$size";
        }

        if ($tiny)
        {
                $class = "InputBoxGrayTiny";
        }
        else
        {
                $class = "InputBoxGray";
        }

        // IE
        if (1)//$IEBROWSER)
        {
        ?>
                <input class="<?=$class?>"  readonly     value="<?=$value?>">
        <?php
        }
        else
        {
                echo "<SPAN class=\"InputBoxGray\">$value</SPAN>";
        }

        if (!empty($name))
        {
                echo "\n<input type=hidden name=\"$name\"  value=\"$value\">\n";
        }
}

function ArrayToFormInputs($Array, $Field="")
{
        foreach ($Array as $Name => $Value)
        {
                if (empty($Field) ||
                    Strcasecmp($Field,$Name)==0)
                {
                        // Echo input
                        echo "<INPUT TYPE=HIDDEN NAME=\"$Name\" VALUE=\"".urlencode($Value)."\">\n";
                }
        }
}

function ArrayIgnoreFieldsArray($Array, $Array_ignore="")
{
        global        $STR_IGNORETHISFIELD;

        $cnt = 0;

        foreach ($Array as $Name => $Value)
        {
                if (strlen($Name)>3)
                if (Strcasecmp(substr($Name, 0, 3), "rr_")==0)
                {
                        // Skip - internal field
                        continue;
                }

                // If numeric array index,
                if (Strcasecmp($Name, $cnt)==0)
                {
                        // Ignore
                        $cnt++;
                        continue;
                }

                if (!isStringInArray($Name,$Array_ignore))
                {
                        // Echo input
                        echo "<INPUT TYPE=HIDDEN NAME=\"$Name\" VALUE=$STR_IGNORETHISFIELD>\n";
                }
        }
}

function ArrayIgnoreFields($Array, $Field_ignore="")
{
        global        $STR_IGNORETHISFIELD;

        $cnt = 0;

        foreach ($Array as $Name => $Value)
        {
                if (strlen($Name)>3)
                if (Strcasecmp(substr($Name, 0, 3), "rr_")==0)
                {
                        // Skip - internal field
                        continue;
                }

                // If numeric array index,
                if (Strcasecmp($Name, $cnt)==0)
                {
                        // Ignore
                        $cnt++;
                        continue;
                }

                if (Strcasecmp($Field_ignore,$Name)!=0)
                {
                        // Echo input
                        echo "<INPUT TYPE=HIDDEN NAME=\"$Name\" VALUE=$STR_IGNORETHISFIELD>\n";
                }
        }

}

function ArrayToFormInputsNOT($Array, $Field="")
{
        $cnt = 0;

        foreach ($Array as $Name => $Value)
        {
                if (strlen($Name)>3)
                if (Strcasecmp(substr($Name, 0, 3), "rr_")==0)
                {
                        // Skip - internal field
                        continue;
                }

                // If numeric array index,
                if (Strcasecmp($Name, $cnt)==0)
                {
                        // Ignore
                        $cnt++;
                        continue;
                }

                if (Strcasecmp($Field,$Name)!=0)
                {
                        // Echo input
                        echo "<INPUT TYPE=HIDDEN NAME=\"$Name\" VALUE=\"".urlencode($Value)."\">\n";
                }
        }
}

function DrawRecordInfo($name, $record, $add_td = false)
{
        global        $policy_admin;

        // If not administrator
        if (empty($policy_admin))
        {
                // We're done
        //        return;
        }

        $RecordID        = ( isset($record['rr_record_id_wizard'])        ? $record['rr_record_id_wizard'] : arrayvalue($record,'rr_record_id') );
        $CreateDate        = ( isset($record['rr_createdate_wizard'])        ? $record['rr_createdate_wizard'] : arrayvalue($record,'rr_createdate') );
        $LastAccess        = ( isset($record['rr_lastaccess_wizard'])        ? $record['rr_lastaccess_wizard'] : arrayvalue($record,'rr_lastaccess') );

        if (empty($RecordID) || empty($CreateDate) || empty($LastAccess))
        {
                return;
        }

?>
<tr>
        <td></td>
<?php if ($add_td) echo "<td></td>"; ?>
        <td nowrap>

        <SPAN class=TextSmall>
        <?=$name?> ID:&nbsp;&nbsp;
        </SPAN>
        </td>
        <td nowrap>

        <table border=0 cellspacing=0 cellpadding=0 width="400">
        <tr>


        <td >
        <?php InputDisabled("", $RecordID, true); ?>
        </td>


<?php
$tmp = $CreateDate;
if (!empty($tmp) && Strcasecmp($tmp,"0000-00-00")!=0)
{
?>
        <td align=right>
                <table cellspacing=0 cellpadding=0 border=0>
                <tr>
                <td nowrap>
                <SPAN class=TextSmall>
                Date Created:&nbsp;&nbsp;
                </SPAN>
                </td>
                <td>
                <?php InputDisabled("", reformat_date($CreateDate), true); ?>
                </td>
                </tr>
                </table>
        </td>
<?php
}
?>
        </tr>
        </table>
        </td>
</tr>

<tr>
<?php if ($add_td) echo "<td></td>"; ?>
        <td></td>
        <td nowrap>
        <SPAN class=TextSmall>
        Last Modified:&nbsp;&nbsp;
        </SPAN>
        </td>
        <td nowrap>
        <?php InputDisabled("", reformat_timestamp($LastAccess)); ?>
        </td>
</tr>
<?php
}

function FormParameters($Form, $RecordName, $Form_Record, $record_id, $redirect1, $redirect0, $redirect0dup_str="")
{
        global        $rr_redirect1, $rr_redirect0, $rr_redirect1_resultstr, $rr_redirect0_resultstr, $account_id, $rr_redirectdup_resultstr;

        // If we have global variables, override locals
        if (isset($rr_redirect1))                                $redirect1                = $rr_redirect1;
        if (isset($rr_redirect0))                                $redirect0                = $rr_redirect0;

        // Output
        if (isset($rr_redirect1_resultstr)) echo "<input type=hidden name=rr_redirect1_resultstr value=\"$rr_redirect1_resultstr\">\n";
        echo "<input type=hidden name=rr_redirect0_resultstr value=\"$rr_redirect0_resultstr\">\n";
        echo "<input type=hidden name=rr_redirect0dup_resultstr value=\"$redirect0dup_str\">\n";
        if (!empty($Form_Record))                        echo "<input type=hidden name=rr_recordfield value=\"$Form_Record\">\n";
        if (!empty($RecordName))                        echo "<input type=hidden name=rr_recordname value=\"$RecordName\">\n";

        if (isset($account_id)) echo "<input type=hidden name=rr_account_id value=$account_id>\n";
        if (!empty($record_id)) echo "<input type=hidden name=rr_record_id value=$record_id>\n";

?>

<input type="hidden" name="rr_action" value="update">
<input type="hidden" name="rr_formid" value="<?=$Form?>">

<input type="hidden" name="rr_redirect1" value="<?=$redirect1?>">
<input type="hidden" name="rr_redirect0" value="<?=$redirect0?>">

<?php
}

function SafeGet($A, $B="")
{
        if (isset($A))
        {
                return $A;
        }

        return $B;
}

function NonEmpty($A, $B)
{
        if (!empty($A))
        {
                return $A;
        }

        return $B;
}

function DrawStatusBar()
{
?>

<table cellspacing=0 cellpadding=0 border=0 width="100%" height=25 background="images/navigationbg1.gif">
 <tr height=1 bgcolor="#808080" background="">
 <td colspan=3 height=1><img src="images/transp.gif"></td>
 </tr>

<tr>
<td width="100%">
<SPAN class=TextSmall>&nbsp;<font color=#336699>Status:</font> All systems go</SPAN>
</td>
<td>
<table cellspacing=0 cellpadding=0 border=0>
<tr height=21>
<td nowrap width=1 bgcolor="#919B9C" background="">
<img src="images/transp.gif">
</td>
<td nowrap width=9>
<img src="images/transp.gif">
</td>
<td>
<a class="LinkSmall" onfocus="blur();" href="<?=GetSystemLogoutPage()?>?Reason=OnRequest" onMouseOver="window.status='Logout'; return true;" onClick="window.status=''; return true;" onMouseOut="window.status=''; return true;" target="_top"><b>Logout</b></a>&nbsp;&nbsp;
</td>
</tr>
</table>
</td></tr>


</table>

<?php
}

function GetRestrictedFieldName($Str)
{
        if (Strcasecmp($Str,"rr_lastaccess")==0)
        {
                return "Last Access";
        }
        else
        if (Strcasecmp($Str,"rr_record_id")==0)
        {
                return "Record ID";
        }

        // Not found - return original
        return $Str;
}

function DrawNavigationBar($Folder, $Navigation, $BottomBorder = false, $Force = false)
{
        global        $CurrentFolder, $PageName, $INTERNAL_PAGE, $BOTTOM_FRAME, $LoginRequired;
        global        $ACCOUNT_TYPE, $ACCOUNT_CHAT;

        if (isset($INTERNAL_PAGE))
        {
                return;
        }

        // Draw folders
        if (isset($LoginRequired))
        {
                DrawFolders($Folder);
        }
        else
        {
                if ($ACCOUNT_TYPE==$ACCOUNT_CHAT)
                {
                        DrawFolders($Folder,false);
                }
                else
                {
                        DrawWebsiteFolders($Folder);
                }
        }

        // Get last navigation name
        $LastNavigationName = "";
        foreach ($Navigation as $Name => $URL)
        {
                $LastNavigationName = $Name;
        }

        // If not more than one step on navigation bar,
        // Or empty navigation
        if (Strcasecmp($PageName, $LastNavigationName)==0 ||
            empty($Navigation))
        {
                // We're done
                return;
        }

        echo        "<center><table cellspacing=0 cellpadding=3 border=0  width=670>";

                echo        "<tr><td nowrap><img src=\"images/smhere.gif\" align=absbottom><SPAN class=TextTiny>&nbsp;</span>";

//                if (isset($CurrentFolder))
//                {
//                        $Navigation = array_merge(array($CurrentFolder => ""), $Navigation);
//                }

        // Loop drawing items
        $i = 0;
        $count = count($Navigation);
        foreach ($Navigation as $Name => $URL)
        {
                $IsCurrent = ( ++$i == $count );

                echo        "&nbsp;<SPAN class=LinkSmallBlack>";
                if ($URL!="")
                {
                        echo        "<a class=LinkSmallBlack href=\"";
                        echo        $URL;        // Link
                        echo        "\">";
                }

                if ($IsCurrent)        echo "<b>";                        // Bold if last item on navigation bar

                // Print item
                echo        stripslashes($Name);

                if ($IsCurrent) echo "</b>";                // Unbold

                if ($URL!="")
                {
                        echo        "</a>";
                }
                echo        "</SPAN> <font size=\"-2\" face=Verdana color=\"#336699\">>></font>";
        }

        // Draw navigation bottom
        echo        "</td>"; //<td nowrap align=\"right\" width=\"98%\">";

//        echo        "<SPAN class=TextSmall><font color=\"#336699\">";
//        echo date("l, F jS, Y");
//        echo        "</font></SPAN></td></tr></table>";
        echo        "</tr></table></center>";
}

function TrimCRLF($Str)
{
        $Str = stri_replace("\r","",$Str);

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
                // If this is NOT \n or it's a space but the next character is NOT  a \n
                if ($Str[$i]!="\n" ||
                    ($Str[$i]=="\n" && $Str[$i+1]!="\n") ||
                        ($Str[$i]=="\n" && $Str[$i+1]=="\n" && $Str[$i+2]!="\n"))
                {
                        // Grab it
                        $NewStr .= $Str[$i];
                }
        }

        return $NewStr;
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

function GetLinkTag($Name, $Link, $Length = 30, $Title = "", $Suffix = "", $LinkClass="LinkSmall", $EmptyStr="<Empty>")
{
        if (empty($Title))
        {
                $Title = htmlspecialchars(strcut($Name, 100));
        }

        $Title = stri_replace(htmlspecialchars("&#160;")," ", $Title);
        $Title = TrimAllSpaces($Title);

        $Result = "";

        if (!empty($Link))
        {
                $Result .= "<a class=$LinkClass title=\"".$Title."\" href=\"".$Link."\">";
        }
        else
        {
                $Result .= "<span class=$LinkClass>";
        }

        $Result .= NonEmpty(strcut($Name, $Length).$Suffix, htmlspecialchars($EmptyStr));

        if (!empty($Link))
        {
                $Result .= "</a>";
        }
        else
        {
                $Result .= "</span>";
        }

        return $Result;
}

function URLRemoveParameter($Parameters, $Remove)
{
        $Prefix = "";
        $ResultStr = "";

        // If first character is '?'
        if (($start = _strpos($Parameters,"?")) != -1)
        {
                $Prefix .= substr($Parameters, 0, $start+1);

                // Remove it
                $Parameters = substr($Parameters, $start+1);
        }

        do
        {
                if (($next = _strpos($Parameters,"&")) == -1)
                {
                        $next = strlen($Parameters);
                }

                if (($separator = _strpos($Parameters,"=")) != -1)
                {
                        $Name        = trim(substr($Parameters, 0, $separator));
                        $Value        = (substr($Parameters, $separator+1, max(0,$next-($separator+1))));
/*                        $Name        = trim(substr($Parameters, 0, $separator));
//                        if ($next != -1)
                                $Value        = substr($Parameters, $separator+1, max(0,$next-($separator+1)));
//                        else
//                                $Value        = substr($Parameters, $separator+1);
*/
                        // Is this the one we are looking for?
                        if (Strcasecmp($Name,$Remove)!=0)
                        {
//                                echo "adding: $Name = $Value [$ResultStr] <BR>";

                                // Add
                                if (!empty($ResultStr)) $ResultStr .= "&";
                                $ResultStr .= $Name . "=" . $Value;
                        }
                }
                else
                {
                        break;
                }

                $Parameters = substr($Parameters, $next+1, strlen($Parameters) - ($next+1));
/*
                if ($next != -1 && $separator != -1)
                        $Parameters = substr($Parameters, $next+1);
                else
                        $Parameters = "";
*/
        } while (true);

        // Return result
        return $Prefix . $ResultStr . $Parameters;
}

function URLCat($URL)
{
        if (_strpos($URL,"?")!=-1 || _strpos($URL,"&")!=-1)
        {
                // If last character is a '?'
                if (strlen($URL)>0)
                if ($URL[strlen($URL)-1]=="?")
                {
                        // We're done
                        return $URL;
                }

                // Otherwise
                return $URL."&";
        }

        return $URL."?";
}

function printReadError($Name, $id, $inside_chart = true)
{
        echo "<SPAN class=TextMedium><font color=red><b>Failed reading ".$Name." ID:".$id. ". Seems like it was deleted.</b></font></SPAN><br><br>";

        if ($inside_chart)
        {
                echo "</td></tr>";
        }
}

function IsHTMLFile($FileName)
{
        // Get file type
        $Type = reformat_shortfiletype(FileGetType($FileName));

        // Return result
        return isStringInArray($Type, array("htm","html"));

}

function IsCSSFile($FileName)
{
        // Get file type
        $Type = reformat_shortfiletype(FileGetType($FileName));

        // Return result
        return isStringInArray($Type, array("css"));

}

function HTMLToText($Str)
{
        $Str = TrimCRLF($Str);
        $Str = TrimAllSpaces($Str);
        $Str = str_replace("\r", "", $Str);
        $Str = str_replace("\n", "", $Str);
        $Str = stri_replace("<BR>", "\n", $Str);
        $Str = stri_replace("<p>", "\n\n", $Str);
        $Str = stri_replace("</td>", " ", $Str);
        $Str = stri_replace("&nbsp;", " ", $Str);
        $Str = stri_replace("&#160;", "", $Str);
        $Str = strip_tags($Str);
        $Str = TrimCRLF($Str);
        $Str = TrimAllSpaces($Str);

        $Str = str_replace('"', "&quot;", $Str);

        return $Str;
}

function IsImageFile($FileName)
{
        // Get file type
        $Type = reformat_shortfiletype(FileGetType($FileName));

        // Return result
        return isStringInArray($Type, array("jpg","gif","png","bmp"));
}

function SingularPlural($i)
{
        if ($i!=1) return "s";
        return "";
}

function SingularBGColor($i, $color="#EFEFEF")
{
        if ($i % 2 == 1)
        {
                return " bgcolor='$color' ";
        }
}

function StringWrap($font, $text, $maxwidth, $maxlines)
{
   $fontwidth = ImageFontWidth($font);
   $fontheight = ImageFontHeight($font);

        $Str = "";

        $cntlines        = 0;

   if ($maxwidth != NULL)
   {
           $maxcharsperline = floor($maxwidth / $fontwidth);
           $text = wordwrap($text, $maxcharsperline, "\n", 1);
   }

        $lines = explode("\n", $text);

        while (list($numl, $line) = each($lines))
        {
                if (!empty($Str))
                        $Str .= "\n";
                $Str .= $line;

                $cntlines++;
                if ($cntlines>=$maxlines)
                {
                        break;
                }

        }

        return $Str;
}

function DrawButtonLine($Form, $OnOK="", $OnCancel="", $OnDelete="", $IsEdit=true, $cols=5, $ADD_BUTTON_NAME="")
{
        global $INTERNAL_PAGE, $LAST_WIZARD_STEP, $STR_BACK, $STR_NEXT, $rr_redirect1, $rr_redirect0, $rr_redirect_back, $rr_redirect_cancel, $INSIDE_FRAME;

//        if (isset($rr_redirect0))                $OnCancel        = $rr_redirect0;
        if (isset($rr_redirect_cancel))        $OnCancel        = $rr_redirect_cancel;

        if (!isset($STR_BACK))
        {
                $STR_BACK = "Back";
        }

        if (!isset($STR_NEXT))
        {
                $STR_NEXT = "Next";
        }

        if (empty($ADD_BUTTON_NAME))
        {
                $ADD_BUTTON_NAME = "&nbsp;&nbsp;&nbsp;Add&nbsp;&nbsp;&nbsp;";
        }

        if ($cols==-1)
        {
                $cols = 5;
        }

{
?>
        <tr height=5><td ><img src="images/transp.gif"></td></tr>
<?php
}

if (isset($INTERNAL_PAGE))
{
?>
<tr>
        <td colspan=<?=$cols?> width="100%">
        <?php DrawHR(); ?>
        </td>
</tr>

        <tr>
                <td width="100%" colspan=<?=$cols?>>
                <table cellspacing=0 cellpadding=0 border=0 width="100%">
                <tr>
                <td>
                <?php
                if ($IsEdit && !empty($OnDelete))
                {
//                        DrawXPButton ('Delete','Delete', "$OnDelete");
                }
                ?>
                </td>
                <td width="100%"></td>
                <td nowrap width=10><img src="images/transp.gif"></td>
                <td >
                <?php DrawXPButton("&nbsp;&nbsp;<< $STR_BACK&nbsp;&nbsp;",'Back', "$rr_redirect_back"); ?>
                </td>
                <td>
                <?php DrawXPButton(isset($LAST_WIZARD_STEP) ? '&nbsp;&nbsp;Finish&nbsp;&nbsp;' : "&nbsp;&nbsp;$STR_NEXT >>&nbsp;&nbsp;",'Next', !empty($OnOK) ? "Javascript:OnWizardSubmit('$Form', '$rr_redirect1', '$OnOK');" : ""); ?>&nbsp;
                </td>
                <td nowrap width=7><img src="images/transp.gif"></td>
                <td >
                <?php DrawXPButton('&nbsp;&nbsp;&nbsp;Cancel&nbsp;&nbsp;&nbsp;','Cancel', "$OnCancel"); ?>
                </td>
                </tr></table>
                </td>
        </tr>
<?php
}
else
{
?>
        <tr>
<?php

$len = max($cols-3,0);
for ($i=0; $i<$len; $i++)
{
        echo "<td></td>";
}

?>
                <td width="100%">
                <table cellpadding=0 cellspacing=0 border=0 width="100%"><tr>
                <td><?php
                if ($IsEdit && !empty($OnDelete))
                {
                        DrawXPButton ('&nbsp;&nbsp;Delete&nbsp;&nbsp;','Delete', "$OnDelete");
                }
                ?>
                </td>
                <td width="100%"></td>
                <td>
                <?php
                if ($IsEdit)
                {
                        DrawXPButton('&nbsp;&nbsp;Save Changes&nbsp;&nbsp;','Submit', "$OnOK");
                }
                else
                {
                        DrawXPButton($ADD_BUTTON_NAME,'Submit', "$OnOK");
                }
                ?>
                </td>
                <td>
                <?php DrawXPButton('&nbsp;&nbsp;&nbsp;Cancel&nbsp;&nbsp;&nbsp;','Cancel', "$OnCancel"); ?>
                </td>
                </tr></table>
                </td>
        </tr>
<?php
}
}

function CheckDTTicket($tc)
{
        global  $DREAMTIME_TICKET_DATABASE;

        $ticket_dir     = $DREAMTIME_TICKET_DATABASE;

        $buf = GetFileContents($ticket_dir."/".$tc);

        @list($username, $time, $username2) = split( ':', $buf );

        $time =  $time-time();

        if ($time < 0)
        {
//                return "";
        }

                $username2 = trim($username2);

        return $username2;
}

function DTGetPasswordByTicket($tc)
{
                $username = CheckDTTicket($tc);

                // If not found
                if (empty($username))
                {
                        // Return empty string
                        return "";
                }

                // Get password from database
                $Result = mysql_query("SELECT LoginPass FROM ChPers WHERE SecId='$username'");
                $count = @mysql_num_rows($Result);

                // Verify
                if ($count<1)
                {
                        // Return empty string
                        return "";
                }

                $Row = @mysql_fetch_row($Result);

                // Return result
        return $Row[0];
}

function sendemail($to, $subject, $message, $from, $extra="")
{
        @mail($to,$subject,$message,"From: $from\n".$extra);
        sleep(0);
        return;

        $debug_=0;

        // initialize our return array, populating with default values
        $return = array ( false,  "",  "",  "" );

        // assign our user part and domain parts respectively to seperate       // variables
        list ( $user, $domain ) = split (  "@", $to, 2 );
        if($debug_==1)
    {
                echo "user: $user<BR>";
        echo "domain: $domain<BR>";
    }

        // split up the domain name into sub-parts
        $arr = explode (  ".", $domain );

        // figure out how many parts to the host/domain name portion there are
        $count = count ( $arr );

        // get our Top-Level Domain portion (i.e. foobar.org)
        $tld = $arr[$count - 2] .  "." . $arr[$count - 1];

        if (($pos = _strpos($from,'<'))!=-1)
        {
                $fromemailaddress = substr($from, $pos+1);

                if (($pos = _strpos($fromemailaddress, '>'))!=-1)
                {
                        $fromemailaddress = substr($fromemailaddress, 0, $pos);
                }

                $fromemailaddress = trim($fromemailaddress);

                if ($fromemailaddress[0] == '"')
                {
                        $fromemailaddress = substr($fromemailaddress,1);
                }

                if (strlen($fromemailaddress)>1)
                if ($fromemailaddress[strlen($fromemailaddress)-1]=='"')
                {
                        $fromemailaddress = substr($fromemailaddress, 0, strlen($fromemailaddress)-1);
                }
        }
        else
        {
                $fromemailaddress = $from;
        }
        $from = trim($from);
        $fromemailaddress = trim($fromemailaddress);

        echo  " ";
   // check that an MX record exists for Top-Level Domain, and if so
   // start our email address checking
   if ( 1)//checkdnsrr ( $domain,  "MX" ) )
   {
           if($debug_==1)
           {
                   echo "Check DNS RR OK<BR>";
           }

   // Okay...valid dns reverse record; test that MX record for
   // host exists, and then fill the 'mxhosts' and 'weight'
   // arrays with the correct information
   if ( getmxrr ( $domain, $mxhosts, $weight ) )
   {
                if($debug_==1)
                {
                        echo  "MX LOOKUP RESULTS :<BR>";
                        for ( $i = 0; $i < count ( $mxhosts ); $i++ )
                        {
                                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;o $mxhosts[$i] $weight[$i]<BR>";
                        }
                }

                $min_index = -1;
                $min_weight = 0;

                // Get minimum weight
                for ($j=0; $j<count($weight); $j++)
                {
                        if ($min_weight == 0 || $weight[$j]<$min_weight)
                        {
                                $min_weight = $weight[$j];
                                $min_index = $j;
                        }
                }

                // If we found anything
                if ($min_index!=-1)
                {
                        // open socket on port 25 to mxhosts, setting
                        // returned file pointer to the variable 'fp'
                        if ($fp = fsockopen ( $mxhosts[$min_index], 25, $errno, $errstr, 15 ))
                        {
                                // Set socket timeout
                                socket_set_timeout($fp, 8);

                                // Non blocking
        //                        set_socket_blocking ( $fp, false );

                                $status = 0;

                                // Hello
                                do
                                {
                                        $out = fgets($fp, 2500);

                                        $tmp = $out;

                                        if (empty($tmp) || $tmp=="")
                                        {
                                                break;
                                        }

                                        if (($pos = _strpos($tmp,' ')) != -1)
                                        {
                                                $tmp = substr($tmp, 0, $pos);

                                                if (is_numeric($tmp) && ($tmp+0)>0)
                                                {
                                                        $status = $tmp+0;
                                                        break;
                                                }
                                        }

                                        echo " ";

                                } while (1);

                                // If Okay
                                if ($status == 220 || $status == 250 || $status=200)
                                {
                                        // Helo
                                        fputs($fp, "HELO JOHN\r\n");
                                        $out = fgets($fp, 2500);
                                        if (($pos = _strpos($out,' ')) != -1)
                                        {
                                                $tmp = substr($out, 0, $pos);

                                                if (is_numeric($tmp) && ($tmp+0)>0)
                                                {
                                                }
                                        }

                                        // Mail From
                                        fputs($fp, "MAIL FROM:<$fromemailaddress>\r\n");
                                        $out = fgets($fp, 2500);
                                        if (($pos = _strpos($out,' ')) != -1)
                                        {
                                                $tmp = substr($out, 0, $pos);

                                                if (is_numeric($tmp) && ($tmp+0)>0)
                                                {
                                                }
                                        }

                                        // RCPT To
                                        fputs($fp, "RCPT TO:<$to>\r\n");
                                        $out = fgets($fp, 2500);
                                        if (($pos = _strpos($out,' ')) != -1)
                                        {
                                                $tmp = substr($out, 0, $pos);

                                                if (is_numeric($tmp) && ($tmp+0)>0)
                                                {
                                                }
                                        }

                                        // Extra
                                        fputs($fp, "DATA\r\n");
                                        if (!empty($extra))
                                        {
                                                fputs($fp, "$extra\r\n");
                                        }
                                        fputs($fp, "Message-ID: <".GetRandomMessageID($to).">\r\n");
                                        fputs($fp, "X-Mailer: QUALCOMM Windows Eudora Version 5.2.0.9\r\n");
                                        fputs($fp, "To: $to\r\n");
                                        fputs($fp, "From: $from\r\n");
                                        fputs($fp, "Subject: $subject\r\n");
                                        fputs($fp, "$message\r\n\r\n.\r\n");

                                }
                                else
                                {
//                                        echo "failed sending to: [$to]<BR>";
                                }

                                fclose($fp);

                        }


                }
        }
        }

       // return the array for the user to test against
     return $return;
}


function GetRandomMessageID($to)
{
        $tmp = $to;

        for ($i=0; $i<strlen($tmp); $i++)
        {
                if ($tmp[$i]=='@')
                {
                        $tmp[$i] = '_';
                }
        }

    srand((double)microtime()*1000000);

    $vowels = array("a", "e", "i", "o", "u");
    $cons = array("b", "c", "d", "g", "h", "j", "k", "l", "m", "n", "p", "r", "s", "t", "u", "v", "w", "tr",
    "cr", "br", "fr", "th", "dr", "ch", "ph", "wr", "st", "sp", "sw", "pr", "sl", "cl");

    $num_vowels = count($vowels);
    $num_cons = count($cons);

        $password = "";
    for($i = 0; $i < 60; $i++)
        {
        $password .= $cons[rand(0, $num_cons - 1)] . $vowels[rand(0, $num_vowels - 1)];
    }

        $buf = substr($password, 0, 60);

        $buf .= '@' . $tmp;

        return $buf;
}

function HTMLSelectBoxFromFile($filename, $name, $value, $default, $class="SelectBox")
{
        global $DOCUMENT_ROOT;

        //$file = @fopen($DOCUMENT_ROOT.$filename, "r");

        if(substr($filename, 0, 1) == "/"){
        $filename = "$DOCUMENT_ROOT$filename";
        }

        $file = @fopen($filename, "r");

        if (!$file)
        {
                return 0;
        }

        echo "<select class=$class name=$name>";

        // If no value, set as default
        if (empty($value))
        {
                $value = $default;
        }

        do
        {
                $line = trim(fgets($file, 2500));

                if (!empty($line))
                {
                        echo "<option value=\"$line\"";
                        //if (Strcasecmp($value,$line)==0){
if($value == $line){
                                echo "selected";
                        }
                        echo ">";
                        echo $line;
                }

        } while (!feof($file));

        echo "</select>";

        @fclose($file);
}

///// DEBUG FUNCTIONS ////////////

$debug_users=array(9615,19310);

function getmicrotime()
{
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}

function _start()
{
	global $start_time;
	$start_time=getmicrotime();
}

function _time()
{
	global $start_time, $login_operator_id, $debug_users;
	
	if(in_array($login_operator_id, $debug_users))
		return getmicrotime()-$start_time;
	else
		return "";
}

function pr($val, $stop=false)
{
	global $debug_users;
	
	if(in_array($login_operator_id, $debug_users))
	{
		echo "<pre>";
		print_r($val);
		if($stop)
			die();
	}
}

?>
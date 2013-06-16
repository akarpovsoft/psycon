<?php

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

function ImgTag($name, $src, $border=0, $params="", $force_no_size = false)
{
	global        $DOCUMENT_ROOT;

	// Validate
	if (empty($src))
	{
		return;
	}

	// Get original image size
	$size        = @GetImageSize($DOCUMENT_ROOT.$src);
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

function HTMLSelectBoxFromFile($filename, $name, $value, $default, $class="dropdown_box")
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

// Draw XP-style button image
function DrawXPButton($text, $name, $action="", $align="left")
{
	global $IEBROWSER, $http_addr;

	//echo "<input type=button name='$name' value='&nbsp;&nbsp;$text&nbsp;&nbsp;'>";
	//return;

?><table border=0 cellspacing=0 cellpadding=0>
<tr height=6>
<td nowrap width=5 height=6 background="<?=$server?>/images/button_topleft.gif"><img src="<?=$server?>/images/transp.gif" width=5 height=6></td>
<td nowrap height=6 background="<?=$server?>/images/button_top.gif"><img src="<?=$server?>/images/transp.gif" height=6></td>
<td nowrap width=5 height=6 background="<?=$server?>/images/button_topright.gif"><img src="<?=$server?>/images/transp.gif" width=5 height=6></td>
</tr>
<tr>
<td nowrap width=5  background="<?=$server?>/images/button_left.gif"><img src="<?=$server?>/images/transp.gif" width=5></td>
<td nowrap background="<?=$server?>/images/button_bg.gif">
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
<td nowrap width=5 background="<?=$server?>/images/button_right.gif"><img src="<?=$server?>/images/transp.gif" width=5></td>
</tr>

<tr height=5>
<td nowrap width=5 height=5 background="<?=$server?>/images/button_bottomleft.gif"><img src="<?=$server?>/images/transp.gif" width=5 height=5></td>
<td nowrap background="<?=$server?>/images/button_bottom.gif"><img src="<?=$server?>/images/transp.gif"></td>
<td nowrap width=5 height=5 background="<?=$server?>/images/button_bottomright.gif"><img src="<?=$server?>/images/transp.gif" width=5 height=5></td>
</tr>
</table></td><?php
}

function DrawStatus($account_id, $id)
{
    
    global $http_addr;
    srand((double)microtime()*1000000);

        $randomid = rand(0,50000);

?>

        <img src="<?php echo $server;?>/chatgetstatus.php?account_id=<?=$account_id?>&operator_id=<?=$id?>&unique=<?=$randomid?>" name=statusimage<?=$id?> width=92 height=25 border=0>
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
                        $header .= PrintSysMessage($lang['Establishing_Communication'], $oldlineseparator);
                        $header .= PrintLine($name, $lang['common_chat_msg_1_1'].urldecode($name).$lang['common_chat_msg_1_2'], $oldlineseparator);
                }
                else
                {
                        $header .= PrintSysMessage($lang['Establishing_Communication'], $oldlineseparator);
                        $header .= PrintSysMessage($lang['common_chat_msg_2'].urldecode($name), $oldlineseparator);
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


function HTMLSelectBoxFromArray($data, $name, $value, $default='', $class="dropdown_box")
{

	echo "<select class='$class' name='$name' id='$name'>";

	// If no value, set as default
	if (empty($value))
	{
		$value = $default;
	}

	while(list($k, $v)=each($data))
	{
		echo "<option value=\"$k\"";
		if($value == $k){
			echo "selected";
		}
		echo ">".$v."</option>
		";
	};

	echo "</select>&nbsp;";
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
function IsFramed()
{
        global        $PREF_GLOBAL, $PREF_FRAMES, $use_frames;

        return $use_frames;
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
	/*
?>
<td nowrap height=21 width="1%" background="images/chart_topbg.gif"><img src="images/chart_border.gif" width=10 valign="top"></td>
<?php */
?><td class="tableVerticalHeader" nowrap height=21 width="1%"></td>  <?php 	
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

	global        $start_from_page, $sort_ascending, $sort_by_field, $filter,$lang,$server;
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
<table width=100% border='0' cellspacing=1 cellpadding=0>
<tr>
<?php
if($FormName == "history" or $FormName == "invoice"){
	$month = $HTTP_COOKIE_VARS["filter_month"];
	$f_reader = $HTTP_COOKIE_VARS["filter_reader"];
	$reader_arr[all]="All Readers";

?>
<td width=15><form name="month" action="<?php echo $server;?>/month.php" method="post">
  <select size="1" name="month">
  <option value="<?=$month?>"><?=$month_name[$month]?></option>
  <option value="all"><?=$month_name[all]?></option>
  <option value="1"><?=$month_name[1]?></option>
  <option value="2"><?=$month_name[2]?></option>
  <option value="3"><?=$month_name[3]?></option>
  <option value="4"><?=$month_name[4]?></option>
  <option value="5"><?=$month_name[5]?></option>
  <option value="6"><?=$month_name[6]?></option>
  <option value="7"><?=$month_name[7]?></option>
  <option value="8"><?=$month_name[8]?></option>
  <option value="9"><?=$month_name[9]?></option>
  <option value="10"><?=$month_name[10]?></option>
  <option value="11"><?=$month_name[11]?></option>
  <option value="12"><?=$month_name[12]?></option>

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
	echo"<option value=\"All\">".$lang['all']."</option>";
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
<?php 

DrawXPButton($lang['Go'],'Go', "Javascript:document.forms['month'].submit();"); ?>
<input type="submit" name="go" value="Go">
</form>
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
	$ShowRecordsContaining = $lang['Showing_ONLY_fields_Starting'];
}
else
{
	$ShowRecordsContaining = $lang['Search_for_fields_Starting'];
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
	/*$Image = "";

	if ($Border)
	{
		$Image = "images/chart_topbg.gif";
	}
?>
        <td nowrap width="1%" background="<?=$Image?>"><img src="images/transp.gif" ></td>
<?php */
	?>
	<td nowrap > </td>
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

function printChartSortByLink($URL, $new_sort_by_field, $Name, $AlignRight = false, $Cols = 1,$max_width='150px')
{
	global $PREF_START_FROM_PAGE, $PREF_SORT_ASCENDING, $PREF_SORT_BY_FIELD;
	global $start_from_page, $sort_ascending, $sort_by_field, $filter;

	// Set this for easier access
	$current_sort_by_field = !empty($sort_by_field) && (Strcasecmp($sort_by_field, $new_sort_by_field)==0);

	echo "<td  style='max-width:".$max_width."' class='tableVerticalHeader' colspan=$Cols ";

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
	echo        "<a class='tableHeaderLink' onfocus=\"blur();\" title=\"";
	echo        $lang['Sort_by']." ". $Name;
	if ($current_sort_by_field && $sort_ascending)
	{
		echo " (".$lang['Descending'].")";
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

function DrawNoRecordsMessage($str)
{
        DrawChartYSpace();

        echo "<tr>";

        DrawChartXSpace();

        echo "<td nowrap colspan=1><SPAN class=TextTiny><img src=images/transp.gif height=17 width=1 align=top>$str</SPAN></td>";
        echo "</tr>";
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

function SingularBGColor($i, $color="#EFEFEF")
{
        if ($i % 2 == 1)
        {
                return " bgcolor='$color' ";
        }
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

function printChartCell($Str, $Icon="", $AlignRight=false, $ExtraPrefix="", $ExtraSuffix="", $ExtraBeforePrefix="")
{
        global $IEBROWSER;

        if (empty($Str))
        {
                $Str = "&nbsp;";
        }

        echo "<td class='tableVerticalElement' nowrap ";

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

#This function can display a list of countries or states in select box
function getLangListToSelectBox($name, $arr_name ,$default)
{
	global $lang;
		
	asort($lang[$arr_name]);
	
	echo "<select name='$name' id='$name'>";
	
	while(list($k, $v)=each($lang[$arr_name]))
	{
		echo "<option value=\"$k\"";
		if($default == $k){
			echo "selected";
		}
		echo ">".$v."</option>
		";
	};

	echo "</select>&nbsp;";
	return 1;
	
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

?>
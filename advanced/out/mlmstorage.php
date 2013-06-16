<?php
@set_time_limit(0);

require_once("common.php");
require_once("database.php");
require_once("dbmessage.php");
require_once("config/config.php");

function GetPaidThroughDate($lastordertime, $fancy = true)
{
	if ($fancy)
	{
		$link = "&nbsp;&nbsp;&nbsp;<a href=\"Javascript:popUpErrorWin('mlmpayment.html');\" class=LinkSmall>Tell Me More</a>";
	}
	else
	{
		$link = "";
	}

	if (empty($lastordertime) || ($lastordertime+0 == 0))
	{
		return "<font color=red><b>Need to buy Fuel</b></font> $link";
	}
	
	$Result = @mysql_query("SELECT (TO_DAYS('$lastordertime')+30) < (TO_DAYS(now())+0)");
	$Row = @mysql_fetch_row($Result);

	if (Strcasecmp($Row[0],"1")==0)
	{
		return "<font color=red><b>Did NOT buy Fuel in the last 30 days!</b></font> $link";
	}

	$Result = @mysql_query("SELECT (TO_DAYS('$lastordertime')+30) - (TO_DAYS(now())+0)");
	$Row = @mysql_fetch_row($Result);

	$day = $Row[0];

	$Result = @mysql_query("SELECT FROM_DAYS((TO_DAYS('$lastordertime')+30))");
	$Row = @mysql_fetch_row($Result);

	$datestr = reformat_date($Row[0]);//$Row[0];//substr($Row[0], 0, 4) . ".". substr($Row[0], 4, 2) . ".". substr($Row[0], 6, 2);

	$PREFIX = "";
	$SUFFIX = "";

	if ($day < 5)
	{
		$PREFIX = "<font color=red><b>";
		$SUFFIX = "</b></font>";
	}

	return $PREFIX . "$datestr ($day day".SingularPlural($day)." remaining)" . $link. $SUFFIX;
}

function GetMemberStatus($account_id, $operator_id)
{
	$operator = storageGetFormRecord($account_id, "Operators", $operator_id);

	// Validate
	if (empty($operator['rr_record_id']))
	{
		return "N/A";
	}

	if (Strcasecmp($operator['type'],'administrator')==0)
	{
		$statusstr = "<font color=green><b>Administrator</b></font>";
	}
	else
	if ($nolifeforce)
	{
		$statusstr = "<font color=red><b>Unverified</b></font>&nbsp;<a href='lifeforce.php' class=LinkSmall target='_top'>(Need to set your LifeForce ID)</a>";
	}
	else
	if ($operatorsTotalCount+$operatorsLeads < 10)
	{
		$statusstr = "New Member  (Less than 10 signups)";
	}
	else
	if ($operatorsTotalCount < 10)
	{
		$statusstr = "Basic (Less than 10 LifeForce members in downline)";
	}
	else
	{
		$statusstr = "<font color=green><b>Professional</b></font>";
	}

	return $statusstr;
}


function SetupMessages($account_id, $message_operator_id)
{
	messageInsert($account_id, "", "/rich/messages/message1.txt",
		"Prospect Message 1", "Welcome to RichAndHealthy!", $message_operator_id);
	messageInsert($account_id, "", "/rich/messages/message2.txt",
		"Prospect Message 2", "{%name%}, I am waiting on you!", $message_operator_id);
	messageInsert($account_id, "", "/rich/messages/message3.txt",
		"Prospect Message 3", "I forgot to mention, {%name%}", $message_operator_id);
	messageInsert($account_id, "", "/rich/messages/message4.txt",
		"Prospect Message 4", "{%name%}, Your Body is a Miracle!", $message_operator_id);
	messageInsert($account_id, "", "/rich/messages/message5.txt",
		"Prospect Message 5", "Beware of the Internet, {%name%}", $message_operator_id);
	messageInsert($account_id, "", "/rich/messages/message6.txt",
		"Prospect Message 6", "{%name%}, Need More Reason?", $message_operator_id);
	messageInsert($account_id, "", "/rich/messages/message7.txt",
		"Prospect Message 7", "Hello again", $message_operator_id);
	messageInsert($account_id, "", "/rich/messages/message8.txt",
		"Prospect Message 8", "Hi {%name%}", $message_operator_id);
	messageInsert($account_id, "", "/rich/messages/message9.txt",
		"Prospect Message 9", "{%name%}, The Right Stuff", $message_operator_id);
	messageInsert($account_id, "", "/rich/messages/message10.txt",
		"Prospect Message 10", "{%name%}, Survived? Maybe?", $message_operator_id);
	messageInsert($account_id, "", "/rich/messages/message11.txt",
		"Prospect Message 11", "Hello again {%name%}", $message_operator_id);
	messageInsert($account_id, "", "/rich/messages/message12.txt",
		"Prospect Message 12", "I am Confused, {%name%}", $message_operator_id);

}

function GetSponsor($account_id, $user_id)
{
	$operator = storageGetFormRecord($account_id, "Operators", $user_id);

	return arrayvalue($operator,"aff_id");
}

function AddNewUserToMatrix($account_id, $user_id, $aff_id)
{
	AddUserToTheMatrix($account_id, $aff_id, $aff_id, $user_id, 1);
}

function DistributeCommission($account_id, $user_id)
{
	global	$MATRIX_PAY_LEVEL;

	$storage_id = "";
	$tablenameMatrixLevels	= storageGetUserTableByFormID($account_id, "MatrixLevels", &$storage_id);
	$tablenameOperators	= storageGetUserTableByFormID($account_id, "Operators", &$storage_id);
	
	$Result = @mysql_query("SELECT parent_id,level,rr_record_id FROM $tablenameMatrixLevels WHERE user_id='$user_id'");
	$count = @mysql_num_rows($Result);

	for ($i=0; $i<$count; $i++)
	{
		$Row	= @mysql_fetch_row($Result);
		
		$parent_id		= $Row[0];
		$level			= $Row[1];	
		$rr_record_id	= $Row[2];

		if (empty($MATRIX_PAY_LEVEL["$level"]))
		{
			continue;
		}

		$operatorUser = storageGetFormRecord($account_id, "Operators", $user_id);

		$share = $MATRIX_PAY_LEVEL["$level"]+0;

		// Add history record
		AddHistoryRecord($account_id, $parent_id, "Commission", "From", $operatorUser['name'], $share);

		// Add balance to parent
		@mysql_query("UPDATE $tablenameOperators SET balance = balance + $share WHERE rr_record_id='$parent_id'");

		// Update parent matrix to reflect user is now paying
		@mysql_query("UPDATE $tablenameMatrixLevels SET paying='1' WHERE rr_record_id='$rr_record_id'");

		// Notify parent
		$operator = storageGetFormRecord($account_id, "Operators", $parent_id);

		if (!empty($operator['notify_signup']))
		{
			$message	= "Congratulations ".$operator['name'].", \r\n".
						  " \r\n".
						  "You just received a $".number_format($share,2)." Commission thanks to a purchase \r\n".
						  "by ".$operatorUser['name']." who is in your Matrix on level $level. \r\n".
						  " \r\n".
						  "Your new balance is: $".number_format($operator['balance'],2)." \r\n".
						  " \r\n".
						  "Keep up the good work! \r\n".
						  " \r\n";

			mail($operator['emailaddress'], "You've got cash!",
				$message, "From: support@richandhealthy.net");
		}
	}
}

function GetTotalMonthlyIncome($account_id, $user_id, &$cnt_total_paying_members)
{
	global	$MATRIX_PAY_LEVEL;

	$tablenameMatrixLevels	= storageGetUserTableByFormID($account_id, "MatrixLevels", &$storage_id);

	$total = 0;
	$total_records = 0;
	for ($i=1; $i<=6; $i++)
	{
		$Result = @mysql_query("SELECT COUNT(*) FROM $tablenameMatrixLevels WHERE parent_id='$user_id' AND level='$i'");
		$Row = @mysql_fetch_row($Result);
		if (!empty($Row))
		{
			$cnt = $Row[0];
			$total_records += $cnt;
		}
		else
		{
			$cnt = 0;
		}

		$Result = @mysql_query("SELECT COUNT(*) FROM $tablenameMatrixLevels WHERE parent_id='$user_id' AND level='$i' AND paying='1'");
		$Row = @mysql_fetch_row($Result);

		if (!empty($Row))
		{
			$cnt_paying = $Row[0];

			$cnt_total_paying_members += $Row[0];

//			echo "SELECT COUNT(*) FROM $tablenameMatrixLevels WHERE parent_id='$user_id' AND level='$i' AND paying='1'";
		}
		else
		{
			$cnt_paying = 0;
		}

		$total += $cnt_paying * $MATRIX_PAY_LEVEL[$i];
	}

	return $total;
}

function GetCountDownline($account_id, $user_id,$level="")
{
	// Get 'Operators' table name
	$storage_id = "";
	$tablenameMatrix	= storageGetUserTableByFormID($account_id, "Matrix", &$storage_id);

    if (empty($level))
    {
    $Result = @mysql_query("SELECT COUNT(*) FROM $tablenameMatrix WHERE user_id='$user_id' AND level>='1' AND level<='8'");
    }
    else
    {
    $Result = @mysql_query("SELECT COUNT(*) FROM $tablenameMatrix WHERE user_id='$user_id' AND level='$level'");
    }

    $Row = @mysql_fetch_row($Result);
    if (empty($Row)) return 0;
    return $Row[0];
}

function AddUserToTheMatrix($account_id, $orig_parent_id, $parent_id, $user_id, $level, $recursive = true)
{
	// Get 'Operators' table name
	$storage_id = "";
	$tablenameMatrix			= storageGetUserTableByFormID($account_id, "Matrix", &$storage_id);
	$tablenameMatrixLastAccess	= storageGetUserTableByFormID($account_id, "MatrixLastAccess", &$storage_id);
	$tablenameMatrixLevels		= storageGetUserTableByFormID($account_id, "MatrixLevels", &$storage_id);
	$tablenameOperators			= storageGetUserTableByFormID($account_id, "Operators", &$storage_id);

	return IAddUserToTheMatrix($account_id, $orig_parent_id, $parent_id, $user_id, $level,
	$tablenameMatrix, $tablenameMatrixLastAccess, $tablenameOperators, $tablenameMatrixLevels, $recursive);
}

function IAddUserToTheMatrix($account_id, $orig_parent_id, $parent_id, $user_id, $level, 
$tablenameMatrix, $tablenameMatrixLastAccess, $tablenameOperators, $tablenameMatrixLevels, $recursive = true)
{
	if (Strcasecmp($parent_id,$user_id)==0)
	{
		return 1;
	}

	// Verify we don't already have this user
	$Result = @mysql_query("SELECT user_id FROM $tablenameMatrixLevels WHERE user_id='$user_id' ");
	$count = @mysql_num_rows($Result);
	if ($count>0)
	{
		return 1;
	}

	// Get number of users under <loop_user_id>
	$Result = mysql_query("SELECT numchildren FROM $tablenameMatrixLastAccess WHERE user_id = '$parent_id'");

	$Row = mysql_fetch_row($Result);
	if (empty($Row))
	{
		$Row[0] = 0;
	}

//	echo "     AddUserToTheMatrix: ".$Row[0]." under $parent_id<BR>";

	// If less than 6
	if ($Row[0]+0 < 6)
	{
//	echo "SELECT numchildren FROM $tablenameMatrixLastAccess WHERE user_id = '$parent_id'<BR>";
	for ($k=0; $k<500; $k++)
	{
	echo " ";
	}

		// Add
		$record['parent_id']	= "$parent_id";
		$record['user_id']		= "$user_id";

//		mysql_query("INSERT INTO $tablenameMatrix (parent_id, user_id) VALUES ('$parent_id','$user_id')");

//		if (mysql_insert_id()>0)
		{

	//		echo "                     Added: $user_id under $parent_id<BR>";

			// Update last access
			$record['user_id']		= "$parent_id";
			$Result = @mysql_query("SELECT rr_record_id FROM $tablenameMatrixLastAccess WHERE user_id='$parent_id'");
			$Row = @mysql_fetch_row($Result);

			if (!empty($Row))
			{
				@mysql_query("UPDATE $tablenameMatrixLastAccess SET rr_lastaccess=now(),numchildren=(numchildren+1) WHERE rr_record_id='".$Row[0]."'");
			}
			else
			{
				storageInsertUserData($account_id, "MatrixLastAccess", array("user_id"=>"$parent_id","numchildren"=>"1"));
			}

			// Update Matrix Levels
			UpdateMatrixLevels($account_id, $parent_id, $user_id, 1, 
			$tablenameMatrix, $tablenameMatrixLastAccess, $tablenameOperators, $tablenameMatrixLevels);
		}

		return 1;
	}

	if (!$recursive)
	{
		return 0;
	}

	// (Otherwise - We have 6 members already under '$loop_user_id'
	// Go deeper
	return IBuildMatrixForMemberBySibling($account_id, $orig_parent_id, $parent_id, $user_id, $level, 
	$tablenameMatrix, $tablenameMatrixLastAccess, $tablenameOperators, $tablenameMatrixLevels);
}


function IBuildMatrixForMemberBySibling($account_id, $orig_parent_id, $parent_id, $user_id, $level,
$tablenameMatrix, $tablenameMatrixLastAccess, $tablenameOperators, $tablenameMatrixLevels)
{
	$children_level		= 1;

	do
	{
		// Get children of 'parent_id'
		$Result = @mysql_query("SELECT distinct $tablenameMatrixLevels.user_id FROM $tablenameMatrixLevels left join $tablenameMatrixLastAccess on ($tablenameMatrixLevels.user_id=$tablenameMatrixLastAccess.user_id) WHERE $tablenameMatrixLevels.parent_id='$parent_id'  and ($tablenameMatrixLastAccess.numchildren<6 or $tablenameMatrixLastAccess.numchildren is null) and $tablenameMatrixLevels.level='$children_level' order by $tablenameMatrixLastAccess.rr_lastaccess");
//		echo "SELECT distinct $tablenameMatrixLevels.user_id FROM $tablenameMatrixLevels left join $tablenameMatrixLastAccess on ($tablenameMatrixLevels.user_id=$tablenameMatrixLastAccess.user_id) WHERE $tablenameMatrixLevels.parent_id='$parent_id'  and ($tablenameMatrixLastAccess.numchildren<6 or $tablenameMatrixLastAccess.numchildren is null) and $tablenameMatrixLevels.level='$children_level' order by $tablenameMatrixLastAccess.rr_lastaccess";

		$count = @mysql_num_rows($Result);

		for ($i=0; $i<$count; $i++)
		{
			$Row = @mysql_fetch_row($Result);

			// Try inserting into one of the siblings
			if (IAddUserToTheMatrix($account_id, $orig_parent_id, $Row[0], $user_id, $level+1, 
			$tablenameMatrix, $tablenameMatrixLastAccess, $tablenameOperators, $tablenameMatrixLevels, false) == 1)
			{
//				echo "Added to son $i ".($level+1)."<BR>";
				return 1;
			}
		}

		$children_level++;

	} while (true);

	echo "never here";
	return 0;
}

function UpdateMatrixLevels($account_id, $parent_id, $user_id, $level, 
$tablenameMatrix, $tablenameMatrixLastAccess, $tablenameOperators, $tablenameMatrixLevels)
{
	// Get 'Operators' table name
	$storage_id = "";

	$Result = @mysql_query("SELECT lastordertime FROM $tablenameOperators WHERE rr_record_id='$user_id'");
	$Row = @mysql_fetch_row($Result);
	if (!empty($Row))
	{
		$ispaid = IsPaid($Row[0]);
	}
	else
	{
		$ispaid = 0;
	}

	if (Strcasecmp($user_id, $parent_id)==0)
	{
		return 1;
	}

	$record["parent_id"]	= "$parent_id";
	$record["user_id"]		= "$user_id";
	$record["level"]		= "$level";
	$record["paying"]		= "$ispaid";

	mysql_query("INSERT INTO $tablenameMatrixLevels (parent_id, user_id, level, paying) VALUES ('$parent_id','$user_id','$level','$ispaid')");

	// SendSponsorEmail

	// Add to parent
//	if ($level<7)
	{
		$Result = @mysql_query("SELECT parent_id FROM $tablenameMatrixLevels WHERE user_id='$parent_id' and level='1'");
		$Row = @mysql_fetch_row($Result);

		if (!empty($Row))
		{
			$aff_id = $Row[0];
		}
		else
		{
			$aff_id = 0;
		}

			if (!empty($aff_id) )
			{
				UpdateMatrixLevels($account_id, $aff_id, $user_id, $level+1, 
				$tablenameMatrix, $tablenameMatrixLastAccess, $tablenameOperators, $tablenameMatrixLevels);
			}
			else
			{
//				echo "Not found: ".$Row[0]."<BR>";
			}
	}
}

function SetupUserSegments($account_id, $user_id)
{
	global	$MATRIX_NUM_LEVELS;

	$tablenameSegments	= storageGetUserTableByFormID($account_id, "Segments", $storage_id);
	$tablenameMatrixLevels	= storageGetUserTableByFormID($account_id, "MatrixLevels", $storage_id);

	// If we already have at least one segment for this user
	$Result = @mysql_query("SELECT * FROM $tablenameSegments where operator_id='$user_id' limit 2");
	$count = @mysql_num_rows($Result);
	if ($count>0)
	{
		// We're done
		return;
	}

	// Add the segmetns
	storageInsertUserData($account_id, "Segments", array("operator_id"=>"$user_id","name"=>"All My Members",
	"query"=>"SELECT * FROM $tablenameMatrixLevels WHERE parent_id='$user_id' and level>='1' and level<='$MATRIX_NUM_LEVELS'"));

	for ($i=1; $i<=$MATRIX_NUM_LEVELS; $i++)
	{
		$query  = "SELECT * FROM $tablenameMatrixLevels WHERE parent_id='$user_id' and level='$i'";

		storageInsertUserData($account_id, "Segments", array("operator_id"=>"$user_id","query"=>"$query",
		"name"=>"Downline Level $i"));

		echo "   ($i/$MATRIX_NUM_LEVELS)<BR>";
	}

	storageInsertUserData($account_id, "Segments", array("operator_id"=>"$user_id","name"=>"Paying Downline Members",
    "query"=>"SELECT * from $tablenameMatrixLevels WHERE parent_id='$user_id' and paying='1'"));
}


function GetProductPackageName($account_id, $product)
{
	switch ($product)
	{
		case '1':
			$product_package = "<span class=TextMedium>Body Balance-1 Quart + FlexoPlus</span><span class=TextSmall> $51/Month</span>";
			$amount += 51;
		break;

		case '2':
			$product_package = "<span class=TextMedium>Body Balance-1 gallon</span><span class=TextSmall> $82/Month</span>";
			$amount += 82;
		break;

		case '3':
			$product_package = "<span class=TextMedium>Cornerstones</span><span class=TextSmall> $167.50/Month</span>";
			$amount += 167.50;
		break;

		default:
			$product_package = "None";
		break;
	}

	return $product_package;
}

function GetLeadsPackageName($account_id, $leads)
{
	switch ($leads)
	{
		case 'light':
			$preenrollee_package = "<span class=TextMedium>Light Package</span><span class=TextSmall><br>40 Pre-Enrollees $29/Month</span>";
			$amount += 29;
		break;

		case 'medium':
			$preenrollee_package = "<span class=TextMedium>Medium Package</span><span class=TextSmall><br>100 Pre-Enrollees $49/Month</span>";
			$amount += 49;
		break;

		case 'heavy':
			$preenrollee_package = "<span class=TextMedium>Heavy Package</span><span class=TextSmall><br>200 Pre-Enrollees $99/Month</span>";
			$amount += 99;
		break;

		default:
			$preenrollee_package = "None";
		break;
	}

	return $preenrollee_package;
}

function GetOrderAmount($account_id, $product, $leads)
{
	$amount = 0;

	switch ($leads)
	{
		case 'light':
			$amount += 29;
		break;

		case 'medium':
			$amount += 49;
		break;

		case 'heavy':
			$amount += 99;
		break;
	}

	switch ($product)
	{
		case '1':
			$amount += 51;
		break;

		case '2':
			$amount += 82;
		break;

		case '3':
			$amount += 167.50;
		break;
	}

	return $amount;
}
?>

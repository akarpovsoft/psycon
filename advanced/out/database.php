<?php
require_once("config/config.php");

$DB_HANDLE		= 0;

$MYSQL_ERRNO	= '';
$MYSQL_ERROR	= '';

function dbhandle()
{
	global $DB_HANDLE;

	if (empty($DB_HANDLE) || $DB_HANDLE == 0)
	{
		$DB_HANDLE = db_connect();
	}

	return $DB_HANDLE;
}

if (!function_exists('storageGetUserTable'))
{
	function storageGetUserTable($account_id, $storage_id)
	{
		return "T". $account_id. "_". $storage_id;
	}
}

function db_connect($dbname= '')
{
	global $dbhost, $dbusername, $dbuserpassword, $default_dbname;
	global $MYSQL_ERRNO, $MYSQL_ERROR;

	$link_id = mysql_connect($dbhost, $dbusername, $dbuserpassword);
	if (!$link_id)
	{
		$MYSQL_ERRNO	= 0;
		$MYSQL_ERROR	= "Database Connection failed to the host $dbhost.";
		die($MYSQL_ERROR);
		return 0;
	}
	else
	if (empty($dbname) && !mysql_select_db($default_dbname))
	{
		$MYSQL_ERRNO	= mysql_errno();
		$MYSQL_ERROR	= mysql_error();
		die($MYSQL_ERROR);
		return 0;
	}
	else
	if (!empty($dbname) && !mysql_select_db($dbname))
	{
		$MYSQL_ERRNO	= mysql_errno();
		$MYSQL_ERROR	= mysql_error();
		die($MYSQL_ERROR);
		return 0;
	}
	else return $link_id;
}

function sql_merge_clause($select, $from, $where, $sql2, $options)
{
	$sql2 = trim($sql2);
	$where = trim($where);

	if (!empty($sql2))
	{
	if (($pos_where = _strpos(strtolower($where),"where")) != -1 )
	{
		$where = "where ($sql2) and ".substr($where,$pos_where+5);				
	}
	else
	{
		$where = " $sql2";
	}
	}


	return $select. " ".$from." ".$where." ".$options;
}

function sql_merge($select, $from, $where, $options)
{
	return $select. " ".$from." ".$where." ".$options;
}

function sql_split($sql, &$select, &$from, &$where, &$options)
{
	// Initialize
	$select		= "";
	$from		= "";
	$where		= "";
	$options	= "";

	// If we have a SELECT clause
	if (($pos_select = _strpos($sql,"select")) != -1)
	{
		// Cut everything before that
		$sql = substr($sql, $pos_select);
	}
	// (Else - no SELECT clause)
	else
	{
		// Fail
		return false;	
	}

	// Get positions of FROM, WHERE, OPTIONS
	if (($pos_options = _strpos($sql,"group")) == -1 &&
            ($pos_options = _strpos($sql,"order ")) == -1 &&
            ($pos_Options = _strpos($sql,"limit")) == -1)
	{
		$pos_options = strlen($sql);
	}
	if (($pos_where = _strpos($sql,"where")) == -1)
	{
		$pos_where = strlen($sql);
	}
	if (($pos_from = _strpos($sql,"from")) == -1)
	{
		$pos_from = strlen($sql);
	}

	// Set returned parameters
	if ($pos_options != -1)
	{
		$options = substr($sql, $pos_options);
		$sql = substr($sql, 0, $pos_options);	
	}
	if ($pos_where != -1)
	{
		$where = substr($sql, $pos_where);
		$sql = substr($sql, 0, $pos_where);
	}
	if ($pos_from != -1)
	{
		$from = substr($sql, $pos_from);
		$sql = substr($sql, 0, $pos_from);
	}
	$select = $sql;

	// Trim all
	$select = trim($select);
	$from = trim($from);
	$where = trim($where);
	$options = trim($options);

	// Return success
	return true;
}

function sql_error()
{
	global $MYSQL_ERRNO, $MYSQL_ERROR;

	if (empty($MYSQL_ERROR))
	{
		$MYSQL_ERRNO	= mysql_errno();
		$MYSQL_ERROR	= mysql_error();
	}
	return "$MYSQL_ERRNO: $MYSQL_ERROR";
}

function DBFilterFieldClause($fieldname, $filter)
{
	$Result = "";

	if (empty($filter))
	{
		return "$fieldname LIKE \"%\"";
	}

	$filter = TrimAllSpaces($filter);

	// Explode to words
	$FilterWords = Explode(" ", $filter);

	for ($i=0; $i<count($FilterWords); $i++)
	{
		// If this is an empty word,
		if (empty($FilterWords[$i]))
		{
			// Skip
			continue;
		}

		// If we already have something,
		if (!empty($Result))
		{
			// Add OR
			$Result .= " OR ";
		}
			
		$Result .= "$fieldname LIKE \"%".$FilterWords[$i]."%\"";
	}

	return $Result;
}

function DBFilterClauseTables($table_names, $filter)
{
	// Init
	$FilterClause	= "";

	$table_names = stri_replace(" join","",$table_names);
	$table_names = stri_replace(" left","",$table_names);

	$table_names = TrimAllSpaces($table_names);

	for ($i=0; $i<strlen($table_names); $i++)
	{
		if ($table_names[$i]==' ')
			$table_names[$i] = ',';
	}

	do
	{
		$next	  = _strpos($table_names, ",");
		$next_mov = 0;
		if ($next == -1)
		{
//			$next = _strpos($table_names, "left join");
			if ($next == -1)
			{
				$next = _strpos($table_names, " on ");
				if ($next == -1)
				{
					$next = strlen($table_names);
				}
			}
			else
			{
				$next_mov = 9;
			}
		}

		$loc_as	= _strpos($table_names, " as ");

		$alias	= "";

		// If we have a table alias
		if ( ($loc_as != -1) && ($loc_as < $next) )
		{
			// Get table name
			$name = substr($table_names, 0, $loc_as);

			// Get table alias
			$alias = substr($table_names, $loc_as+4, $next - ($loc_as+4));
		}
		// (Else - no table alias)
		else
		// Just grab table name
		{
			// If no next table name
			if ($next == NULL)
			{
				$name = $table_names;

				// We're done
				$table_names = "";
			}
			// (Else - we do have a next table name)
			else
			// Grab it
			{
				$name = substr($table_names, 0, $next);
			}
		}

		// Progress to next
		if ($next<strlen($table_names))
		{
			$next++;
		}
		$table_names = substr($table_names, $next+$next_mov, strlen($table_names) - $next-$next_mov);


		if (empty($alias))
		{
		$alias = $name;
		}

//		echo ">>[$name]<BR>";

		// Add to filter clause
		$FilterClauseNew = DBFilterClauseTable(trim($name), trim($alias), $filter);

		$FilterClauseNew = trim($FilterClauseNew);

		if (!empty($FilterClauseNew))
		{
			// If not first one,
			if (!empty($FilterClause))
			{
				// Add 'OR'
				$FilterClause .= " OR ";
			}

			// Add
			$FilterClause .= $FilterClauseNew;
		}

//		echo "$FilterClause<BR><BR>";

	} while (strlen($table_names) > 0);

	// Return result
	return $FilterClause;
}

function DBFilterClauseTable($tablename, $table_alias, $filter)
{
	// Init
	$FilterClause	= "";

	// Validate
	if (empty($filter))
	{
		return "";
	}

	$filter = TrimAllSpaces($filter);

	// Explode to words
	$FilterWords = Explode(" ", $filter);

	// Check if this is a huge table
	$IsHugeTable = storageIsHugeTable($tablename);

	// If huge table
	if ($IsHugeTable)
	{
		// Get keys
		$Result = @mysql_query("SHOW KEYS FROM $tablename");

		// Found anything?
		$count = @mysql_num_rows($Result);
		while ($count > 0)
		{
			// Fetch results into array
			$ArrayKeys[] = mysql_fetch_array($Result);

			$count--;
		}
	}
	else
	{
		$ArrayKeys[] = array();
	}

	// Get all field names
	// Get records
	$Result = @mysql_query("DESCRIBE $tablename");

	// Found anything?
	$count = @mysql_num_rows($Result);
	while ($count > 0)
	{
		// Fetch result into array
		$Array = mysql_fetch_array($Result);


		// Iterate through all words
		for ($i=0; $i<count($FilterWords); $i++)
		{
			// If this is an empty word,
			if (empty($FilterWords[$i]))
			{
				// Skip
				continue;
			}

			// If this is a huge table
			if ($IsHugeTable)
			{
				// Is not an index
				if (!IsIndex($Array['Field'], $ArrayKeys))
				{
					// Skip to next one
					continue;
				}
			}

			// If we already have something,
			if (!empty($FilterClause) && ($i==0))
			{
				// Add OR
				$FilterClause .= " or ";
			}

			if ($i==0)
			{
				$FilterClause .= "(";
			}

			// If we have a source tablename
			if (!empty($table_alias))
			{
				// Use it
				$FilterClause .= $table_alias . ".";
			}

			// Add to search clause
			if ($IsHugeTable)
			{
				$FilterClause .= $Array['Field'] . " = \"". $FilterWords[$i]. "\" ";
			}
			else
			{
				$FilterClause .= $Array['Field'] . " LIKE \"%". $FilterWords[$i]. "%\" ";
			}

			if ($i+1<count($FilterWords))
			{
				$FilterClause .= " or ";
			}
			else
			{
				$FilterClause .= ") ";
			}


		}

//		echo "<BR>{$FilterClause}<BR>";

		// Decrease count
		$count--;
	}

	return $FilterClause;
}

function DBGetFirstTableName($sql)
{
	// Find 'from'
	if (($start_tables = _strpos(strtolower($sql), "from")) == -1)
	{
		return "";
	}

	$start_tables += 4;

	// Find end of table list
	if (($end_tables = _strpos(strtolower($sql), "where")) != -1 ||
		($end_tables = _strpos(strtolower($sql), "limit")) != -1 ||
		($end_tables = _strpos(strtolower($sql), "group")) != -1 ||
		($end_tables = _strpos(strtolower($sql), "order ")) != -1)
	{
		// Found it
	}
	// (Else - didn't find anything)
	else
	// Use end of line
	{
		$end_tables = strlen($sql);
	}

	// Cut everything
	$sql = substr($sql, $start_tables, $end_tables - $start_tables);

	// Find next
	$next	  = _strpos($sql, ",");
	if ($next == -1)
	{
		$next = strlen($sql);
	}

	// Get table name
	$table_name = substr($sql, 0, $next);

	// If we have an alias
	if (($alias_pos = _strpos(strtolower($sql), " as ")) != -1)
	{
		// Remove it
		$table_name = substr($sql, 0, $alias_pos);
	}

	// Return result
	return trim($table_name);
}

function CreateLikeString($FieldsArray, $filter, $table_names)
{
	$table_names = stri_replace(" join","",$table_names);
	$table_names = stri_replace(" left","",$table_names);

	$table_names = TrimAllSpaces($table_names);

	for ($i=0; $i<strlen($table_names); $i++)
	{
		if ($table_names[$i]==' ')
			$table_names[$i] = ',';
	}

	$AraryKeys = array();

	//echo "table names: $table_names<BR>";

/*	do
	{
		$next	  = _strpos($table_names, ",");
		$next_mov = 0;
		if ($next == -1)
		{
			if ($next == -1)
			{
				$next = _strpos($table_names, " on ");
				if ($next == -1)
				{
					$next = strlen($table_names);
				}
			}
			else
			{
				$next_mov = 9;
			}
		}

		$loc_as	= _strpos($table_names, " as ");

		$alias	= "";

		// If we have a table alias
		if ( ($loc_as != -1) && ($loc_as < $next) )
		{
			// Get table name
			$name = substr($table_names, 0, $loc_as);

			// Get table alias
			$alias = substr($table_names, $loc_as+4, $next - ($loc_as+4));
		}
		// (Else - no table alias)
		else
		// Just grab table name
		{
			// If no next table name
			if ($next == NULL)
			{
				$name = $table_names;

				// We're done
				$table_names = "";
			}
			// (Else - we do have a next table name)
			else
			// Grab it
			{
				$name = substr($table_names, 0, $next);
			}
		}

		// Progress to next
		if ($next<strlen($table_names))
		{
			$next++;
		}
		$table_names = substr($table_names, $next+$next_mov, strlen($table_names) - $next-$next_mov);


		if (empty($alias))
		{
		$alias = $name;
		}

		// Add to filter clause
		GetTableFieldNames(trim($name), &$ArrayKeys);
//		$FilterClauseNew = DBFilterClauseTable(trim($name), trim($alias), $filter);

//		$FilterClauseNew = trim($FilterClauseNew);

	} while (strlen($table_names) > 0);
*/
/*	for ($i=0; $i<count($ArrayKeys); $i++)
	{
		echo "-".$ArrayKeys[$i]."-<BR>";
	}
	die;*/

	$filter = TrimAllSpaces($filter);

	// Explode to words
	$FilterWords = Explode(" ", $filter);

	$FilterClause = "";

	for ($j=0; $j<count($FieldsArray); $j++)
	{
		// Iterate through all words
		for ($i=0; $i<count($FilterWords); $i++)
		{
			// If this is an empty word,
			if (empty($FilterWords[$i]))
			{
				// Skip
				continue;
			}

//			if (!isStringInArray($FieldsArray[$j],$ArrayKeys))
			{
//				echo $FieldsArray[$j]."---"."<BR>";
	//			continue;
			}

			// If this is a huge table
	//		if ($IsHugeTable)
	//		{
	//			// Is not an index
	//			if (!IsIndex($Array['Field'], $ArrayKeys))
	///			{
	//				// Skip to next one
	//				continue;
	//			}
	//		}

			// If we already have something,
			if (!empty($FilterClause) && ($i==0))
			{
				// Add OR
				$FilterClause .= " or ";
			}

			if ($i==0)
			{
				$FilterClause .= "(";
			}

			// If we have a source tablename
//			if (!empty($table_alias))
//			{
//				// Use it
//				$FilterClause .= $table_alias . ".";
//			}

			// Add to search clause
//			if ($IsHugeTable)
///			{
//				$FilterClause .= $Array['Field'] . " = \"". $FilterWords[$i]. "\" ";
//			}
//			else
			{
				$FilterClause .= $FieldsArray[$j] . " LIKE \"%". $FilterWords[$i]. "%\" ";
			}

			if ($i+1<count($FilterWords))
			{
				$FilterClause .= " or ";
			}
			else
			{
				$FilterClause .= ") ";
			}


		}
	}

	return $FilterClause;
}

function GetTableFieldNames($tablename, &$ArrayKeys)
{
	// Check if this is a huge table
	$IsHugeTable = storageIsHugeTable($tablename);

	// If huge table
	if ($IsHugeTable)
	{
		// Get keys
		$Result = @mysql_query("SHOW KEYS FROM $tablename");

		// Found anything?
		$count = @mysql_num_rows($Result);
		while ($count > 0)
		{
			// Fetch results into array
			$ArrayKeys[] = mysql_fetch_array($Result);

			$count--;
		}
	}

	// Get all field names
	// Get records
	$Result = @mysql_query("DESCRIBE $tablename");

	// Found anything?
	$count = @mysql_num_rows($Result);
	while ($count > 0)
	{
		// Fetch result into array
		$Array = mysql_fetch_array($Result);


			// If this is a huge table
			if ($IsHugeTable)
			{
				// Is not an index
				if (!IsIndex($Array['Field'], $ArrayKeys))
				{
					// Skip to next one
					continue;
				}
			}
			
			$ArrayKeys[] = $tablename.".".$Array['Field'];

		// Decrease count
		$count--;
	}

	return $ArrayKeys;
}

function DBFilterClause2($IsHugeTable, $sql, $filter, $AddAND = true)
{

	$fields = "";

	$filter = _addslashes($filter);

	$table_names = " ";

	$loop_sql = $sql;

	// Find 'from'
	if (($start_tables = _strpos(strtolower($loop_sql), " from")) == -1)
	{
		return " ";
	}

	$fields = substr($loop_sql,0,$start_tables);

	$alias_pos = 0;
	
	$FieldsArray = array();

		
//	echo "$fields<BR>";

//	echo "Source: [$loop_sql]<BR><BR>";

	$loop_sql = substr($loop_sql,$start_tables+5);

	$found = true;

//	return " ";

	do
	{

		// Find end of table list
		if (   ($end_tables = _strpos(strtolower($loop_sql), " on ")) != -1 ||
			   ($end_tables = _strpos(strtolower($loop_sql), " where ")) != -1 ||
			($end_tables = _strpos(strtolower($loop_sql), " limit ")) != -1 ||
			($end_tables = _strpos(strtolower($loop_sql), " group ")) != -1 ||
			($end_tables = _strpos(strtolower($loop_sql), " order ")) != -1)
		{
			$addition = 0;

			// Found it
			while ($addition+$end_tables<strlen($loop_sql) && $loop_sql[$end_tables+$addition]==' ')
				$addition++;
			while ($addition+$end_tables<strlen($loop_sql) && $loop_sql[$end_tables+$addition]!=' ')
				$addition++;
		}
		// (Else - didn't find anything)
		else
		// Use end of line
		{
			$end_tables = strlen($loop_sql);
			$addition = 0;
		}

		// Set these for easier access
		{
			$table_names	.= " " . substr($loop_sql, 0, $end_tables);
		}

		$loop_sql = substr($loop_sql, $end_tables+$addition);

		if (empty($loop_sql))
		{
			$found = false;
			break;
		}

		if ( ($end_tables = _strpos(strtolower($loop_sql), " join ")) != -1)
		{
			$found = true;
			$loop_sql = substr($loop_sql, $end_tables + 5);
	//		mail("mike@idansoft.com","found join", $loop_sql);
		}
		else
		{	
	//		mail("mike@idansoft.com","didnt find join", $loop_sql);
			$found = false;
			break;
		}

	} while ($found);

//	return " ";

	// Add aliases
	do
	{
		// Search for comma
		$comma_pos	= _strpos(strtolower($fields),",");
		$alias_pos	= _strpos(strtolower($fields)," as ");

		if ($comma_pos == -1 && !empty($fields))
		{
			$comma_pos = strlen($fields);
		}

		$addition = 1;
		if ($comma_pos>1 && $comma_pos<$alias_pos)
		{
			$addition = 1;
		}
		else
		if ($alias_pos>1 && ($alias_pos<$comma_pos))
		{
			$tmp = substr($fields,$alias_pos);
			$addition = _strpos($tmp,",");
			if ($addition==-1)
			{
				$addition = strlen($tmp);
			}
			else
			{
				$addition++;
			}
//			echo "addition = $addition (".substr($tmp,$addition).")<BR>";
			$comma_pos = $alias_pos;
		}

		if ($comma_pos >1)
		{
			$end_pos = $comma_pos-1;
			
			while ($end_pos>0 && $fields[$end_pos]==' ')
			{
				$end_pos--;
			}

			$start_pos = $end_pos;
			while ($start_pos>0 && $fields[$start_pos]!=' ')
			{
				$start_pos--;
			}
			
			$newalias = substr($fields, $start_pos, $end_pos-$start_pos+1);

			$newalias = trim($newalias);

			if (_strpos($newalias,")")!=-1 || 
			    _strpos($newalias,"(")!=-1 ||
				_strpos($newalias,"'")!=-1 ||
				_strpos($newalias,'"')!=-1)
			{
					$fields = substr($fields,$comma_pos+1);
					continue;
//					echo "func";
//					die;
			}

		//	if (isStringInArray($newalias, $ArrayKeys))
			{
				$FieldsArray[] = $newalias;
			}

//			echo "($newalias)<BR>$fields";
//			die;


			// Remove what we handled
			$fields = substr($fields,$comma_pos+$addition);
		}

		else
		{
			break;
		}
	}
	while (true);


//	mail("mike@idansoft.com","table names:",$table_names);
//	echo "table_names = $table_names [$start_tables .. $end_tables] len=".strlen($sql);
//	die;

//	return " ";

	
	// Init
	$FilterClause	= CreateLikeString($FieldsArray,$filter,$table_names);//DBFilterClauseTables($table_names, $filter);
	$PREFIX			= " (";
	$SUFFIX			= ") ";

//	return " ";

//	echo "($fields)<BR>";

	// If we have nothing,
	if (empty($FilterClause))
	{
		// Return empty string
		return " ";
	}

	// If we don't have a where clause
	if (_strpos(strtolower($sql), "where") == -1)
	{
		// Add it
		$PREFIX	= " WHERE (";
	}
	// (Else - we have a where clause)
	else
	// If need to add AND
	if ($AddAND)
	{
		$PREFIX		= " AND (";
	}

	// Return result
	return $PREFIX . $FilterClause . $SUFFIX;
}

function IsIndex($Field, $ArrayKeys)
{
	$found = false;

	for ($i=0; $i<count($ArrayKeys); $i++)
	{
		$Key_name = arrayvalue($ArrayKeys[$i], 'Key_name');

		if (Strcasecmp($Key_name, $Field."_2")==0)
		{
			for ($j=0; $j<count($ArrayKeys); $j++)
			{
				$Key_name = arrayvalue($ArrayKeys[$j], 'Key_name');
				$Non_unique = arrayvalue($ArrayKeys[$j],'Non_unique');

				if (Strcasecmp($Key_name,$Field)==0 &&
				empty($Non_unique))
				{
//					echo "found $Key";
					$found = true;
					break;
				}
			}
		}
	}

	return $found;
}

	function storageGetUserTableByFormID($account_id, $formid, &$storage_id)
	{
		$Result = @mysql_query("SELECT storage_id FROM storage WHERE account_id='$account_id' AND formid='$formid' LIMIT 1");

		// Found anything?
		$count = @mysql_num_rows($Result);
		if ($count > 0)
		{
			$Row = mysql_fetch_row($Result);

			// Set returned storage_id
			$storage_id = $Row[0];

			return storageGetUserTable($account_id, $Row[0]);
		}

		return "";
	}

function DBFilterClause($IsHugeTable, $tablename, $filter, $AddAND = true, $source_tablename="")
{
	$filter = _addslashes($filter);

	if (empty($filter))
	{
		return "";
	}

	$filter = TrimAllSpaces($filter);

	// Explode to words
	$FilterWords = Explode(" ", $filter);

	// If huge table
	if ($IsHugeTable)
	{
		// Get keys
		$Result = @mysql_query("SHOW KEYS FROM $tablename");

		// Found anything?
		$count = @mysql_num_rows($Result);
		while ($count > 0)
		{
			// Fetch results into array
			$ArrayKeys[] = mysql_fetch_array($Result);

			$count--;
		}
	}
	else
	{
		$ArrayKeys[] = array();
	}

	// Get all field names
	// Get records
	$Result = @mysql_query("DESCRIBE $tablename");

	// Init
	$FilterClause	= "";
	$PREFIX			= "(";
	$SUFFIX			= ") ";

	// If need to add AND
	if ($AddAND)
	{
		$PREFIX		= " AND (";
	}

	// Found anything?
	$count = @mysql_num_rows($Result);
	while ($count > 0)
	{
		// Fetch result into array
		$Array = mysql_fetch_array($Result);


		// Iterate through all words
		for ($i=0; $i<count($FilterWords); $i++)
		{
			// If this is an empty word,
			if (empty($FilterWords[$i]))
			{
				// Skip
				continue;
			}

			// If this is a huge table
			if ($IsHugeTable)
			{
				// Is not an index
				if (!IsIndex($Array['Field'], $ArrayKeys))
				{
					// Skip to next one
					continue;
				}
			}

			// If we already have something,
			if (!empty($FilterClause) && ($i==0))
			{
				// Add OR
				$FilterClause .= " OR ";
			}

			if ($i==0)
			{
				$FilterClause .= "(";
			}

			// If we have a source tablename
			if (!empty($source_tablename))
			{
				// Use it
				$FilterClause .= $source_tablename . ".";
			}

			// Add to search clause
			if ($IsHugeTable)
			{
				$FilterClause .= $Array['Field'] . " = \"". $FilterWords[$i]. "\" ";
			}
			else
			{
				$FilterClause .= $Array['Field'] . " LIKE \"%". $FilterWords[$i]. "%\" ";
			}


			if ($i+1<count($FilterWords))
			{
				$FilterClause .= " or ";
			}
			else
			{
				$FilterClause .= ") ";
			}

		}

//		echo "<BR>{$FilterClause}<BR>";

		// Decrease count
		$count--;
	}

//echo "[".$FilterClause."]";

	// Return result
	return $PREFIX . $FilterClause . $SUFFIX;
}


?>

<?php
require_once("common.php");
require_once("database.php");
require_once("config/config.php");

if (empty($DREAMTIME_ACCOUNT))
{
require_once("mlmstorage.php");
}
else
{
require_once("dtfstorage.php");
}

function storageOpen()
{
        // If storage table doesn't exist,
        if (!mysql_query("SELECT storage_id FROM storage LIMIT 1"))
        {
                // Create table
                if (!mysql_query("CREATE TABLE storage (
                                                        account_id MEDIUMINT(10) NOT NULL,
                                                        storage_id MEDIUMINT(10) DEFAULT '1' NOT NULL AUTO_INCREMENT,

                                                        name CHAR(60) NOT NULL,
                                                        description CHAR(100),
                                                        formid CHAR(50) NOT NULL,

                                                        createdate DATE DEFAULT '0000-00-00' NOT NULL,
                                                        lastaccess TIMESTAMP(14),
                                                        status SMALLINT DEFAULT '1' NOT NULL,
                                                        is_system SMALLINT DEFAULT '0' NOT NULL,

                                                        chartfields CHAR(255),
                                                        emailaddress CHAR(100),

                                                        rr_constraint CHAR(255),

                                                        PRIMARY KEY (storage_id),
                                                        UNIQUE formid (account_id, formid),
                                                        UNIQUE storage_id (storage_id)
                                                )"))
                {
                        die ("Database fatal error: Storage table creation");
                }
        }

        // (If we're up to here - [storage] table exists)
        // Return success
        return true;
}

function storageSetSystemFlag($account_id, $formid, $flag)
{
        // Set record
        $Result         = @mysql_query("UPDATE storage SET is_system = '$flag'
                                        WHERE account_id = '$account_id' AND formid = '$formid'");

        return $Result;
}

function storageInsert($account_id, $name,
$description, $formid, $constraint="", $fields="", $index="")
{
        // Safe
        $name                        = _addslashes($name);
        $description        = _addslashes($description);

        // Validate
        if (empty($name))
        {
                return false;
        }

        // Insert storage
        $Result = @mysql_query("INSERT INTO storage (account_id, name, description, formid,
                                                createdate, rr_constraint)
                                                VALUES ('$account_id', '$name', '$description', '$formid',
                                                date_format(now(), '%Y-%m-%d %H:%i:%s'), '$constraint')");

        // If successful,
        if ($Result)
        {
                // Get table name
                $tablename        = storageGetUserTable($account_id, @mysql_insert_id());

                // Drop table
                @mysql_query("DROP TABLE $tablename");

                // Create table
                $Result1 = storageCreateUserDataTable($account_id, $formid, $fields, $index);
        }

        // Return result
        return $Result && $Result1;
}

function storageDeleteUserDataRecord($account_id, $storage_id, $record_id)
{
        $tablename        = storageGetUserTable($account_id, $storage_id);

        // Delete storage
        $Result = mysql_query("DELETE FROM $tablename WHERE rr_account_id='$account_id' AND rr_record_id='$record_id'");

        return mysql_affected_rows()>0;
}

function storageDeleteUserData($account_id, $formid, $record_id)
{
        $storage_id = "";
        $tablename        = storageGetUserTableByFormID($account_id, $formid, &$storage_id);

        // Delete storage
        $Result = mysql_query("DELETE FROM $tablename WHERE rr_record_id='$record_id'");

        return mysql_affected_rows()>0;
}

function storageDelete($account_id, $storage_id)
{
        // Get table name
        $tablename        = storageGetUserTable($account_id, $storage_id);

        // Delete storage
        @mysql_query("DELETE FROM storage WHERE account_id='$account_id' AND storage_id='$storage_id'");

        // Set result
        $Result = mysql_affected_rows()>0;

        // If successful,
        if ($Result)
        {
                // Delete storage table
                @mysql_query("DROP TABLE $tablename");
        }

        return $Result;
}

function storageGetRecord($account_id, $storage_id)
{
        // Get record
        $Result = @mysql_query("SELECT * from storage WHERE account_id='$account_id' AND storage_id='$storage_id'");

        // Found anything?
        $count = @mysql_num_rows($Result);
        if ($count < 1)
        {
                return array("");
        }

        // Convert to array
        $Array = mysql_fetch_array($Result);

        // Strip slashes
        @array_walk(&$Array, "StripSlashesFromArray", 0);

        return $Array;
}


function storageSetRecord($account_id, $storage_id, $storage)
{
        // Add slashes
        @array_walk(&$storage, "AddSlashesToArray", 0);

        // Validate
        if (empty($storage['name']))
        {
                return false;
        }

        // Set record
        $Result         = @mysql_query("UPDATE storage SET name = '$storage[name]',
                                        description = '$storage[description]',
                                                                status = '$storage[status]',
                                                                formid = '$storage[formid]',
                                                                chartfields = '$storage[chartfields]'
                                        WHERE account_id = '$account_id' AND storage_id = '$storage_id'");

        return ($Result);
}

function storageGetCount($account_id, $filter, $SQLFilter="")
{
        if (!empty($SQLFilter))
        {
                $SQLFilter = " and (".$SQLFilter.") ";
        }

        // Get count
        $Result                = @mysql_query("SELECT count(*) FROM storage
                                                                WHERE account_id='$account_id' ".$SQLFilter.
                                                                DBFilterClause(false, "storage", $filter));

        // Failed?
        if (!$Result)
        {
                return 0;
        }

        // Get row
        $Row = mysql_fetch_row($Result);

        // Return result
        return $Row[0];
}

function SQLSafe($SQL)
{
        // If no 'from' - return empty string
        if (_strpos(strtolower($SQL), "from")==-1)
        {
                return "";
        }

        // If no 'where' clause - add it
        $WHERE_STR        = "";
        if (_strpos(strtolower($SQL), "where")==-1)
        {
                $WHERE_STR        = " WHERE rr_record_id=rr_record_id ";
        }

        // Return string
        return ($SQL . $WHERE_STR);
}

function storageGetList($account_id, $sort_by_field="", $is_ascending="", $rows_per_page="", $start_from_page="",
$filter="", $SQLFilter="")
{
        $ResultArray        = array( array() );

        if (!empty($SQLFilter))
        {
                $SQLFilter = " and (".$SQLFilter.") ";
        }

        // Set sort order
        if (!$is_ascending)
        {
                $Sort = "DESC";
        }
        else
        {
                $Sort = "";
        }

        // Do we have a limit
        if (!empty($rows_per_page) && !empty($start_from_page))
        {
                $Limit_str = " LIMIT ". ($start_from_page-1)*$rows_per_page. ",". $rows_per_page;
        }
        else
        {
                $Limit_str = "";
        }

        // Do we have a sort field?
        if (!empty($sort_by_field))
        {
                $Sort_by_field_str = "ORDER BY '$sort_by_field' ". $Sort;
        }
        else
        {
                $Sort_by_field_str = "";
        }

        // Get records
        $Result                = @mysql_query("SELECT * FROM storage
                                                                WHERE account_id='$account_id' ".$SQLFilter.
                                                                DBFilterClause(false, "storage", $filter).
                                                                $Sort_by_field_str.
                                                                " " . $Limit_str);

        // Found anything?
        $count = @mysql_num_rows($Result);
        while ($count > 0)
        {
                // Fetch result into array
                $Array = mysql_fetch_array($Result);

                // Strip slashes
                @array_walk(&$Array, "StripSlashesFromArray", 0);

                // Add to array
                $ResultArray[] = $Array;

                // Decrease count
                $count--;
        }

        // Return result
        return $ResultArray;
}

function storageGetUserDataRecord($account_id, $storage_id, $record_id, $tablename="")
{
        $ResultArray        = array();

        if (empty($tablename))
        {
                $tablename        = storageGetUserTable($account_id, $storage_id);
        }

        // Get record
        $Result = @mysql_query("SELECT * from $tablename WHERE rr_record_id='$record_id'");

        // Found anything?
        $count = @mysql_num_rows($Result);
        if ($count < 1)
        {
                return array("");
        }

        // Convert to array
        $Array = @mysql_fetch_array($Result);

        // Strip slashes
        @array_walk(&$Array, "StripSlashesFromArray", 0);

        return $Array;
}

function storageSetUserDataRecord($account_id, $storage_id, $record, $record_id)
{
        $tablename        = storageGetUserTable($account_id, $storage_id);

        // Add slashes
        @array_walk(&$record, "AddSlashesToArray", 0);

        $SQL                = "UPDATE $tablename SET rr_lastaccess=now() ";

        foreach ($record as $Name => $Value)
        {
                $SQL .= ",$Name = \"". $Value. "\" ";
        }

        $SQL                .= "WHERE rr_account_id = '$account_id' AND rr_record_id = '". $record_id. "'";

        // Set record
        $Result         = @mysql_query($SQL);

        return ($Result);
}

function storageIsHuge($account_id, $formid)
{
        // Initialize
        $storage_id = 0;

        // Get table name
        $tablename = storageGetUserTable($account_id, $storage_id);

        // Return result
        return storageIsHugeTable($tablename);
}

function storageIsHugeTable($tablename)
{
        global        $MAX_RECORDS_NONINDEX_SEARCH;

        $Result = @mysql_query("SELECT COUNT(*) FROM $tablename");

        if (!$Result)
        {
                return false;
        }

        $Row = mysql_fetch_row($Result);

        return $Row[0] > $MAX_RECORDS_NONINDEX_SEARCH;
}

function storageGetUserDataCount($account_id, $formid, $filter="", $SQLFilter="")
{
        global        $MAX_LIST_FIELDLENGTH;

        $storage_id = "";
        $tablename        = storageGetUserTableByFormID($account_id, $formid, &$storage_id);

        if (!empty($SQLFilter))
        {
                $SQLFilter = " and (".$SQLFilter.") ";
        }

        // Check if huge table
        $IsHugeTable        = storageIsHugeTable($tablename);

        // Build chart field selection
        $FieldsNormal = GetTableFields($account_id, $storage_id, "", "", ",");


        if (true)//$cut_long_fields)
        {
        $Fields = GetTableFields($account_id, $storage_id, "LEFT(", ",$MAX_LIST_FIELDLENGTH)",",");
        }
        else
        {
        $Fields = GetTableFields($account_id, $storage_id, "", "", ",");
        }


        // Get records
        $Result                = @mysql_query("SELECT COUNT(*) FROM $tablename ".
                                                                "WHERE (rr_record_id=rr_record_id) ".$SQLFilter.
                                                                DBFilterClause2(false, "SELECT $FieldsNormal FROM $tablename WHERE (rr_record_id=rr_record_id) ", $filter, true));
//                                                                DBFilterClause($IsHugeTable, "$tablename", $filter));

//        echo "SELECT COUNT(*) FROM $tablename ".
//                                                                "WHERE (rr_record_id=rr_record_id) ".$SQLFilter.
//                                                                DBFilterClause2(false, "SELECT $Fields FROM $tablename", $filter, true);

        if (!$Result)
        {
                return 0;
        }

        // Get row
        $Row = mysql_fetch_row($Result);

        // Return result
        return $Row[0];
}

function storageGetSQLList($account_id, $SQL, $sort_by_field="", $is_ascending="", $rows_per_page=20, $start_from_page=1,
$filter="", $record_id=-1)
{
        $ResultArray        = array( array() );

        //echo "[$sort_by_field] [$is_ascending] [$rows_per_page] [$start_from_page] [$record_id]";

        // Set sort order
        if (!$is_ascending)
        {
                $Sort = "DESC";
        }
        else
        {
                $Sort = "";
        }

        // Do we have a limit

        //echo"$start_from_page, $rows_per_page<br>";
        if (!empty($rows_per_page) && !empty($start_from_page))
        {
                if ($record_id != -1)
                {
                        $Limit_str = " LIMIT ". (($start_from_page-1)*$rows_per_page+$record_id) . ",1";
                }
                else
                {
                        $Limit_str = " LIMIT ". ($start_from_page-1)*$rows_per_page. ",". $rows_per_page;
                }
        }
        else
        {
                $Limit_str = "";
        }



        // Do we have a sort field?
        if (!empty($sort_by_field))
        {
                $Sort_by_field_str = "ORDER BY '$sort_by_field' ". $Sort;
        }
        else
        {
                $Sort_by_field_str = "";
        }

//        echo ($SQL).
//                                                                //WHERE rr_account_id='$account_id' ".
//                                                                DBFilterClause2($SQL, $filter, true).
//                                                                $Sort_by_field_str.
//                                                                " " . $Limit_str;
/*
echo "Filter = $filter<BR>";
echo "FilterClause = ".DBFilterClause2(false,$SQL,$filter,true);
echo "<BR>";
echo "SQL = $SQL<BR>";
*///echo $SQL.DBFilterClause2(false,$SQL,$filter,true)." ".$Limit_str;

//        mail("mike@idansoft.com","!!!",$SQL.
//                                                                DBFilterClause2(false, $SQL, $filter, true).
//                                                                $Sort_by_field_str.
//                                                                " " . $Limit_str);
        //echo"test".$SQL.DBFilterClause2(false, $SQL, $filter, true).$Sort_by_field_str." " . $Limit_str; exit();
        // Get records
        $Result                = @mysql_query($SQL.
                                //WHERE rr_account_id='$account_id' ".
                                                                DBFilterClause2(false, $SQL, $filter, true).
                                                                $Sort_by_field_str.
                                                                " " . $Limit_str);

                                                                /*mail("mike@idansoft.com","!",$SQL.
                                //WHERE rr_account_id='$account_id' ".
                                                                DBFilterClause2(false, $SQL, $filter, true).
                                                                $Sort_by_field_str.
                                                                " " . $Limit_str);*/



        // Found anything?
        $count = @mysql_num_rows($Result);
        while ($count > 0)
        {
                // Fetch result into array
                $Array = mysql_fetch_array($Result);

                // Strip slashes
                @array_walk(&$Array, "StripSlashesFromArray", 0);

                // Add to array
                $ResultArray[] = $Array;

                // Decrease count
                $count--;
        }

        // Return result
        return $ResultArray;
}


function storageGetUserDataList($account_id, $formid, $sort_by_field="", $is_ascending="", $rows_per_page="", $start_from_page="",
$filter="", $SQLFilter="", $cut_long_fields = true)
{
        global        $MAX_LIST_FIELDLENGTH;

        $storage_id = "";
        $tablename        = storageGetUserTableByFormID($account_id, $formid, &$storage_id);

        $ResultArray        = array( array() );

        // Set sort order
        if (!$is_ascending)
        {
                $Sort = "DESC";
        }
        else
        {
                $Sort = "";
        }

        // Do we have a limit
        if (!empty($rows_per_page) && !empty($start_from_page))
        {
                $Limit_str = " LIMIT ". ($start_from_page-1)*$rows_per_page. ",". $rows_per_page;
        }
        else
        {
                $Limit_str = "";
        }

        // Do we have a sort field?
        if (!empty($sort_by_field))
        {
                $Sort_by_field_str = "ORDER BY $sort_by_field ". $Sort;
        }
        else
        {
                $Sort_by_field_str = "";
        }

        if (!empty($SQLFilter))
        {
                $SQLFilter = "and (".$SQLFilter.") ";
        }

        $FieldsNormal = GetTableFields($account_id, $storage_id, "", "", ",");
        $FieldsCut    = GetTableFields($account_id, $storage_id, "LEFT(", ",$MAX_LIST_FIELDLENGTH)",",");

        // Build chart field selection
        if ($cut_long_fields)
        {
        $Fields = $FieldsCut;
        }
        else
        {
        $Fields = $FieldsNormal;
        }
//$Fields="*";

        // Check if huge table
        $IsHugeTable        = storageIsHugeTable($tablename);

        // Get records
        $Result                = @mysql_query("SELECT $Fields FROM $tablename ".
                                                                //WHERE rr_account_id='$account_id' ".
                                                                "WHERE (rr_record_id=rr_record_id) ".$SQLFilter.
//                                                                DBFilterClause($IsHugeTable, "$tablename", $filter).
                                                                DBFilterClause2(false, "SELECT $FieldsNormal FROM $tablename WHERE (rr_record_id=rr_record_id) ", $filter, true).
                                                                $Sort_by_field_str.
                                                                " " . $Limit_str);

/*echo "SELECT $Fields FROM $tablename ".
                                                                //WHERE rr_account_id='$account_id' ".
                                                                "WHERE (rr_record_id=rr_record_id) ".$SQLFilter.
                                                                DBFilterClause2(false, "SELECT $Fields FROM $tablename", $filter, true),
                                                                $Sort_by_field_str.
                                                                " " . $Limit_str."<BR>";*/

        // Found anything?
        $count = @mysql_num_rows($Result);
//        echo "*** $count $Result ***";
        while ($count > 0)
        {
                // Fetch result into array
                $Array = mysql_fetch_array($Result);

                // Strip slashes
                @array_walk(&$Array, "StripSlashesFromArray", 0);

                // Add to array
                $ResultArray[] = $Array;

                // Decrease count
                $count--;
        }

        // Return result
        return $ResultArray;
}

function GetTableFields($account_id, $storage_id, $FieldPrefix="", $FieldSuffix="", $FieldSeparator="")
{
        $ResultStr        = "";

        $tablename        = storageGetUserTable($account_id, $storage_id);

        // Get records
        $Result = @mysql_query("DESCRIBE $tablename");

        // Found anything?
        $count = @mysql_num_rows($Result);
        while ($count > 0)
        {
                // Fetch result into array
                $Array = mysql_fetch_array($Result);

                if (!empty($ResultStr))
                {
                        $ResultStr .= $FieldSeparator;
                }

//                if (_strpos($Array['Field'],"longtext")!=-1)
                {
                $ResultStr .= $FieldPrefix . $tablename.".".$Array['Field'] . $FieldSuffix . " as ".$Array['Field'];
                }
//                else
//                {
//                $ResultStr .= $Array['Field'];
//                }

                $count--;
        }

        return $ResultStr;
}

function storageGetFieldEnum($account_id, $storage_id, $field="")
{
        global        $ARRAY_RESTRICTED_FIELDS, $STR_IGNORETHISFIELD;

        $ResultArray = array();

        $tablename        = storageGetUserTable($account_id, $storage_id);
        $count                = 0;
        $found                = false;

        // Get records
        $Result = @mysql_query("DESCRIBE $tablename");

//        echo "<BR>Describe $tablename $storage_id<BR>";

        // Found anything?
        $count = @mysql_num_rows($Result);
        while (($count > 0) && (!$found))
        {
                // Fetch result into array
                $Array = mysql_fetch_array($Result);

                // Strip slashes
                @array_walk(&$Array, "StripSlashesFromArray", 0);

                do
                {
                        // Is this a field we want
                        if (!empty($field) && isset($Array['Field']))
                        {
                                if (Strcasecmp($Array['Field'],$field)!=0)
                                {
                                        break;
                                }
                        }

                        // Mark found
                        $found = true;

                        // Parse enum values
                        $valuestring = $Array['Type'];
                        $valuestring = trim($valuestring);

//echo "valuestring = ".$valuestring."<BR><BR>";

                        // Strip enum
                        if (($first_char = _strpos($valuestring,"enum(")) != -1)
                        {
                                $valuestring = substr($valuestring, $first_char + 4, strlen($valuestring)-($first_char+4));
                        }
                        // Strip paranthesis
                        if ($valuestring[0] == "(")
                        {
                                $valuestring = substr($valuestring, 1, strlen($valuestring)-1);
                        }
                        if (strlen($valuestring)>0)
                        if ($valuestring[strlen($valuestring)-1] == ")")
                        {
                                $valuestring = substr($valuestring, 0, strlen($valuestring)-1);
                        }

//echo " processing = ".$valuestring."<BR>";

                        do
                        {
                                $start = 0;
                                if ($valuestring[$start]==",") $start++;
                                if ($valuestring[$start]=="'") $start++;

//                                echo "<BR>valuestring = $valuestring\n";

                                $end = $start;
                                while ($end < strlen($valuestring))
                                {
                                        if ($valuestring[$end]=="'")
                                        {
                                                if ($end+1<strlen($valuestring))
                                                {
                                                        if ($valuestring[$end+1]=="'")
                                                        {
                                                                // Skip
                                                                $end++;
                                                        }
                                                        else
                                                        {
        //                                                        echo "Found end = $end (start = $start)\n";

                                                                break;
                                                        }
                                                }
                                                else
                                                {
//                                                        echo "Found end = $end (start = $start)\n";

                                                        break;
                                                }
                                        }

                                        $end++;
                                }

                                if ($end - $start > 0)
                                {
                                        $Item = substr($valuestring, $start, $end-$start);

                                        $Item = str_replace("''", "'", $Item);

                                        if (!empty($Item) )
                                        {
                                                $ResultArray[] = /*_stripslashes*/($Item);
                                        }
//                                        echo "cutting: ".substr($valuestring, $start, $end-$start) . "\n";
                                }

                                $valuestring = substr($valuestring, $end+1, max(0,strlen($valuestring) - ($end+1)));

                        } while (strlen($valuestring)>0);

//                        while ( ($first_char = _strpos($valuestring,"',")) != -1 ||
//                                ($first_char = _strpos($valuestring,"')"

                } while (false);

                // Decrease count
                $count--;
        }

        // Return result
        return $ResultArray;
}

function storageGetFields($account_id, $storage_id, $field="")
{
        global        $ARRAY_RESTRICTED_FIELDS;

        $ResultArray = array();

        $tablename        = storageGetUserTable($account_id, $storage_id);
        $count                = 0;

        // Get records
        $Result = @mysql_query("DESCRIBE $tablename");

        // Found anything?
        $count = @mysql_num_rows($Result);
        while ($count > 0)
        {
                // Fetch result into array
                $Array = mysql_fetch_array($Result);

                // Strip slashes
                @array_walk(&$Array, "StripSlashesFromArray", 0);

                do
                {
                        // Is this a field we want
                        if (!empty($field) && isset($Array['Field']))
                        {
                                if (Strcasecmp($Array['Field'],$field)!=0)
                                {
                                        break;
                                }
                        }

                        if (isStringInArray($Array['Field'],$ARRAY_RESTRICTED_FIELDS) ||
                                Strcasecmp($Array['Field'], "rr_record_id")==0 ||
                                Strcasecmp($Array['Field'], "rr_lastaccess")==0 ||
                                Strcasecmp($Array['Field'], "rr_createdate")==0)
                        {
                                break;
                        }

                        // Add
                        $ResultArray[] = $Array;

                } while (false);

                // Decrease count
                $count--;
        }

        // Get indexes
        $Result = @mysql_query("SHOW KEYS FROM $tablename");

        // Found anything?
        $count = @mysql_num_rows($Result);
        while ($count > 0)
        {
                // Fetch results into array
                $Array = mysql_fetch_array($Result);

                if (isset($Array['Column_name']))
                {
                        for ($i=0; $i<count($ResultArray); $i++)
                        {
                                if (isset($ResultArray[$i]['Field']))
                                {
                                        if (Strcasecmp($ResultArray[$i]['Field'], $Array['Key_name'])==0)
                                        {
                                                $ResultArray[$i] = array_merge($ResultArray[$i], array('Key' => 'Key'));
                                        }
                                }
                        }
                }

                // Decrease count
                $count--;
        }

        return $ResultArray;
}

function storageGetFieldSize($Type)
{
        $Type =substr($Type,0,15);

        $Type = strtolower($Type);

        // If enum,
        if (_strpos($Type,"enum")!=-1)
        {
                return "";
        }

        // (Not enum - get size)
        $Str = strstr($Type, "(");
        if (!empty($Str))
        {
                $i = _strpos($Str, ")");
                if ($i != -1)
                {
                        return Substr($Str, 1, $i-1);
                }
        }

        return "";
}

function storageGetFieldType($Type)
{
        if (_strpos($Type, "(") != -1)
        {
                return substr($Type, 0, _strpos($Type,"("));
        }

        return $Type;
}

function storageGetFieldDispType($Type)
{
        global        $STR_DESC_VARCHAR, $STR_DESC_INT, $STR_DESC_TIMESTAMP, $STR_DESC_DOUBLE, $STR_DESC_LONGTEXT, $STR_DESC_ENUM;

        $Type = strtolower(substr($Type,0,15));

        if (_strpos(substr($Type,0,4), "char")!=-1)
        {
                return $STR_DESC_VARCHAR;
        }
        else
        if (_strpos(substr($Type,0,3), "int")!=-1)
        {
                return $STR_DESC_INT;
        }
        else
        if (_strpos(substr($Type,0,14), "timestamp")!=-1)
        {
                return $STR_DESC_TIMESTAMP;
        }
        else
        if (_strpos(substr($Type,0,6), "double")!=-1)
        {
                return $STR_DESC_DOUBLE;
        }
        else
        if (_strpos(substr($Type,0,8), "longtext")!=-1)
        {
                return $STR_DESC_LONGTEXT;
        }
        else
        if (_strpos(substr($Type,0,4), "enum")!=-1)
        {
                return $STR_DESC_ENUM;
        }
        else
        {
                return $STR_DESC_VARCHAR;
        }
}

function storageGetFormIDList($account_id)
{
        $ResultArray = array();

        // Get records
        $Result                = @mysql_query("SELECT * FROM storage
                                                                WHERE account_id='$account_id'");

        // Found anything?
        $count = @mysql_num_rows($Result);
        while ($count > 0)
        {
                // Fetch result into array
                $Array = mysql_fetch_array($Result);

                // Strip slashes
                @array_walk(&$Array, "StripSlashesFromArray", 0);

                // Add to array
                $ResultArray[] = $Array;

                // Decrease count
                $count--;
        }

        // Return result
        return $ResultArray;
}

function storageGetFormRecord($account_id, $formid, $record_id)
{
        $storage_id = -1;

        storageGetUserTableByFormID($account_id, $formid, &$storage_id);

        return storageGetUserDataRecord($account_id, $storage_id, $record_id);
}

function storageFormIDByStorageID($account_id, $storage_id)
{
        $Result = @mysql_query("SELECT formid FROM storage WHERE account_id='$account_id' AND storage_id='$storage_id' LIMIT 1");

        // Found anything?
        $count = @mysql_num_rows($Result);
        if ($count > 0)
        {
                $Row = mysql_fetch_row($Result);

                return $Row[0];
        }

        return "";
}

if (!function_exists('storageGetUserTableByFormID'))
{

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
}

if (!function_exists('storageGetUserTable'))
{
        function storageGetUserTable($account_id, $storage_id)
        {
                return "T". $account_id. "_". $storage_id;
        }
}

function storageGetIDFromFormID($account_id, $form_id)
{
        $Result = @mysql_query("SELECT storage_id FROM storage WHERE account_id='$account_id' AND formid='$form_id' LIMIT 1");

        // Found anything?
        $count = @mysql_num_rows($Result);
        if ($count > 0)
        {
                $Row = mysql_fetch_row($Result);

                return $Row[0];
        }

        return "";
}

function storageGetIDFromTablename($table_name)
{
        if ( ($table_name_pos = _strpos($table_name, "_")) == -1)
        {
                return "";
        }

        return substr($table_name, $table_name_pos+1, strlen($table_name) - ($table_name_pos+1));
}


function storageGetSQLCount($account_id, $filter, $sql, $sql2="")
{
        $count                = 0;

        sql_split($sql, &$select, &$from, &$where, &$options);

        $sql = sql_merge_clause($select, $from, $where, $sql2, $options);
        sql_split($sql, &$select, &$from, &$where, &$options);

        if (empty($from))
        {
                return 0;
        }

        $sql_filter = DBFilterClause2(false, $sql, $filter, false);

        $sql = sql_merge_clause($select, $from, $where, $sql_filter, $options);

        sql_split($sql, &$select, &$from, &$where, &$options);

//        $where = substr($where, 5);
//echo "$where ";
//echo "SELECT COUNT(*) ".($from) ." ".
//                                  $where;

        // Do we have this table,
        if ($Result = @mysql_query("SELECT COUNT(*) ".($from) ." ".
                                  $where))
        {
                // Found anything?
                if (@mysql_num_rows($Result) > 0)
                {
                        $Row = mysql_fetch_row($Result);
                        $count = $Row[0];
                }
        }

        return $count;
}

function storageGetUserTableCount($account_id, $storage_id)
{
        $tablename        = storageGetUserTable($account_id, $storage_id);
        $count                = 0;

        // Do we have this table,
        if ($Result = @mysql_query("SELECT COUNT(*) FROM $tablename"))
        {
                // Found anything?
                if (@mysql_num_rows($Result) > 0)
                {
                        $Row = mysql_fetch_row($Result);
                        $count = $Row[0];
                }
        }

        return $count;
}

function IsValidEnumValue($Str)
{
        global $MAX_ENUM_FIELDSIZE;

        if (strlen($Str)>=$MAX_ENUM_FIELDSIZE ||
                _strpos($Str,"\r")!=-1 ||
                _strpos($Str,"\n")!=-1)
        {
                return false;
        }

        return true;
}

function storageAddMissingFields($account_id, $tablename, $ArrayValues, $storage_id)
{
        global        $MAX_CHART_FIELDS, $STR_DESC_LONGTEXT, $STR_DESC_VARCHAR, $STR_IGNORETHISFIELD, $MAX_ENUM_FIELDSIZE, $MAX_ENUM_RECORDS, $STR_FIELDTYPE;
        global        $STR_DESC_INT, $STR_DESC_DOUBLE, $STR_DESC_VARCHAR, $STR_DESC_LONGTEXT;

        $Result = true;

        // Get list of existing fields
        $ArrayExistingFields        = array();
        $ArrayFieldTypes                = array();
        $ArrayFieldSizes                = array();
        $Result1 = @mysql_query("DESCRIBE $tablename");

        // Found anything?
        $count = @mysql_num_rows($Result1);
        while ($count > 0)
        {
                // Fetch result into array
                $Row = mysql_fetch_array($Result1);

                $ArrayExistingFields[]        = $Row[0];
                $ArrayFieldTypes                = array_merge($ArrayFieldTypes, array($Row[0] => $Row[1]));
                $ArrayFieldSizes                = array_merge($ArrayFieldSizes, array($Row[0] => storageGetFieldSize($Row[1])));
//echo "[".$Row[1]."] ".storageGetFieldSize($Row[1]);
//echo "[".$Row[0]."] = ".storageGetFieldSize($Row[1])."<BR>";
                $count--;
        }

        // Get chart fields
        $chartfieldsArray = storageGetChartFields($account_id, $storage_id);
        if (count($chartfieldsArray) < $MAX_CHART_FIELDS)
        {
                $AutoFillChartfields = true;
        }

        // Attempt adding missing fields
        $NamePrevious = "";
        foreach ($ArrayValues as $Name => $Value)
        {
                // Set these for easier access
                $NewFieldType        = !empty($Value) ? "enum('".$Value."')" : "enum('".$STR_IGNORETHISFIELD."')";
                $OldFieldType        = storageGetFieldDispType(htmlarrayvalue($ArrayFieldTypes,"$Name"));
                $OldFieldSize        = htmlarrayvalue($ArrayFieldSizes,"$Name");
                $RequestedType        = arrayvalue($ArrayValues, $Name.$STR_FIELDTYPE);
                $is_exists                = isStringInArray($Name, $ArrayExistingFields);
                $need_alter                = false;
                $SQLAlter                = "";
                $ArrayEnums                = array();

                // If empty field or 'ignorethisfield' value, skip
                if (empty($Name) ||
                    Strcasecmp($Value,$STR_IGNORETHISFIELD)==0)
                {
                        continue;
                }

                // If field ends with '_rr_fieldtype'
                if (strlen($Name)>strlen($STR_FIELDTYPE))
                if (Strcasecmp( substr($Name,strlen($Name)-strlen($STR_FIELDTYPE)), $STR_FIELDTYPE)==0)
                {
                        continue;
                }

//                echo "Testing: $Name ";

                // If existing field is an enum
                if (_strpos($OldFieldType, "enum")!=-1)
                {
                        // Get enum array
                        $ArrayEnums = storageGetFieldEnum($account_id, $storage_id, $Name);
//echo "Count = ".count($ArrayEnums)." [$Name]<BR>";

                }

        //        echo "Name=$Name Len of value = ".strlen($Value)."<BR>";

                // If given value is a string longer than 255 characters,
                if (strlen($Value)>255)
                {

                        // If existing field type is NOT 'LONGTEXT'
                        if (Strcasecmp($OldFieldType, $STR_DESC_LONGTEXT)!=0)
                        {
                                // Set new field type
                                $NewFieldType = "LONGTEXT";
//echo "LONGTEXT";
                                $need_alter = true;
                        }
                }
                // (Else - not a long string)
                else
                // If given value longer than $MAX_ENUM_FIELDSIZE characters (and smaller than 255)
                // or value contains any CR/LFs
                // or too many enum fields
                if (!IsValidEnumValue($Value) ||
                        count($ArrayEnums)+1 > $MAX_ENUM_RECORDS)
                {
                        // If existing field type is NOT 'VARCHAR'
                        if ((Strcasecmp($OldFieldType, $STR_DESC_VARCHAR)!=0 ||
                                $OldFieldSize != 255) &&
                                (Strcasecmp($OldFieldType, $STR_DESC_LONGTEXT)!=0))
                        {
//echo "CHAR $OldFieldSize $OldFieldType";
                                // Set new field type
                                $NewFieldType = "CHAR(255)";
                                $need_alter = true;
                        }
                }
                // (Else - safe to use as enum)
                else
                // If existing field is an enum
                if (_strpos($OldFieldType, "enum")!=-1)
                {
//                        echo " enum";
//                        echo "Enum of $Name: ";
//                        echo "<BR>Built ArrayEnums for $account_id:$storage_id count=".count($ArrayEnums)." values=".count($ArrayValues)."<BR>";

                        // Build enum array
                        for ($j=0; $j<count($ArrayEnums); $j++)
                        {
                                $Item = $ArrayEnums[$j];
                                $Item = _addslashes($Item);

//                                echo $Item.",";

                                $ArrayEnums[$j] = "'".$Item."'";
                        }
//                        echo "<BR>";

                        // Add new value
                        if (empty($Value))
                        {
                                $Value = $STR_IGNORETHISFIELD;
                        }

                                $Item = $Value;
                                $Item = _addslashes($Item);

                        if (!isStringInArray("'".$Item."'", $ArrayEnums))
                        {
                                $ArrayEnums[] = "'".$Item."'";
                        }

                        // Sort enum array
                        sort($ArrayEnums);

                        // Make a one-line string that MySQL understands
                        $NewEnumStr = Implode(",", $ArrayEnums);

                        // Set new field type
                        $NewFieldType        = "enum($NewEnumStr)";
//                        echo "$Name = $NewFieldType<BR>";

                        // Do we need to alter
                        if (Strcasecmp($NewFieldType, htmlarrayvalue($ArrayFieldTypes,"$Name"))!=0)
                        {
//                        echo "Old = $OldFieldType ".htmlarrayvalue($ArrayFieldTypes,"$Name")."  $NewFieldType<BR>Old = ".htmlarrayvalue($ArrayFieldTypes,"$Name")."<BR>";
                                $need_alter                = true;
//echo "CHAR2";
                        }
                }
                // (Else - existing field is not an enum)
                else
                {
//                        echo "a $OldFieldType";
                }

//                echo "<BR>";

                // If we don't have this field,
                if (!$is_exists)
                {
                        // If we have a user requested type
                        if (!empty($RequestedType))
                        {
                                if (Strcasecmp($RequestedType, $STR_DESC_INT)==0)
                                {
                                        $NewFieldType        = "BIGINT";
                                }
                                else
                                if (Strcasecmp($RequestedType, $STR_DESC_DOUBLE)==0)
                                {
                                        $NewFieldType        = "DOUBLE";
                                }
                                else
                                if (Strcasecmp($RequestedType, $STR_DESC_LONGTEXT)==0)
                                {
                                        $NewFieldType        = "LONGTEXT";
                                }
                                else
                                if (Strcasecmp($RequestedType, $STR_DESC_VARCHAR)==0)
                                {
                                        $NewFieldType = "CHAR";
                                }
                        }

                        // Add it
                        $SQLAlter = "ALTER TABLE $tablename ADD $Name $NewFieldType";

                        if (!empty($NamePrevious))
                        {
                                $SQLAlter.= " AFTER $NamePrevious";
                        }
//                        else
//                        {
//                                $SQLAlter.= " AFTER rr_record_id";
//                        }

                        // If insufficient chart fields,
                        if (isset($AutoFillChartfields))
                        {
                                if (count($chartfieldsArray) < $MAX_CHART_FIELDS)
                                {
                                        $chartfieldsArray[] = $Name;
                                }
                        }
                }
                // (Else - we already have this field)
                else
                // If need to change definition
                if ($need_alter)
                {
                        // Change definition
                        $SQLAlter = "ALTER TABLE $tablename CHANGE $Name $Name $NewFieldType";

                }

//                                echo "$SQLAlter<BR>";

                // If we need to do anything,
                if (!empty($SQLAlter))
                {
                        // Do it
                        if (!@mysql_query($SQLAlter))
                        {
//                                echo "Failed: $SQLAlter<BR>";
//                                die;
                                $Result = false;
                                break;
                        }
                        else
                        {
//                        echo "ok [$Result]";
                        }
                }
//die;
                // Remember last field
                // (So that we can insert the next one directly after this one)
                $NamePrevious = $Name;
        }

        // Chart fields updated
        if (isset($AutoFillChartfields))
        {
                storageSetChartFields($account_id, $storage_id, $chartfieldsArray);
        }

        // We added new fields,
        // Reset constraint
        if (!empty($storage_id))
        {
//                echo "1";
                $storage = storageGetRecord($account_id, $storage_id);

//                echo "2: $storage_id | $storage[rr_constraint]";
                if (!empty($storage['rr_constraint']))
                {
//                        echo "ALTER TABLE $tablename add ".$storage['rr_constraint'];
                        @mysql_query("ALTER TABLE $tablename add ".$storage['rr_constraint']);
                }
        }

        // Return result
        return $Result;
}

function storageAddMissingValues($tablename, &$ArrayValues)
{
        global                $ARRAY_RESTRICTED_FIELDS;

        // Get list of existing fields
        $ArrayExistingFields = array();
        $Result = @mysql_query("DESCRIBE $tablename");

        // Found anything?
        $count = @mysql_num_rows($Result);
        while ($count > 0)
        {
                // Fetch result into array
                $Row = mysql_fetch_array($Result);

                $ArrayExistingFields[] = $Row[0];

                $count--;
        }

        // Iterate through all fields we found
        foreach ($ArrayExistingFields as $Name => $Value)
        {
                // If field does not exist in <ArrayValues>
                if (!isset($ArrayValues[$Value]) &&
                        !isStringInArray($Value, $ARRAY_RESTRICTED_FIELDS))
                {
                        // Add empty value
                        $ArrayValues = array_merge($ArrayValues, array($Value => ""));
//                        echo "$Value ". isset($ArrayValues[$Value])."<BR>";
                }
        }
//        echo "---<BR>";
//        die;
}

function storageCreateUserDataTable($account_id, $formid, $fields="", $constraints="")
{
        if (!empty($fields))
        {
                $fields .= ", ";
        }
        if (!empty($constraints))
        {
                $constraints = ", $constraints";
        }

        $tablename = storageGetUserTableByFormID($account_id, $formid, &$storage_id);

        // Create table
        if (!@mysql_query("CREATE TABLE $tablename (
                                rr_record_id MEDIUMINT(10) DEFAULT '1' NOT NULL AUTO_INCREMENT,
                                rr_account_id MEDIUMINT(10) DEFAULT '1' NOT NULL,

                                rr_emailsent SMALLINT DEFAULT '0' NOT NULL,
                                rr_synchronized SMALLINT DEFAULT '0' NOT NULL,
                                rr_createdate DATE DEFAULT '0000-00-00' NOT NULL,
                                rr_lastaccess TIMESTAMP(14),
                                $fields

                                PRIMARY KEY (rr_record_id),
                                INDEX (rr_lastaccess),
                                UNIQUE rr_record_id (rr_record_id)
                                $constraints
                                )"))
        {
//                        echo "Failed creating table.<BR>";

                return false;
        }

        return true;
}

function IstorageInsertUserData($account_id, $ArrayValues, $tablename, $bForceInsert=false)
{
        global        $STR_IGNORETHISFIELD;

        // If we don't have record id
        if (!isset($ArrayValues['rr_record_id']) || $bForceInsert)
        {
                // Build SQL string
                $SQLInsert        = "INSERT INTO $tablename (rr_lastaccess, rr_account_id, rr_createdate";

                foreach ($ArrayValues as $Name => $Value)
                {
                        if (!empty($Name))
                        {
                                $SQLInsert.= ", ". $Name;
                        }
                }

                $SQLInsert.= ") ";

                $SQLInsert.= "VALUES (now(), $account_id, date_format(now(), '%Y-%m-%d %H:%i:%s')";

                foreach ($ArrayValues as $Name => $Value)
                {
                        if (!empty($Name) && Strcasecmp($Value,$STR_IGNORETHISFIELD)!=0)
                        {
                                $SQLInsert.= ", \"". _addslashes($Value). "\"";
                        }
                }
                $SQLInsert.= ")";

//                echo "<BR><BR>".$SQLInsert."<BR>";
//die;
                // Attempt to insert
                if (!($Result = mysql_query($SQLInsert)))
                {
                        // If this is a 'duplicate key' error
                        if (mysql_errno() == 1062)
                        {
//                echo "<BR><BR>".$SQLInsert."<BR>";
                                $RecordID = -2;
                        }
                }
                // (Else - Insert successful)
                else
                {
                        // Get record ID
                        $RecordID = @mysql_insert_id();
                }

        }
        // (Else - we have record ID)
        else
        {
                // Attempt update
                // Build SQL string
                $SQLUPDATE        = "UPDATE $tablename SET rr_lastaccess=now() ";

                foreach ($ArrayValues as $Name => $Value)
                {
                        if (!empty($Name) &&
                                Strcasecmp($Name,"rr_record_id")!=0 &&
                                Strcasecmp($Value,$STR_IGNORETHISFIELD)!=0)
                        {
                                $SQLUPDATE.= ", ". $Name. " = ". "\"". _addslashes($Value). "\"";
                        }
                }

                $SQLUPDATE.= " WHERE rr_record_id='";
                $SQLUPDATE.= $ArrayValues['rr_record_id']."'";

                $Result = @mysql_query($SQLUPDATE);

//        echo "Attempting update:\n".$SQLUPDATE;
//  die;

                $RecordID = $ArrayValues['rr_record_id'];

                if (mysql_affected_rows() > 0)
                {
                }
                // (Else - update failed)
                else
                {
                        // If this is a 'duplicate key' error
                        if (mysql_errno() == 1062)
                        {
                                return -2;
                        }
                }
        }

        return $RecordID;
}

function storageInsertUserData($account_id, $formid, $ArrayValues, $tablename="", $MissingValuesAsEmpty=false)
{
        global $MAX_CHART_FIELDS, $STR_IGNORETHISFIELD, $STR_FIELDTYPE;

        // Init
        $RecordID                        = -1;
        $storage_id                        = 0;
        $bForceInsert                = false;

        // Get table name
        $_tablename = storageGetUserTableByFormID($account_id, $formid, &$storage_id);

        // Get table name
        if (empty($tablename))
        {
                $tablename = $_tablename;
        }

        // If didn't find table,
        if (empty($tablename))
        {
                return -1;
        }

        // Remove all numeric fields
        foreach ($ArrayValues as $Name => $Value)
        {
//                echo "$Name<BR>";
                if (is_numeric($Name) )
                {
//                        echo "-Removing $Name<BR>";
                        unset($ArrayValues[$Name]);
                }
        }
        unset($ArrayValues['rr_lastaccess']);
        unset($ArrayValues['rr_account_id']);
        unset($ArrayValues['rr_createdate']);


        // Add missing values
        if ($MissingValuesAsEmpty)
        {
                storageAddMissingValues($tablename, &$ArrayValues);
        }
        foreach ($ArrayValues as $Name => $Value)
        {
                if ($Value == $STR_IGNORETHISFIELD)
                {
                        unset($ArrayValues[$Name]);
                }
        }

        // If table doesn't exist yet,
        if (!@mysql_query("SELECT * FROM $tablename LIMIT 1"))
        {
                // Create it
                if (!storageCreateUserDataTable($tablename))
                {
                                return -1;
                }
        }

        // Fix for empty 'rr_record_id'
        if (isset($ArrayValues['rr_record_id']))
        {
                // If empty
                if (empty($ArrayValues['rr_record_id']))
                {
                        // Unset
                        unset($ArrayValues['rr_record_id']);
                }
                else
                // (Otherwise - not empty)
                {
                        // If record doesn't exist
                        $Result = @mysql_query("SELECT rr_record_id FROM $tablename WHERE rr_record_id='".$ArrayValues['rr_record_id']."'");
                        $count = @mysql_num_rows($Result);

                        if ($count<1)
                        {
                                // Force insert
                                $bForceInsert = true;
                        }
                }
        }

        // Attempt adding missing fields
        if (!storageAddMissingFields($account_id, $tablename,
        $ArrayValues, $storage_id))
        {
                return -1;
        }

        // Remove all fields that end with '_rr_fieldtype'
        foreach ($ArrayValues as $Name => $Value)
        {
                // If field ends with '_rr_fieldtype'
                if (strlen($Name)>strlen($STR_FIELDTYPE))
                if (Strcasecmp(substr($Name,strlen($Name)-strlen($STR_FIELDTYPE)), $STR_FIELDTYPE)==0)
                {
                        unset($ArrayValues[$Name]);
                }
        }


        // Perform insert
        $RecordID = IstorageInsertUserData($account_id, $ArrayValues, $tablename, $bForceInsert);

        // If successful
        if ($RecordID >= 0)
        {
                // Update last access
                @mysql_query("UPDATE storage SET lastaccess = now() WHERE account_id='$account_id' AND formid='$formid'");
        }

        // Return record ID
        return $RecordID;
}

function storageGetFileLocation($account_id, $record_id, $name, $type)
{
        global $DIR_DATA, $DOCUMENT_ROOT;

        return $DIR_DATA.$account_id."/".$name.$record_id.".".$type;
}

function storageAddUserDataField($account_id, $storage_id, $fieldname, $new_size, $new_type, $new_defaultvalue, &$ResultStr)
{
        return storageModifyUserDataField($account_id, $storage_id, "", $fieldname, $new_size, $new_type, $new_defaultvalue, &$ResultStr);
}

function storageModifyUserDataField($account_id, $storage_id, $fieldname, $new_fieldname, $new_size, $new_type, $new_defaultvalue, &$ResultStr)
{
        global        $STR_DESC_LONGTEXT, $STR_DESC_VARCHAR, $STR_DESC_DOUBLE, $STR_DESC_INT, $STR_DESC_ENUM, $MAX_ENUM_RECORDS;

        // Initialize
        $operation_str        = "Modified";
        $operation1_str        = "modifying";

        // Set these for easier access
        $SIZE_STR                = "($new_size)";

        // Get table name
        $tablename = storageGetUserTable($account_id, $storage_id);

        // If we have no current field
        if (empty($fieldname))
        {
                // This is an addition of a new field
                $fieldname = $new_fieldname;

                $ADD_OPERATION        = true;
                $operation_str        = "Created";
                $operation1_str        = "creating";
        }

        // Set these for easier access
        $FieldIsEnum                = false;
        $new_defaultvalue        = addslashes($new_defaultvalue);
        if (!empty($new_defaultvalue))
        {
                $DEFAULT_STR        = " DEFAULT \"".$new_defaultvalue."\"";
        }
        else
        {
                $DEFAULT_STR        = "";
        }

        // If not an add operation
        if (!isset($ADD_OPERATION))
        {
                // If current field type is 'enum'
                // Remember it
                {
                        // Check current field type
                        $Result1 = @mysql_query("DESCRIBE $tablename $fieldname");

                        // Found anything?
                        $count = @mysql_num_rows($Result1);
                        if ($count > 0)
                        {
                                // Fetch result into array
                                $Type = mysql_fetch_row($Result1);

                                if (_strpos($Type[1],"enum(") != -1)
                                {
                                        $FieldIsEnum = true;
                                }
                        }
                }

                // If field is enum and we are converting it to numeric
                if ($FieldIsEnum &&
                        (Strcasecmp($new_type,$STR_DESC_INT)==0 ||
                         Strcasecmp($new_type,$STR_DESC_DOUBLE)==0))
                {
                        // Need to first convert type to text
                        // (Otherwise we will lose ALL enum values)
                        if (!($Result = storageModifyUserDataField($account_id, $storage_id, $fieldname, $fieldname,
                                50, $STR_DESC_VARCHAR, $new_defaultvalue, &$ResultStr)))
                        {
                                return $Result;
                        }
                }
        }

        // Modify type
        if (Strcasecmp($new_type, $STR_DESC_INT)==0)
        {
                switch ($new_size)
                {
                        case 4:
                                $new_type        = "SMALLINT";
                        break;

                        case 6:
                                $new_type        = "SMALLINT";
                        break;

                        case 9:
                                $new_type        = "MEDIUMINT";
                        break;

                        case 20:
                                $new_type        = "BIGINT";
                        break;

                        default:
                                $new_type        = "INT";
                        break;
                }
        }
        else
        if (Strcasecmp($new_type, $STR_DESC_DOUBLE)==0)
        {
                $new_type                = "DOUBLE";
                $new_size                = 0;
                $SIZE_STR                = "";
        }
        else
        if (Strcasecmp($new_type, $STR_DESC_LONGTEXT)==0)
        {
                $new_type                = "LONGTEXT";
                $DEFAULT_STR        = "";
                $new_size                = 0;
                $SIZE_STR                = "";
        }
        else
        if (Strcasecmp($new_type, $STR_DESC_VARCHAR)==0)
        {
                $new_type = "CHAR";
        }
        else
        if (Strcasecmp($new_type, $STR_DESC_ENUM)==0)
        {
                $ArrayEnums = array();

                // Count number of different values
                $Result = @mysql_query("SELECT $fieldname FROM $tablename GROUP BY $fieldname");
                $count = @mysql_num_rows($Result);

                // If too many values
                if ($count > $MAX_ENUM_RECORDS)
                {
                        // Return error
                        $ResultStr = "Failed modifying field to Enum - too many unique values ($MAX_ENUM_RECORDS is the max)";
                        return false;
                }

                // Iterate through all values, ensuring they are all valid to be included in enum
                while ($count > 0)
                {
                        $Row = mysql_fetch_row($Result);
                        $count--;

                        $Value = $Row[0];

                        // Store value
                        $ArrayEnums[] = "'" . _addslashes($Value) . "'";

                        // If invalid value
                        if (!IsValidEnumValue($Value))
                        {
                                // Return error
                                $ResultStr = "Failed modifying field to Enum - some values are invalid to be included in enum";
                                return false;
                        }
//                        echo "Adding $Value<BR>";
                }

                // Sort enum array
                sort($ArrayEnums);

                // Make a one-line string that MySQL understands
                if (count($ArrayEnums)>0)
                {
                        $NewEnumStr = Implode(",", $ArrayEnums);
                }
                else
                {
                        $NewEnumStr = $STR_IGNORETHISFIELD;
                }

                // Set new field type
                $new_type                = "enum($NewEnumStr)";
                $new_size                = 0;
                $SIZE_STR                = "";
//                echo $new_type;
//                die;
        }
        else
        {
                $ResultStr = "Failed modifying field - Invalid type.";
                return false;
        }

        // Alter field
        if (isset($ADD_OPERATION))
        {
                $Result = @mysql_query("ALTER TABLE $tablename ADD $fieldname $new_type ".$SIZE_STR." ".$DEFAULT_STR);
        }
        else
        {
                $Result = @mysql_query("ALTER TABLE $tablename CHANGE $fieldname $new_fieldname $new_type ".$SIZE_STR. " ".
                                  $DEFAULT_STR);
        }

        // If successful
        if ($Result)
        {
                // Set result string
                $ResultStr        = "Form Field [". htmlsafe($fieldname). "] $operation_str successfuly.";
        }
        else
        {
                if (empty($ResultStr))
                {
                        $ResultStr = "Failed $operation1_str field [".htmlsafe($fieldname)."] - Error #".@mysql_errno();
                }
        }

        return $Result;
}

function storageDeleteUserDataField($account_id, $storage_id, $fieldname)
{
        // Get table name
        $tablename = storageGetUserTable($account_id, $storage_id);

        $Result = @mysql_query("ALTER TABLE $tablename DROP $fieldname");

        return $Result;
}

function storageSetChartFields($account_id, $storage_id, $chartfieldsArray)
{
        $chartfields = Implode(";", $chartfieldsArray);

        if (!empty($chartfields))
        {
                if ($chartfields[0] == ';')
                {
                        $chartfields = substr($chartfields, 1, strlen($chartfields)-1);
                }
        }

        // Set
        $Result = @mysql_query("UPDATE storage SET chartfields = '$chartfields' WHERE account_id='$account_id' AND storage_id='$storage_id'");

        return $Result;
}

function storageGetChartFields($account_id, $storage_id)
{
        $ArrayResult = array();

        // Get chart fields
        $Result = @mysql_query("SELECT chartfields FROM storage WHERE account_id='$account_id' AND storage_id='$storage_id'");

        // Found anything?
        $count = @mysql_num_rows($Result);
        if ($count > 0)
        {
                $Row = mysql_fetch_row($Result);

                $chartfields = $Row[0];

                $Array = array();
                $Array = explode( ";", $chartfields );

                for ($i=0; $i<count($Array); $i++)
                {
                        if (!empty($Array[$i]))
                        {
                                $ArrayResult[] = $Array[$i];
                        }
                }
        }

        return $ArrayResult;
}

function StoreFile($account_id, $formid, $RecordName, $FileName, $RecordID, $Directory, &$ResultStr, $rr_tablename="",
$DestFileName="")
{
        global        $DOCUMENT_ROOT, $DIR_DATA;

        $ArrayValues = array();

        $ArrayValues = array_merge($ArrayValues, array("rr_record_id" => $RecordID));
        $ArrayValues = array_merge($ArrayValues, array("$RecordName"."_location" => ""));

        // If no file provided,
        if (empty($FileName))
        {
                // We're done
                return true;
        }

        // If no table name,
        if (empty($rr_tablename))
        {
                $storage_id = "";

                // Get it
                $rr_tablename        = storageGetUserTableByFormID($account_id, $formid, &$storage_id);
        }

        // If not deleting file,
        if (Strcasecmp($DestFileName, "none")!=0)
        {
                // If error
                if (!empty($ResultStr))
                {
                        // Bail out
                        return false;
                }

                // If we have a directory
                if (!empty($Directory))
                {
                        // If no <DestFileName>
                        if (empty($DestFileName))
                        {
                                // Set it
                                $DestFileName = basename($FileName);
                        }

                        // Use it
                        $FileLocation = $DIR_DATA.$account_id."/".$Directory."/".$DestFileName;


//                        echo "Location = $FileLocation<BR>";
                }
                // (Else - no directory)
                else
                {
                        // Get file location based on recordID and RecordName
                        // (Ensuring it will have a unique name per each RecordID/RecordName combination)
                        $FileLocation = storageGetFileLocation($account_id, $RecordID, $RecordName, "png");
                }

                // Set these for easier access
                $FileDir = $DOCUMENT_ROOT.dirname($FileLocation);

                // Create directory if necessary
                if (!@is_dir($FileDir))
                {
                        if (!@mkdir($FileDir, 0700))
                        {
                                $ResultStr = "Failed uploading file ($FileName). Can't create directory ($FileDir)";
                                return false;
                        }
                }

                // Copy file to its new location
                if (!copy($FileName, $DOCUMENT_ROOT.$FileLocation))
                {
                        $ResultStr = "Failed uploading file ($FileName). Can't copy to ".$DOCUMENT_ROOT.$FileLocation;
                        return false;
                }

                // Set file location
                $ArrayValues = array_merge($ArrayValues, array("$RecordName"."_location" => $FileLocation));
        }

        return (storageInsertUserData($account_id, $formid, $ArrayValues, $rr_tablename) >= 0);
}

function DTImportSponsorIfNeeded($account_id, $operator_id)
{
        $aff_id = "";

        $tablenameOperators        = storageGetUserTableByFormID($account_id, "Operators", $storage_id);

        $operator = storageGetFormRecord($account_id, "Operators", $operator_id);

                $Result = mysql_query("select UpL from ChPers where SecId='".$operator['login']."'");
                $count = @mysql_num_rows($Result);
                $Row = @mysql_fetch_row($Result);

                if ($count>0 && !empty($Row))
                {
                        $sitename_sponsor = $Row[0];

                        if (!empty($sitename_sponsor) )
                        {
                                $Result = @mysql_query("SELECT rr_record_id FROM $tablenameOperators WHERE login='$sitename_sponsor'");
                                $Row = @mysql_fetch_row($Result);

                                if (!empty($Row))
                                {
                                        $aff_id = $Row[0];
                                        @mysql_query("UPDATE $tablenameOperators SET aff_id='$aff_id' WHERE rr_record_id='$operator_id'");
                                }
                                else
                                {
                                        // Import DreamTime sponsor
                                        $aff_id = DTImportUser($account_id, "Operators", $sitename_sponsor);

                                        @mysql_query("UPDATE $tablenameOperators SET aff_id='$aff_id' WHERE rr_record_id='$operator_id'");
                                }
                        }
                }

        return $aff_id;
}

function DTImportUser($account_id, $formid, $sitename)
{
        $tablename        = storageGetUserTableByFormID($account_id, $formid, $storage_id);

        // Do we have this user
        $Result = @mysql_query("SELECT rr_record_id,membertype,lastordertime FROM $tablename WHERE login='$sitename'");

//echo "SELECT rr_record_id,membertype,lastordertime FROM $tablename WHERE login='$sitename'";

        $count = @mysql_num_rows($Result);

        // Setup record
        $record['login']                = $sitename;
        $record['password']                = DTGetUserPassword($sitename);
        $record['emailaddress']        = DTGetUserEmail($sitename);
        $record['phone']                = DTGetUserPhone($sitename);
        $record['address']                = "";
        $record['name']                        = DTGetUserName($sitename);
        $record['notify_announcements'] = 'on';
        $record['notify_signup'] = 'on';
        $record['notify_downline'] = 'on';

        // If we already have this member
        if ($count > 0)
        {
                $Row = @mysql_fetch_row($Result);
                $id = $Row[0];

                if ($id>0)
                {
                        $record['rr_record_id']        = $id;
                }

                $update_membertype = empty($Row[1]);

                $update_lastorder = $Row[2] + 0 == 0;

                // We're done
                return $id;
        }
        // (Else - we don't have this member yet)
        else
        {
                // Need to update membertype
                $update_membertype = true;

                // Need to update lastorder
                $update_lastorder = true;
        }

        // If we need to update membertype
        if ($update_membertype)
        {
//                if (DTIsFreeMember($sitename))
                {
                        $record['membertype']        = "free";
                        $record['title'] = "";
                }
//                else
//                {
//                        $record['membertype']        = "basic";
//                        $record['title'] = "basic";
//                }
        }

        if (!empty($record['rr_record_id']))
        {
                if ($record['rr_record_id']+0 < 1)
                {
                        unsert($record['rr_record_id']);
                }
        }

        // Return our own user ID
        $RecordID = storageInsertUserData($account_id, "Operators", $record);

        if ($RecordID<=0)
        {
//        echo "Duplicate: ".$record['login']." - [".$record['rr_record_id']."]";
//        die;
        }
        else
        {
                $aff_id = DTImportSponsorIfNeeded($account_id, $RecordID);

//                AddUserToTheMatrix($account_id, $aff_id, $aff_id, $RecordID, 1);

                SetupUserSegments($account_id, $RecordID);
        }

//echo $record['login']."-".$record['rr_record_id'];//"[$RecordID]";

        // If we need to update lastorder
/*        if ($update_lastorder)
        {
                if (DTIsFreeMember($sitename))
                {
                }
                else
                {
                        @mysql_query("UPDATE $tablename SET lastordertime=(from_days(to_days(now()) + 15)) WHERE rr_record_id='$RecordID'");
                }
        }
*/
        // Return record ID
        return $RecordID;
}

function DTImportSponsor($account_id, $formid, $sitename, $sponsor_sitename)
{
        $tablename        = storageGetUserTableByFormID($account_id, $formid, $storage_id);

        // Do we have this user
        $Result = @mysql_query("SELECT rr_record_id FROM $tablename WHERE login='$sitename' ");

        $count = @mysql_num_rows($Result);

        // If we have this user
        if ($count>0)
        {
                $Row = @mysql_fetch_row($Result);
                $id = $Row[0];

                // Get our record
                $operator = storageGetFormRecord($account_id, "Operators", $id);

                // Get sponsor
                $aff_id = arrayvalue($operator,"aff_id");

                // If no sponsor
                if (empty($aff_id))
                {
                        // Import sponsor
                        $aff_id = DTImportUser($account_id, $formid, $sponsor_sitename);

                        if ($aff_id>0)
                        {
                                // Store aff_id
                                storageInsertUserData($account_id, $formid, array("rr_record_id"=>"$id","aff_id"=>"$aff_id"));
                        }
                }

                // Return sponsor
                return $aff_id;
        }

        // (We don't have this user)
        return "";
}

function DTGetUserPassword($login)
{
        $Result = @mysql_query("SELECT LoginPass FROM ChPers WHERE SecId='$login'");
        $Row = @mysql_fetch_row($Result);

        if (!empty($Row))
        {
                return $Row[0];
        }

        return "";
}

function DTGetUserLogin($login)
{
        $Result = @mysql_query("SELECT LoginName FROM ChPers WHERE SecId='$login'");
        $Row = @mysql_fetch_row($Result);

        if (!empty($Row))
        {
                return $Row[0];
        }

        return "";
}

function DTgenpassword($length)
{

    srand((double)microtime()*1000000);

    $vowels = array("a", "e", "i", "o", "u");
    $cons = array("b", "c", "d", "g", "h", "j", "k", "l", "m", "n", "p", "r", "s", "t", "u", "v", "w", "tr",
    "cr", "br", "fr", "th", "dr", "ch", "ph", "wr", "st", "sp", "sw", "pr", "sl", "cl");

    $num_vowels = count($vowels);
    $num_cons = count($cons);

    for($i = 0; $i < $length; $i++){
        $password .= $cons[rand(0, $num_cons - 1)] . $vowels[rand(0, $num_vowels - 1)];
    }

    return substr($password, 0, $length);
}

function DTCreateDTUser($account_id, $operator_id)
{
        global        $DREAMTIME_ACCOUNT;

        $operator = storageGetFormRecord($account_id, "Operators", $operator_id);

        if (empty($operator))
        {
                return 0;
        }

        $name = $operator['name'];
        $password = $operator['password'];
        $login = $operator['login'];
        $emailaddress = $operator['emailaddress'];
        $phone = $operator['phone'];
        $aff_id = $operator['aff_id'];

        if (!empty($DREAMTIME_ACCOUNT))
        {
                // Create Link
                system("/usr/web/users/debttofreedom/exec/makespot /bin/ln -s /usr/web/users/debttofreedom/exec/replica.pl /usr/web/users/debttofreedom/$login");

                // Get first character
                $first_char = substr(strtolower($login),0,1);

                // Add user to ChPers
                $Result = @mysql_query("INSERT INTO ChPers (SecId,LoginName,LoginPass,DlOf,UpL,UserEmail,Url,FirstName,LastName,DayPhone,NightPhone,CellPhone,Pager,Fax,Company,Title,Addr1,Addr2,City,State,Zip,Country,Prefix,Position,SSN,DistId,SignedUp,MlmRank,MxPosition,Cust1,ChargeId) ".
                " VALUES ('$login', '$login', '$password', '$login_sponsor', '$login_sponsor', 'email.htm', 'url.htm', 'first-name.htm', 'last-name.htm', 'phone.htm', 'night-phone.htm', 'cell-phone.htm', 'pager.htm', 'fax.htm', 'company.htm', 'title.htm', 'address1.htm', 'address2.htm', 'city.htm', ".
                "'state.htm', 'zipcode.htm', 'country.htm', 'prefix.htm', 'position.htm', 'ssn.htm', 0, now(), 0, 0, '', '')");
                if (!$Result)
                {
//                        echo "Failed ChPers";
//                        return false;
                }

                // Add user to ChRoot
                $Result = @mysql_query("INSERT INTO ChRoot (SecId,PriRoot,PriStart,SecRoot,ExpDate,Cust1,Cust2) VALUES ('$login', '/templates', 'index.htm', '/secondary/$first_char/$login', '' ,'' ,'')");
                if (!$Result)
                {
//                        echo "Failed CHRoot";
//                        return false;
                }

                $firstname = $name;
                if (($pos = _strpos($name, " ")) != -1)
                {
                        $firstname = substr($name, 0, $pos);
                }
                $lastname = " ";
                if (($pos = _strpos($name, " ")) != -1)
                {
                        $lastname = substr($name, $pos+1);
                }

                // Create direoctry
                system("/usr/web/users/debttofreedom/exec/makespot mkdir /usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login));
                system("/usr/web/users/debttofreedom/exec/makespot chown -R 65534:65534 /usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login));

                // Create files
                // Email
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/email.htm", "w+");
                fwrite($file, $emailaddress);
                fclose($file);
                // Phone
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/phone.htm", "w+");
                fwrite($file, $phone);
                fclose($file);
                // First-name
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/first-name.htm", "w+");
                fwrite($file, $firstname);
                fclose($file);
                // Last-name
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/last-name.htm", "w+");
                fwrite($file, $lastname);
                fclose($file);
                // Company
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/company.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // Address1
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/address1.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // Address2
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/address2.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // City
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/city.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // State
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/state.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // Zipcode
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/zipcode.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // Country
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/country.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // Prefix
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/prefix.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // Position
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/position.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // SSN
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/ssn.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // Fax
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/fax.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // Pager
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/pager.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // Night-Phone
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/night-phone.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // Cell-Phone
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/cell-phone.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // URL
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/url.htm", "w+");
                fwrite($file, "");
                fclose($file);
        }

}

function DTCreateUser($account_id, $name, $password, $login, $emailaddress, $phone, $aff_id)
{
        global        $DREAMTIME_ACCOUNT;

        $login = strtolower($login);

        if (!empty($DREAMTIME_ACCOUNT))
        {
                // Verify we don't already have this user
                $Result = @mysql_query("SELECT SecId FROM ChRoot WHERE SecId='$login'");
                $count = @mysql_num_rows($Result);
                if ($count>0)
                {
                        return true;
                }
        }

        $tablenameOperators        = storageGetUserTableByFormID($account_id, "Operators", &$storage_id);
        $Result = @mysql_query("SELECT rr_record_id FROM $tablenameOperators WHERE login='$login'");
        $count = @mysql_num_rows($Result);
        if ($count>0)
        {
                return true;
        }

        // Get sponsor record
        $sponsor = storageGetFormRecord($account_id, "Operators", $aff_id);
        $login_sponsor = arrayvalue($sponsor, 'login');

        if (!empty($DREAMTIME_ACCOUNT))
        {
                // Create Link
                system("/usr/web/users/debttofreedom/exec/makespot /bin/ln -s /usr/web/users/debttofreedom/exec/replica.pl /usr/web/users/debttofreedom/$login");

                // Get first character
                $first_char = substr(strtolower($login),0,1);

                // Add user to ChPers
                $Result = @mysql_query("INSERT INTO ChPers (SecId,LoginName,LoginPass,DlOf,UpL,UserEmail,Url,FirstName,LastName,DayPhone,NightPhone,CellPhone,Pager,Fax,Company,Title,Addr1,Addr2,City,State,Zip,Country,Prefix,Position,SSN,DistId,SignedUp,MlmRank,MxPosition,Cust1,ChargeId) ".
                " VALUES ('$login', '$login', '$password', '$login_sponsor', '$login_sponsor', 'email.htm', 'url.htm', 'first-name.htm', 'last-name.htm', 'phone.htm', 'night-phone.htm', 'cell-phone.htm', 'pager.htm', 'fax.htm', 'company.htm', 'title.htm', 'address1.htm', 'address2.htm', 'city.htm', ".
                "'state.htm', 'zipcode.htm', 'country.htm', 'prefix.htm', 'position.htm', 'ssn.htm', 0, now(), 0, 0, '', '')");
                if (!$Result)
                {
//                        echo "Failed ChPers";
                        return false;
                }

                // Add user to ChRoot
                $Result = @mysql_query("INSERT INTO ChRoot (SecId,PriRoot,PriStart,SecRoot,ExpDate,Cust1,Cust2) VALUES ('$login', '/templates', 'index.htm', '/secondary/$first_char/$login', '' ,'' ,'')");
                if (!$Result)
                {
//                        echo "Failed CHRoot";
                        return false;
                }

                $firstname = $name;
                if (($pos = _strpos($name, " ")) != -1)
                {
                        $firstname = substr($name, 0, $pos);
                }
                $lastname = " ";
                if (($pos = _strpos($name, " ")) != -1)
                {
                        $lastname = substr($name, $pos+1);
                }

                // Create direoctry
                system("/usr/web/users/debttofreedom/exec/makespot mkdir /usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login));
                system("/usr/web/users/debttofreedom/exec/makespot chown -R 65534:65534 /usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login));

                // Create files
                // Email
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/email.htm", "w+");
                fwrite($file, $emailaddress);
                fclose($file);
                // Phone
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/phone.htm", "w+");
                fwrite($file, $phone);
                fclose($file);
                // First-name
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/first-name.htm", "w+");
                fwrite($file, $firstname);
                fclose($file);
                // Last-name
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/last-name.htm", "w+");
                fwrite($file, $lastname);
                fclose($file);
                // Company
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/company.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // Address1
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/address1.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // Address2
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/address2.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // City
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/city.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // State
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/state.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // Zipcode
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/zipcode.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // Country
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/country.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // Prefix
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/prefix.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // Position
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/position.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // SSN
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/ssn.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // Fax
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/fax.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // Pager
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/pager.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // Night-Phone
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/night-phone.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // Cell-Phone
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/cell-phone.htm", "w+");
                fwrite($file, "");
                fclose($file);
                // URL
                $file = fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($login)."/url.htm", "w+");
                fwrite($file, "");
                fclose($file);
        }
        else
        {
                $password = "richandhealthy";
        }


        // Insert to 'Operators' table
        $record['name']                = "$name";
        $record['phone']        = "$phone";
        $record['emailaddress']        = "$emailaddress";
        $record['aff_id']                = "$aff_id";
        $record['login']                = "$login";
        $record['title']                = "";
        $record['password']                = "$password";
        $record['notify_announcements']        = "on";
        $record['membertype'] = 'platinum';
        $record['title'] = 'basic';
        $record['notify_signup']                = "on";
        $record['notify_downline']                = "on";
        return storageInsertUserData($account_id, "Operators", $record);
}

// Get member email from site ID
function DTGetUserEmail($id)
{
        $first_char = substr(strtolower($id),0,1);
        $file = @fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($id)."/email.htm", "r");
        //echo "/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($id)."/email.htm";

        $EmailAddress = @fread($file, 10000);
        @fclose($file);

        return $EmailAddress;
}

function DTGetUserName($id)
{
        $first_char = substr(strtolower($id),0,1);
        $file = @fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($id)."/first-name.htm", "r");
        $FirstName = @fread($file, 10000);
        @fclose($file);

        $file = @fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($id)."/last-name.htm", "r");
        $LastName = @fread($file, 10000);
        @fclose($file);

        return $FirstName . " ". $LastName;
}

function DTGetUserPhone($id)
{
        $first_char = substr(strtolower($id),0,1);
        $file = @fopen("/usr/web/users/debttofreedom/secondary/".$first_char."/".strtolower($id)."/phone.htm", "r");
        $Phone = @fread($file, 10000);
        @fclose($file);

        return $Phone;
}

function DTGetCountDownline($member_id,$level="")
{
        if (empty($level))
        {
        $Result = @mysql_query("SELECT COUNT(*) FROM tMatrix WHERE MyId='$member_id' AND Level>='1' AND Level<='8'");
        }
        else
        {
        $Result = @mysql_query("SELECT COUNT(*) FROM tMatrix WHERE MyId='$member_id' AND Level='$level'");
        }

        $Row = @mysql_fetch_row($Result);
        if (empty($Row)) return 0;
        return $Row[0];
}

function DTIsFreeMember($member_id)
{
        $Result = @mysql_query("SELECT Cust1 FROM ChPers WHERE SecId='$member_id'");

        $count = @mysql_num_rows($Result);

        if ($count<1)
        {
                return true;
        }

        $Row = @mysql_fetch_row($Result);

        $Cust1 = $Row[0];

        if (empty($Cust1))
        {
                return true;
        }

        if (_strpos(strtolower($Cust1),"free")!=-1)
        {
                return true;
        }

        return false;
}

function DTGetSignupDate($member_id)
{
        $Result = @mysql_query("SELECT SignedUp FROM ChPers WHERE SecId='$member_id'");

        $count = @mysql_num_rows($Result);

        if ($count<1)
        {
                return "";
        }

        $Row = @mysql_fetch_row($Result);

        $Signedup = $Row[0];

        if (empty($Signedup))
        {
                return "";
        }

        if (_strpos($Signedup, "-")!=-1)
        {
                return $Signedup;
        }

        $result = substr($Signedup,6,4) . "." . substr($Signedup, 0, 2) . ".". substr($Signedup, 3,2);

        return $result;
}

function GetUserSiteURL($login)
{
        global        $ACCOUNT_TYPE;

        switch ($ACCOUNT_TYPE)
        {
                case $ACCOUNT_DTF:
                        return "http://www.".GetFullServerName()."/$login";
                break;

                case $ACCOUNT_MLM:
                        return "http://www".GetFullServerName()."/?id=$login";
                break;
        }

        return "";
}

function IsPaid($lastordertime)
{
        if (empty($lastordertime) || Strcasecmp($lastordertime,'00000000000000')==0)
        {
                return 0;
        }

        $Result = @mysql_query("SELECT (TO_DAYS('$lastordertime')+30) < (TO_DAYS(now())+0)");
        $Row = @mysql_fetch_row($Result);

        if (Strcasecmp($Row[0],"1")==0)
        {
                return 0;
        }

        return 1;
}

?>
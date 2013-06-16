<?php
require_once("database.php");

function accountOpen()
{
        // If account table doesn't exist,
        if (!mysql_query("SELECT account_name FROM account LIMIT 1"))
        {
                // Create table
                if (!mysql_query("CREATE TABLE account (
                                                        account_name VARCHAR(32),

                                                        rr_record_id MEDIUMINT(10) DEFAULT '1' NOT NULL AUTO_INCREMENT,
                                                        rr_emailsent SMALLINT DEFAULT '0' NOT NULL,
                                                        rr_synchronized SMALLINT DEFAULT '0' NOT NULL,
                                                        rr_createdate DATE DEFAULT '0000-00-00' NOT NULL,
                                                        rr_lastaccess TIMESTAMP(14),

                                                        adminname VARCHAR(32) NOT NULL,
                                                        password VARCHAR(20) BINARY NOT NULL,
                                                        phone VARCHAR(32),
                                                        fax VARCHAR(32),
                                                        address VARCHAR(255),
                                                        email VARCHAR(50) NOT NULL,
                                                        website VARCHAR(50),
                                                        image_location VARCHAR(255) DEFAULT '' NOT NULL,

                                                        status SMALLINT DEFAULT '0',

                                                        type SMALLINT DEFAULT '0' NOT NULL,

                                                        PRIMARY KEY (rr_record_id),
                                                        UNIQUE rr_record_id (rr_record_id),
                                                        UNIQUE account_name (account_name)
                                                )"))
                {
                        die ("Database fatal error: Account table creation");
                }
        }

        // (If we're up to here - [account] table exists)
        // Return success
        return true;
}

function accountGetName($account_id)
{
        // Load account record
        $account = storageGetUserDataRecord(0,0,$account_id,"account");

        $AccountName = NonEmpty(htmlarrayvalue($account, 'company_name'), htmlarrayvalue($account, 'account_name'));

        if (empty($AccountName))
        {
                $AccountName = '';
        }

        return $AccountName;
}

function accountGetType($account_id)
{
        // Load account record
        $account = storageGetUserDataRecord(0,0,$account_id,"account");

        return arrayvalue($account,'type')+0;
}

function accountClose()
{
        return true;
}

function accountCreateNew()
{
        // Create account
        if (mysql_query("INSERT INTO account (rr_createdate) values (date_format(now(), '%Y-%m-%d %H:%i:%s'))"))
        {
                return @mysql_insert_id();
        }


        return -1;
}

function accountIsActive($account_id)
{
        $Result = @mysql_query("SELECT * FROM account WHERE rr_record_id = '$account_id' AND status = '1'");

        // Found anything?
        $count = @mysql_num_rows($Result);
        if ($count < 1)
        {
                return false;
        }

        return true;
}

function accountDelete($account_id)
{
        // Get all storages for this account
        $ArrayResult = storageGetList($account_id);

        // Delete all storages
        for ($i=0; $i<count($ArrayResult)-1; $i++)
        {
                $Item = $ArrayResult[$i+1];

                // Delete storage
                if (storageDelete($account_id, $Item['storage_id']))
                {
//                        echo "Deleted ".$Item['storage_id'];
                }
        }

        // Delete account
        @mysql_query("DELETE FROM account WHERE rr_record_id = '$account_id'");
}

function accountCreate($account_id, $account)
{
        // Add slashes
        @array_walk(&$account, "AddSlashesToArray", 0);

        $IMAGE_LOCATION_VALUE = "";
        $IMAGE_LOCATION_DEF = "";
        if (isset($account['image_location']))
        {
                $IMAGE_LOCATION_DEF = "image_location,";
                $IMAGE_LOCATION_VALUE = "'$account[image_location]',";
        }

        // Validate
        if (empty($account['account_name']))
        {
                return false;
        }

        // Create account
        return        mysql_query("INSERT INTO account (account_id, account_name, createdate, adminname,
                                                password, phone, fax, email, address, ".$IMAGE_LOCATION_DEF." website)
                                                VALUES ('$account_id', '$account[account_name]', date_format(now(), '%Y-%m-%d %H:%i:%s'),
                                                '$account[adminname]', Password('$account[password]'), '$account[phone]', '$account[fax]', '$account[email]', '$account[address]', ".$IMAGE_LOCATION_VALUE." '$account[website]')");//,
//                                                '$account[getcategories]')");

}

function accountGetLoginByEmail($email, $account_type, &$account_name, &$username, &$password, &$email_addr_send)
{
        // Initialize
        $result                        = false;
        $account_name        = array();
        $username                = array();
        $password                = array();

        // Get account list
        $fix = "";
        if ($account_type+0 == 0)
        {
                $fix = "or type is NULL";
        }
        $Result = @mysql_query("SELECT rr_record_id,account_name FROM account WHERE type='$account_type' $fix");
        $count = @mysql_num_rows($Result);

        // Iterate through all accounts,
        for ($i=0; $i<$count; $i++)
        {
                $Row                        = mysql_fetch_row($Result);
                $account_id                = $Row[0];

                // Get operators records
                $ArrayResult = storageGetUserDataList($account_id, "Operators", "", "", "", "", "", "emailaddress='$email' or login='$email'");

                // Search operator record
                for ($j=0; $j<count($ArrayResult)-1; $j++)
                {
                        $Item = $ArrayResult[$j+1];

                        // Add match
                        $account_name[]        = $Row[1];
                        $username[]                = $Item['login'];
                        $password[]                = $Item['password'];
                        $email_addr_send                = $Item['emailaddress'];
                        // We had a match
                        $result = true;
                }
        }

        // Return result
        return $result;
}

function accountLogin($account_name, $username, $password, &$ErrorStr, &$account_id, &$operator_id)
{
        $account_name        = _addslashes($account_name);
        $username                = _addslashes($username);
        $password                = _addslashes($password);

        // Reset
        $account_id                = 0;
        $operator_id        = 0;

        // Do we an account with this name?
        $Result = @mysql_query("SELECT rr_record_id FROM account
                                                        WHERE account_name='$account_name'");

        // If didn't find anything
        if (@mysql_num_rows($Result) == 0)
        {
                $ErrorStr = "Account <b>[$account_name]</b> does not exist on our servers.";
                return false;
        }

        // Get account_id
        $Row = mysql_fetch_row($Result);
        $account_id = $Row[0];

        // Do we have a match?
        $tableOperators        = storageGetUserTableByFormID($account_id, "Operators", $storage_id);
        $Result1 = @mysql_query("SELECT rr_record_id FROM $tableOperators WHERE login='$username' AND ".
                           "password='$password'");

        // Do we have a match
        if (@mysql_num_rows($Result1) == 0)
        {
                $ErrorStr = "The Login, Password combination you specified for the account <b>[$account_name]</b> is incorrect.";
                return false;
        }

        // Set returned parameters
        $Row1 = mysql_fetch_row($Result1);
        $operator_id = $Row1[0];

        // Return success
        return true;
}

function accountSetRecord($account_id, $account)
{
        // Add slashes
        @array_walk(&$account, "AddSlashesToArray", 0);

        $IMAGE_LOCATION_STR = "";
        if (isset($account['image_location']))
        {
                $IMAGE_LOCATION_STR = "image_location = '$account[image_location]',";
        }

        // Set account record
        $Result         = @mysql_query("UPDATE account SET account_name = '$account[account_name]',
                                        phone = '$account[phone]',
                                                                fax = '$account[fax]',".
                                                                $IMAGE_LOCATION_STR.
                                        "email = '$account[email]',
                                                                address = '$account[address]',
                                        website = '$account[website]' WHERE account_id = $account_id");


        return ($Result);
}

function accountGetImageLocation($account_id, $type)
{
        global $DIR_ACCOUNT, $DOCUMENT_ROOT;

        return $DIR_ACCOUNT."acct".$account_id.".".$type;
}

function accountIsValidType($type)
{
        switch ($type)
        {
                case "jpeg":
                case "pjpeg":
                case "gif":
                case "bmp":
                        return true;
                break;

                default:
                        return false;
                break;
        }
}

?>
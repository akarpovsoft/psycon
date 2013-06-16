<?php
require_once("common/database.php");

$dblink = dbhandle();


Header("Cache-Control: no-cache, must-revalidate");
Header("Pragma: no-cache");
Header("Expires: Tue, Jan 12 1999 01:01:01 GMT");

$make_request=0;
        if (empty($stop))
        {
                $stop = "false";
        }

        if (Strcasecmp($stop,"true")==0)
        {
                //@mysql_query("DELETE FROM $tableChatRequests WHERE client_id='$client_id' and reader_id='$reader_id'");
        }
        else
        {
                $make_request = 1;
$Result = @mysql_query("SELECT rr_record_id FROM $tableChatSessions where reader_id='$reader_id' AND (to_days(now())=to_days(laststatusupdate) and (time_to_sec(now()) - time_to_sec(laststatusupdate) < 10))");
$count = @mysql_num_rows($Result);
if ($count<1) //Reader is not busy
{
@mysql_query("UPDATE $tableChatRequests SET laststatusupdate=now(),reader_id='$reader_id',subject='$subject' WHERE rr_record_id='$request_id'");
}
@mysql_free_result($Result);
                }
//$_GET[client_id]

       // Do we have a Reader Waiting for us now?
        //$Result = @mysql_query("SELECT rr_record_id FROM $tableChatSessions WHERE type='reader' AND reader_id='$reader_id' AND client_id='$client_id' AND (to_days(now())=to_days(laststatusupdate) and (time_to_sec(now()) - time_to_sec(laststatusupdate) < 10))");
$Result = @mysql_query("SELECT rr_record_id FROM $tableChatSessions WHERE type='reader' AND reader_id='$reader_id' AND client_id='$client_id' AND (to_days(now())=to_days(laststatusupdate) and (time_to_sec(now()) - time_to_sec(laststatusupdate) < 10))");

        $count = @mysql_num_rows($Result);
@mysql_query("UPDATE $tableChatRequests SET laststatusupdate=now(),reader_id='$reader_id',subject='$subject' WHERE rr_record_id='$request_id'");
//$count = 1;
        if ($count>0)
        {
                $Row = @mysql_fetch_row($Result);
                $Image = ImageCreate(2,2);
 //@mysql_query("UPDATE $tableChatRequests SET laststatusupdate=now(),reader_id='$reader_id',subject='$subject' WHERE rr_record_id='$request_id'");
        }
        else
        {
                $Image = ImageCreate(1,1);
        }
@mysql_free_result($Result);

        ImageJPeg($Image);

        ImageDestroy($Image);

mysql_close( $dblink ) ;
?>
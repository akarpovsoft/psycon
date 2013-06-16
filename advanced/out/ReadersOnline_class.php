<?php
if(file_exists('config/config.php'))
{
    require_once("config/config.php");
}

class ReadersOnline
{
    private $sharedConnection, $localConnection, $readerId;

    function __construct()
    {

    }

    function __destruct()
    {
        if(isset($this->localConnection))
        {
            @mysql_close($this->localConnection);
        }
    }
    
    function readerIsInGroup($reader_id, $group_id)
	{
		$this->connectToDb();
	
		$sql = "SELECT * FROM `site_forbidden_readers` WHERE `reader_id` = ".$reader_id." and `group_id` = ".$group_id;	

		$q = mysql_query($sql);
	
		return mysql_fetch_row($q)?'false':'true';
	}

    public function availableReaders($online_only, $category, $group_id = false, $force_online = false)
    {
        $this->connectToDb();
        
		$busy=array();
		$available=array();
		$break=array();
		$offline=array();
		
		$aux_where="";
		if($category=='tarot')
			$aux_where="AND T1_1.tarotreaders='on'";
		elseif($category=='clairvoyance')
			$aux_where="AND T1_1.clairvoyants='on'";
		elseif($category=='atrology')
			$aux_where="AND T1_1.astrologers ='on'";
		elseif(isset($category))
			$aux_where="AND T1_1.area LIKE '%".$category."%'";
		
		$strsql = "SELECT * from T1_1 left join week_online_time on reader_id = T1_1.rr_record_id where type = 'reader' ".$aux_where." order by time_online desc";
		$rs = mysql_query($strsql) or die(mysql_error());
		while ($row = mysql_fetch_assoc($rs))
		{
			
			if (isset($group_id)) 
			{
			    if($this->readerIsInGroup($row['rr_record_id'], $group_id) == 'true') {

					$statusOfReader = $this->ifReaderOnline($row['rr_record_id']);
				
					$row['status']=$statusOfReader;
			
					if('offline' == $statusOfReader)
					{
						if($online_only)
							continue;
				
						$offline[]=$row;
					}
			
					if($statusOfReader=='busy')
					{
						$busy[]=$row;
					}
		
					if($statusOfReader=='online')
					{
						$available[]=$row;
					}
		
					if($statusOfReader=='break')
					{
						$break[]=$row;
					}
				}
				/*elseif ($force_online) {
				    $statusOfReader = $this->ifReaderOnline($row['rr_record_id']);
				    $row['status']=$statusOfReader;
				    
				    if('offline' != $statusOfReader) {
				        $available[]=$row;  
				    }
				}*/
			}
			else {
			
				$statusOfReader = $this->ifReaderOnline($row['rr_record_id']);
				
				$row['status']=$statusOfReader;
			
				if('offline' == $statusOfReader)
				{
					if($online_only)
						continue;
				
					$offline[]=$row;
				}
			
				if($statusOfReader=='busy')
				{
					$busy[]=$row;
				}
		
				if($statusOfReader=='online')
				{
					$available[]=$row;
				}
		
				if($statusOfReader=='break')
				{
					$break[]=$row;
				}
			}
		}
		
		$readers = array_merge($busy, $available, $break, $offline);
		
    	return $readers;
    }
    
   
    public function ifReaderOnline($reader_id=false)
    {
        $this->connectToDb();

        if($reader_id!=false)
        	$this->readerId=$reader_id;
        	
        if($this->readerId < 1)
        {
            return;
        }

        $strsql = 'SELECT rr_record_id FROM T1_12 where reader_id = \''.$this->readerId.'\' and  (DATE_ADD(laststatusupdate, interval 10 second) > now())';
        $rs = mysql_query($strsql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($rs))
        {
            $status = 'busy';
        }
        mysql_free_result($rs);

        if(empty($status))
        {
            $strsql = 'SELECT status FROM T1_1 where rr_record_id = \''.$this->readerId.'\' and type = \'reader\' and (DATE_ADD(laststatusupdate, interval 10 second) > now())';
            $rs = mysql_query($strsql) or die(mysql_error());
            while ($row = mysql_fetch_assoc($rs))
            {
               $status = 'online';
               if(ereg('break', $row[status]))
               {
                   $status = 'break';
               }
            }
            mysql_free_result($rs);
        }

        $strsql = 'SELECT Wp_status from wp_readers where Reader_id = \''.$this->readerId.'\' and Wp_status = \'wponly\'';
        $rs = mysql_query($strsql) or die(mysql_error());
        while ($row = mysql_fetch_assoc($rs))
        {
            $status = 'offline';
        }
        mysql_free_result($rs);



        if(empty($status))
        {
           $status = 'offline';
        }

        return($status);
    }

    public function setGlobalDbConnection(&$conn)
    {
        $this->sharedConnection = $conn;
    }

    private function connectToDb()
    {
        if(isset($this->sharedConnection))
        {
            return;
        }

        global $dbhost, $dbusername, $dbuserpassword, $default_dbname;
        $this->localConnection = mysql_connect($dbhost, $dbusername, $dbuserpassword);
        mysql_select_db($default_dbname);
        $this->sharedConnection = &$this->localConnection;
    }

    public function setReaderId($id)
    {
        $this->readerId = $id;
    }

    public function getStatusImage($status, $reader_id)
    {

        if('offline' == $status)
        {            $src = "http://www.psychic-contact.com/chat/images/status_offline.png";
            //readfile($src);
        }
        else if('online' == $status)
        {        	$src = "http://www.psychic-contact.com/chat/images/status_online.png";
        	//readfile($src);
        }
        else if('break' == $status)
        {            $src = "http://www.psychic-contact.com/chat/images/status_break_dyn.jpg";
            //readfile($src);
        }
        else if('busy' == $status)
        {            $strsql = "SELECT Balance from Ping_chat where Reader = '".$reader_id."' order by Reader_ping desc limit 0, 1";
            $rs = mysql_query($strsql) or die(mysql_error());
            while ($row = mysql_fetch_assoc($rs)){
	            $Balance_rest = $row[Balance];
    	        $Balance_rest_min = round($Balance_rest/60);
            }
            mysql_free_result($rs);

            $src = "http://www.psychic-contact.com/chat/statusimage.php?time=".$Balance_rest_min;
        }
        return $src;
    }

    public function drawNotAvailImage($period = '0', $img = 'images/status_busy_dyn.jpg')
    {
        global $readimg;
        $readimg = false;

        $h_begin = 20;


        $name    = "Back in $period mins";


        if(!isset($quality)){
        $quality = 100;
        }

        $r = 255;
        $g = 255;
        $b = 255;


        //Header("Content-type: image/jpeg");
        $im = imagecreatefromjpeg ("$img");
        $w= imagesx($im);
        $h= imagesy($im);
        $tx_col = imagecolorallocate($im, $r, $g, $b);

        $beginw_name=$w_begin;
        $beginh_name=$h_begin;
        $szf = 7.5;
        $ang=0;
        if(isset($bold)){
        $font = "fonts/ariblk.ttf";
        }
        else{
        $font = "fonts/ariblk.ttf";
        }

        $sz=imagettfbbox($szf, $angl, $font, $name);

        $beginw_name = $sz[4]/2;
        $beginw_name = $w/2-$beginw_name;

        //$beginh_name = $sz[1]/2;
        //$beginh_name = $h/2-$beginh_name;
        $beginh_name = $beginh_name;
        if($period > 0)
        {
        imagettftext($im, $szf, $ang, $beginw_name, $beginh_name, $tx_col, $font, $name);
        }
        Imagejpeg($im, '', $quality);
        ImageDestroy($im);
    }

}

?>
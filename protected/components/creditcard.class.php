<?php
class CreditCard
{
	private $db;
	private $password = 'password';
	private $addMD5_1 = "(u77.";
	private $addMD5_2 = "/-4-da";
						  
        function __construct()
        {
		$this->db = PsyDB::instance();
        }
    
	public function addCreditCard($userId, $cardNumber, $extraData)
        {
		$view1	= $cardNumber[0];
		$view2	= substr($cardNumber, -4);

               
		$hash2	= substr($cardNumber, 1, strlen($cardNumber)-5);
		
		$md5Code = md5($addMD5_1.$cardNumber.$addMD5_2);
		
		$data = array(
			'client_id' => "$userId",
			'hash1' => "'$md5Code'",
			'view1' => "'$view1'",
			'view2' => "'$view2'",
                        'hash2' => "AES_ENCRYPT( '$hash2', '$this->password' )"
			);

		if (is_array($extraData))
			$data = array_merge($data, $extraData);
		
		$fieds = '';
		$values = '';
		$flag = 0;

            

		foreach($data as $key => $val)	
		{
			if($flag == 1)
			{
				$fields .= ", ";
				$values .= ", ";
			}
			else $flag = 1;
			
			$fields .= "`$key`";
			$values .=  "$val";
		}
			
			
		$sql	= "replace INTO `user_location` ( $fields )
				VALUES ( $values )";
		
		
		$this->db->query($sql);
               /// echo  $sql.'<br>';
                
		return 1;
	
	}

        public function addCreditCardInHistory($userId, $cardNumber)
        {
                $md5Code = md5($this->addMD5_1.$cardNumber.$this->addMD5_2);

		$data = array(
                        'record_id' => '',
			'client_id' => "$userId",
			'hash1'     => "'$md5Code'",
			'view1'     => "$view1",
			'view2'     => "$view2",
		);

                $fieds = '';
		$values = '';
		$flag = 0;

		foreach($data as $key => $val)
		{
			if($flag == 1)
			{
				$fields .= ", ";
				$values .= ", ";
			}
			else $flag = 1;

			$fields .= "`$key`";
			$values .=  "$val";
		}


		$sql	= "insert INTO `user_location_history` ( $fields )
				VALUES ( $values )";


		$this->db->query($sql);
                /// echo  $sql.'<br>';

		return 1;

        }
	
	public function editCreditCard($userId, $cardNumber, $extraData = false)
	{
		// put in history table
		$sql = "insert into `user_location_history` (`client_id` ,`hash1` )
				(SELECT  `client_id` ,`hash1`   FROM `user_location` WHERE client_id = $userId)";
		
                /// echo '<br>'.$sql;
                $user = new PsyUser($userId);
                $userData = $user->load();
                $login = $userData['login'];

		///$this->db->query($sql);
		
		// edit user location
                if (!$extraData)
                {
                    $newData = array();
                    $sql = "SELECT  `client_id` ,`hash1` ,`view1` ,`view2` ,`firstname` ,`lastname` ,`billingaddress` ,`billingcity` ,`billingstate` ,`billingzip` ,`billingcountry` ,`month` ,`year`, `card`  FROM `user_location` WHERE client_id = $userId)";

                    $rs = $this->db->query($sql);

                    $extraData = $rs->fetch('PDO::FETCH_ASSOC');

                   

                    unset($extraData['view1']);
                    unset($extraData['view2']);
                    unset($extraData['hash1']);
                    unset($extraData['hash2']);
                }

                $view1	= $cardNumber[0];
		$view2	= substr($cardNumber, -4);
		
		$hash2	= substr($cardNumber, 1, strlen($cardNumber)-5);
		
		$md5Code = md5($addMD5_1.$cardNumber.$addMD5_2);

		$sql	= "replace `user_location` SET `client_id` = $userId, `hash1` = '$md5Code',`view1` = '$view1' ,`view2` = '$view2' ,`hash2` =  AES_ENCRYPT( '$hash2', 'password' )";
		 
		if(is_array($extraData))
		{
			foreach ($extraData as $key => $val)
			{
				$sql .= ", `$key` = $val ";
			}
		}
	
		$this->db->query($sql);
                /// echo '<br>'.$sql;

		
	}

	function adminSearch($view2, $searchInHistory = false)
        {
            if($searchInHistory == 'true')
                $sql_add = "_history";

            $sql	= "select * from user_location$sql_add where `view2` like '%$view2%'";
		
            $rs		= $this->db->query($sql);
		
            $creditcards = array();
		
            while($row = $rs->fetch())
            {
			$creditcards[] = $row;
            }
		 
            return $creditcards;
		
	}
	
	function getCCForClient($clientId)
	{
		$sql	= "select *, AES_DECRYPT(`hash2`, '".$this->password."') as view3 from user_location where client_id = $clientId";
		$rs	= $this->db->query($sql);
		
		$data = $rs->fetch();
		
                $data['cardnumber'] = $data['view1'] . $data['view3'] . $data['view2'];
                  
		return $data;
	}
	
	function searchCard($cardNumber)
	{
		$md5Code = md5($addMD5_1.$cardNumber.$addMD5_2);
		$sql = "SELECT *  FROM `user_location` WHERE `hash1` = '$md5Code' UNION SELECT *  FROM `user_location_history` WHERE `hash1` = '$md5Code'";
		
		$rs = $this->db->query($sql);

                if ($rs->rowCount > 0)
                {
                    $data = array();
                    while($row = $rs->fetch())
                    {
                            $data[] = $row;
                    }

                    return $data;
                }

                return NULL;
	}


        public function  getCardType($card_name)
        {
            $cards = array(
                "Visa"              => "1",
                "Mastercard"        => "2",
                "American Express"  => "3",
                "Discover"          => "4",
            );

            return $cards[$card_name];
        }

        public function  getCardTitle($type)
        {
            $cards = array(
                "1" => "Visa",
                "2" => "Mastercard",
                "3" => "American Express",
                "4" => "Discover",
            );

            return $cards[$type];
        }


        function saveCcLog($user_id = 0, $user_name = '', $cc_numb)
        {
            $cc_numb   = ereg_replace(" ", "", $cc_numb);
            $user_id   = (int)$user_id;
            $cc_numb   = mysql_escape_string($cc_numb);
            $user_name = mysql_escape_string($user_name);

            $duuplicate = 0;

            $strsql = 'SELECT * from user_cc where user_id = \''.$user_id.'\' and cc_numb = \''.$cc_numb.'\'';
            $rs = mysql_query($strsql, $GLOBALS['conn']) or die(mysql_error());
            while ($row = mysql_fetch_assoc($rs))
            {
                $duuplicate = 1;
                break;
            }
            mysql_free_result($rs);


            if(!$duuplicate)
            {
                $mysql = 'INSERT INTO user_cc (record_id, user_id, user_name, cc_numb, date)
                VALUES(\'\', \''.$user_id.'\', \''.$user_name.'\', \''.$cc_numb.'\', NOW())';
                mysql_query($mysql, $GLOBALS['conn']);
            }
            else
            {

            }
        }

}

?>
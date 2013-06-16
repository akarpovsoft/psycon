<?php
/**
 * Class to compute and return time to users
 * 
 * @author Den Kazka den.smart[at]gmail.com
 * @since 2011
 * 
 */
class ReturnTime
{    
    private $session_info; // All nessessary info about session
    
    public $time; // Time added to user
    public $db; // DB instance
    
    public function __construct($info,$time) 
    {
        $this->session_info = $info;
        $this->db = Yii::app()->db;
        $this->time = $time;
    }
    
    /**
     * Return true if session from prevous half of month
     * 
     * @return boolean 
     */
    public function ifSessionOld()
    {
        $period_start = (date("d") > 15)? strtotime(date('Y').'-'.date('m').'-15') : strtotime(date('Y').'-'.date('m').'-01');
        $ssn_date = strtotime($this->session_info['Date']);

        return ($period_start > $ssn_date);
    }
    
    /**
     * Is current session old, recrease Due to reader and reader payment sum
     * Send emails and messages using messages center tool
     * 
     * @return boolean
     */
    public function reduceCurrentSession() 
    {
        //Looking for sessions with so due_to_reader   
        $sql = 'SELECT `n`.*, o.`Rate` rate FROM `History` n,`History` o  
                         WHERE n.`Client_id` = o.`Client_id` AND 
                               n.`Reader_id` = o.`Reader_id` AND
                               n.`Date`>"'.date('Y-m-d',$this->session_info['Date']).'" AND 
                               n.`Due_to_reader`>= o.`Rate`*'.$this->time.' AND o.`Session_id`='.$this->session_info['session_id'];

        $command = $this->db->createCommand($sql);  
        
        $res = $command->query();
        unset($command);
        
        foreach($res as $row) 
        {            
           $history = ChatHistory::getSession($row['Session_id']);
         
           if($history)
           {
            $history->Due_to_reader -= $row['Rate']*$this->time;
            $history->save();
           }
           $readerP = ReaderPayment::findBySessionId($row['Session_id']);
           if($readerP)
           {
            $readerP->Sum -= $row['Rate']*$this->time;
            $readerP->save();
           }
           
           //Notify reader
           $userdata = array();
           $sql = 'SELECT r.`name` rname,r.`emailaddress` remail,r.`login` rlogin,h.`Reader_id` rid FROM `T1_1` r
                                      INNER JOIN `History` h ON h.`Reader_id` = r.`rr_record_id`
                              WHERE h.`Session_id` = '.$this->session_info['session_id'];
           $command = $this->db->createCommand($sql);           
           $stmt = $command->query();
           unset($command);
           
           foreach($stmt as $row1) 
           {
               // Send message to the reader about add time using messages center tool
               $this->notifyReader($row1['rid'], $row1['rname'], $row['Session_id']);
               
                //Send Notification Email to reader
                $to_name = $row1['rname'];
                $subject = "PSYCHAT - You have received a message";
                $message_reader = "Hello $to_name, You have received a letter in your message center at www.psychic-contact.com/chat <br> --------- <br> 
                            Visit Our Chat Site: (www.psychic-contact.com)<br>
                            Free Tarot Readings (www.taroflash.com)<br>
                            Contact Cust. Support (javachat@psychic-contact.com)<br>";
                PsyMailer::send($row1['remail'], $subject, $message_reader);

            }
           return true;
        }
        return false;
    }
    
    /**
     * Correct time in chat history table
     */
    public function changeHistory() 
    {
        $history = ChatHistory::getSession($this->session_info['session_id']);
        $history->Free_time += $this->time;
        $history->Paid_time -= $this->time;
        $history->Due_to_reader -= $history->Rate * $this->time;
        $history->Returned_time += $this->time;
        $history->save();
    }
    
    /**
     * Correct sum in reader payments
     */
    public function reduceReaderPayment() 
    {
        $readerP = ReaderPayment::findBySessionId($this->session_info['session_id']);
        if($readerP)
        {
            $history = ChatHistory::getSession($this->session_info['session_id']);        
            $readerP->Sum = $history->Due_to_reader;
            $readerP->save();
        }
    }
    
    /**
     * Correct reader balance
     */
    public function changeReaderBalance() 
    {
        $reader = Readers::getReader($this->session_info['reader_id']);
        $reader->balance += $this->time;
        $reader->save();
    }
    
    /**
     * Correct client balance
     */
    public function changeClientBalance() 
    {
        $client = Clients::getClient($this->session_info['client_id']);
        $client->balance += $this->time;
        $client->save();        
    }
    
    /**
     * Send notifycations to reader and client using email and messages center tool
     */
    public function notifyUsers($free = null) 
    {
        // Get info about client and reader
        $reader = Readers::getReader($this->session_info['reader_id']);
        $client = Clients::getClient($this->session_info['client_id']);
        
        //Send Internal Message to reader and client	
        $this->sendInternalMessage($client, $reader, $free);

        //Send Notification Email
        $subject = "PSYCHAT - You have received a message";
        //TO CLIENT
        $to_name = $client_name."/".$client->login;
        $body = "Hello $to_name, You have received a letter in your message center at www.psychic-contact.com/chat \n --------- \n $message_full \n 
            Visit Our Chat Site: (www.psychic-contact.com)<br>
            Free Tarot Readings (www.taroflash.com)<br>
            Contact Cust. Support (javachat@psychic-contact.com)";
        PsyMailer::send($client->emailaddress, $subject, $body);
        
        //TO READER
        $to_name = $row['rname']."/".$reader->login;
        $body = "Hello $to_name, You have received a letter in your message center at www.psychic-contact.com/chat \n --------- \n $message_full \n 
            Visit Our Chat Site: (www.psychic-contact.com)<br>
            Free Tarot Readings (www.taroflash.com)<br>
            Contact Cust. Support (javachat@psychic-contact.com)";
        PsyMailer::send($reader->emailaddress, $subject, $body);	
    }
      
    public function changeFreeTime() {
        $freeTime = RemoveFreetime::getFreeTime($this->session_info['client_id']);
        $freeTime->Seconds += $this->time;
        $freeTime->Total_sec += $this->time;
        $freeTime->save();
    }
    
    
    /**
     * Sends message for reader 
     * 
     * @param integer $id Reader id
     * @param string $name Reader name
     * @param integer $ssid Session id
     */
    private function notifyReader($id, $name, $ssid)
    {        
        $subject = 'Add lost time';
        $body = 'Hello '.$name."\n".
                'You have added '.$this->time.' minutes back for session # '.$this->session_info['session_id']."\n".
                'Instead of this session, your payment will be reduced for session # '.$ssid."\n";
        
        $message = new Messages();
        $message->From_user = $id;
        $message->From_name = $name;
        $message->To_user = $id;
        $message->Subject = $subject;
        $message->Body = $body;
        $message->send();
    }
    
    /**
     * Sends mesages to reader and client about time addition
     * 
     * @param mixed $client
     * @param mixed $reader 
     */
    private function sendInternalMessage($client, $reader, $free)
    {     
        
        $subject = strtoupper($free)." Time added (".$this->time." min), Session # ".$this->session_info['session_id'];
        
        $message = new Messages();
        $message->From_user = $reader->rr_record_id;
        $message->From_name = $reader->name;
        $message->Subject = $subject;

        $tmp = explode(" ", $client->name);
        $client_name = $tmp[0];        
        
        $body = "Dear ".$reader->name."<br>".
                "You add to client ".$client->name." (".$client->login.") ".$this->time." mins on ".$this->session_info['Date']."<br>".
                " thank you for choosing Psychic Contact.";
        $message->To_user = $reader->rr_record_id;
        $message->Body = nl2br($body);
        $message->send();
        
        $body = "Dear ".$client_name."/".$client->login."<br>".
                " ".$reader->login." has added ".$free." ".$this->time." mins back to your account on ".$this->session_info['Date']."<br>".
                " thank you for choosing Psychic Contact.";
        $message->To_user = $client->rr_record_id;
        $message->Body = nl2br($body);
        $message->send();
        
        // Send email to admin		
        PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $body);  
    }
    
}
?>

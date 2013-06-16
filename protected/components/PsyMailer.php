<?php
/**
 * PsyMailer class
 *
 * Send emails to specific address
 */
class PsyMailer {
    /**
     * Email sending function
     * 
     * @param string $emailaddress
     * @param string $subject
     * @param string $body
     */
    public static function send($emailaddress, $subject, $body) {
        $headers = "From: ".Yii::app()->params['siteName']."<".Yii::app()->params['adminEmail'].">\r\nContent-Type: text/html\r\n";
        if(isset(Yii::app()->params['adminEmail']))
        	mail($emailaddress, $subject, $body, $headers);
    }
    /**
     * Sending emails to all readers
     *
     * @param string $subject
     * @param string $body
     */
    public static function sendToAllReaders($subject, $body){
        $readers = users::model()->findAll(array(
            'condition' => 'type = :type',
            'params' => array(':type' => 'reader')
        ));
        $headers = "From: ".Yii::app()->params['siteName']."<".Yii::app()->params['adminEmail'].">\r\nContent-Type: text/html\r\n";
        $time_now =date("M j, Y h:i a", time());
        if(isset(Yii::app()->params['adminEmail'])) {
	        foreach($readers as $reader){
	           mail($reader->emailaddress, $subject, $body, $headers);
	        }
        }
    }
    
     /**
     * Sending emails to all OFFLINE readers
     *
     * @param string $subject
     * @param string $body
     */
    public static function sendToAllOfflineReaders($subject, $body){
        $readers = Readers::model()->findAll(array(
            'condition' => 'type = :type',
            'params' => array(':type' => 'reader')
        ));
        $headers = "From: ".Yii::app()->params['siteName']."<".Yii::app()->params['adminEmail'].">\r\nContent-Type: text/html\r\n";
        $time_now =date("M j, Y h:i a", time());
        if(isset(Yii::app()->params['adminEmail'])) {
	        foreach($readers as $reader){
	           if ($reader->getStatus()=='offline') {
	               mail($reader->emailaddress, $subject, $body, $headers);
                   }
	        }
        }
    }
}
?>

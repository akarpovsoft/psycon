<?php
/*
 * Model Tariff
 *
 * Return payment tariffs for different reading types
 *
 */
class Tariff
{
    /**
     * Array with chat tariffs
     *
     * @var array
     */
    public static $chat_tariff_array = array(
/////// signup tariffs (extra 10 mins on signup) /////////////////////    
	'1' 	=> array('title' => '10 Minutes Live Psychic Chat Reading',
                         'amount' => '12.99',
                         'minutes' => '10', 'signupOnly' => 1, 'freeTime' => 10, 'freebie' =>1),
	'2' 	=> array('title' => 'NEW 2009 SPECIAL!: 20 mins',
                         'amount' => '21.99',
                         'minutes' => '20',
                         'style' => 'font-weight:bold; color:#880000', 'signupOnly' => 1, 'freeTime' => 10),
/////// repeater tariffs (same without 10 mins) /////////////////////    
	'7' 	=> array('title' => '10 Minutes Live Psychic Chat Reading',
                         'amount' => '12.99',
                         'minutes' => '10', 'repeaterOnly' => 1), //freebie adds 10 mins
	'8' 	=> array('title' => 'NEW 2009 SPECIAL!: 20 mins',
                         'amount' => '21.99',
                         'minutes' => '20',
                         'style' => 'font-weight:bold; color:#880000', 'repeaterOnly' => 1),
/////// regular tariffs (always extra 10 mins) /////////////////////    
	'3' 	=> array('title' => '15 Minutes Live Psychic Chat Reading',
                         'amount' => '32.99',
                         'minutes' => '25', 'freeTime' => 10),
	'4' 	=> array('title' => '30 Minutes Live Psychic Chat Reading',
                         'amount' => '44.99',
                         'minutes' => '40', 'freeTime' => 10),
	'5' 	=> array('title' => '45 Minutes Live Psychic Chat Reading',
                         'amount' => '61.99',
                         'minutes' => '55', 'freeTime' => 10),
	'6' 	=> array('title' => '60 Minutes Live Psychic Chat Reading',
                         'amount' => '79.99',
                         'minutes' => '70', 'freeTime' => 10),
	'9' 	=> array('title' => '90 Minutes Live Psychic Chat Reading',
                         'amount' => '99.99',
                         'minutes' => '115', 'freeTime' => 15, 'repeaterOnly' => 1)
    );
//, 'repeaterOnly' => 1    
    /**
     * Array with email reading tariffs
     *
     * @var array
     */
    public static $email_tariff_array = array(
        '1' => array('title' => '1 question',
                     'price' => '29.95'),
        '2' => array('title' => '2 questions',
                     'price' => '39.95'),
        '3' => array('title' => '3 questions',
                     'price' => '49.95'),
        '4' => array('title' => '4 questions',
                     'price' => '59.95'),
        '5' => array('title' => '5 questions',
                     'price' => '69.95'),
    );
    /**
     * Array with membership package tariffs
     *
     * @var array
     */
    public static $package_tariff_array = array(
        '1' => array('title' => 'Bronze',
                     'price' => '99.95',
                     'minutes' => 70,
                     'readings' => 1,
                     'astrology' => 1),
        '2' => array('title' => 'Silver',
                     'price' => '199.95',
                     'minutes' => 140,
                     'readings' => 2,
                     'astrology' => 2),
        '3' => array('title' => 'Vip gold',
                     'price' => '339.95',
                     'minutes' => 280,
                     'readings' => 5,
                     'astrology' => 4)
    );
    /**
     * Load list of chat options
     *
     * @return array
     */
    public static function loadChatOptions(){
        return self::$chat_tariff_array;
    }
    /**
     * Get tariff duraion in dependence of tariff price
     *
     * @param <float> $amount
     * @return <int>
     */
    public static function getDuration($amount){

		/// only for steveL
	
		if(($amount == '1.99') && (Yii::app()->user->id == 19310))
				return 20;

        foreach(self::$chat_tariff_array as $tar){
            if($tar['amount'] == $amount)
                return $tar['minutes'];
        }
    }
    /**
     * Returns options for current chat type
     *
     * @param int $type Chat type
     * @return array
     */
    public static function loadCurrentChatOptions($type){
        return self::$chat_tariff_array[$type];
    }
    /**
     * Load special price for reader with different types
     *
     * @param integer $readerId
     * @param mixed $readingType (SPECIAL, 1 question, 2, 3, etc...)
     * @return string
     */
    public static function loadEmailReadingPrice($readerId, $readingType){
        if($readingType == 'SPECIAL') {
            $connection=Yii::app()->db;
            $sql = "SELECT special
                    FROM email_readers
                    WHERE reader_id = ".$readerId;
            $command=$connection->createCommand($sql);
            $rows=$command->query();
            foreach($rows as $row)
                $price = $row['special'];
        } else {
            $price = self::$email_tariff_array[$readingType]['price'];
        }
        return $price;
    }
    
    public static function getReadingTypeByPrice($price)
    {
        foreach(self::$email_tariff_array as $id => $type)
        {
            if($type['price'] == $price)
                $r_type = $id;
        }
        if(!$r_type)
            $r_type = 'SPECIAL';
        
        return $r_type;
    }
    /**
     * Load readers list with special prices
     *
     * @return array
     */
    public static function loadEmailReadingPriceList(){
        $connection=Yii::app()->db;
        $sql = "SELECT T1_1.rr_record_id, T1_1.name, email_readers.special
                FROM email_readers
                LEFT JOIN T1_1 ON email_readers.reader_id = T1_1.rr_record_id
                WHERE email_readers.status = 'active'";
        $command=$connection->createCommand($sql);
        $rows=$command->query();
        return $rows;
    }
    /**
     * Load questions tariffs list (1 question, 2, 3, etc...)
     *
     * @return array
     */
    public static function loadEmailReadingTariffsList(){
        return self::$email_tariff_array;
    }
    
    public static function loadPackagesList()
    {
        return self::$package_tariff_array;
    }
    
    public static function loadCurrentPackage($package_id)
    {
        return self::$package_tariff_array[$package_id];
    }
}

?>

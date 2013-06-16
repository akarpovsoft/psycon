<?php

class RaspConfig 
{
    private $merchantPin = array(
        'def' => '86740249'
    );
    
    private static $currency = array(
        'psyca' => 'CAD',
        'def' => 'USD'
    );
    
    private static $buisnessEmail = array(
        'psyca' => 'batman@yandex.ru',
        'def' => 'orders@psychic-contact.com'
    );
    
    public static function getMerchantPin($account_id)
    {
        if($account_id)
            return $this->merchantPin[$account_id];
        else
            return $this->merchantPin['def'];
    }
    
    public static function getCurrency($account_id)
    {
        if($account_id)
            return self::$currency[$account_id];
        else
            return self::$currency['def'];
    }
    
    public static function getBuisnessEmail($account_id)
    {
        if($account_id)
            return self::$buisnessEmail[$account_id];
        else
            return self::$buisnessEmail['def'];
    }
}
?>

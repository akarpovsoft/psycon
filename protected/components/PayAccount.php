<?php
/**
 *  Payment account manager
 */
class PayAccount {

	const ECHO_PAY = 1;
	const AUTHNET_PAY = 2;
	const FD_PAY = 3;
	const IS_PAY = 4;

	public static $defaultId = 1;
	public $login;
	public $pin;

	protected function __construct($login, $pin) {
		$this->login = $login;
		$this->pin = $pin;
	}
        
        public static function getAccountParams($ps, $id)
        {
            return Yii::app()->params['account_params'][$ps][$id];
        }
        
	/**
  	* 
  	*/
	public static function getParams($type, $id = null) {
		if(!isset($id))
			$id = self::$defaultId;
                $params = self::getAccountParams($type, $id);

                return new self($params['login'], $params['pin']);
                
//		if($type == self::ECHO_PAY) {
//			return new self('212>3340195', '86740249');
//		}
//
//		if($type == self::AUTHNET_PAY) {
//			return new self("93WCWcd7e", "6M5edbs2BJ7L6L24");
//		}
//                
//                if($type == self::FD_PAY) {
//                        return new self("WS1909594750._.1", "mvP2PpRA");
//                }
//                
//                if($type == self::IS_PAY)  {
//                        return new self("4371", "");
//                }
	}
	
	/**
  	*  Payment system factory
  	*/
	public static  function loadPaymentSystem($type, $id = null, $currency='USD') {
		$pa = self::getParams($type, $id);
                
                //echo $pa->login.' '.$pa->pin;
                //die();
		if($type == self::ECHO_PAY) {
			$PSystem = new EchoPaySystem($currency);
		}

		if($type == self::AUTHNET_PAY) {
			$PSystem = new AuthNetPaySystem($currency);
		}

		if($type == self::IS_PAY) {
			$PSystem = new ISPaySystem($currency);
		}

		if($type == self::FD_PAY) {
			$PSystem = new FirstDataPaySystem($currency);
		}

		if(!is_object($PSystem))
			throw new Exception('Invalid payment system type');
                        
		$PSystem->init(array(
		"transaction_login" => $pa->login,
		"transaction_key" => $pa->pin,
                   //"test_mode" => 1
        ));
		
        return $PSystem;
	}
        
        public static function getDefaultPS($id = null)
        {
            $type = Yii::app()->params['default_ps'];
            
            $ps = self::loadPaymentSystem($type, $id);
            
            return $ps;
        }
}
?>                                                   
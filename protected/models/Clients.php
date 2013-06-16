<?php
/**
 * class Clients
 * Special subclass for working with users with status 'Client'
 *
 * @author Den Kazka den.smart[at]gmail.com
 * @since 2010
 * @version $Id
 */
class Clients extends users
{
    public $client_id;

    protected $creditCard;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    /**
     * Register new client
     */
    public function clientRegister(){
        $this->rr_createdate = date('Y-m-d');
        $this->rr_lastaccess = date('Y-m-d H:i:s');
        $this->type = 'client';
        $this->rr_account_id = 1;
        $this->save();
        return mysql_insert_id();
    }

    /**
     * Gets client by Id
     * Returns client with relation table, if with param is not null
     *
     * @param int $client_id
     * @param string $with name of relation table
     * @return object users
     */
    public static function getClient($client_id, $with = null){
        if(!is_null($with))
            return self::model()->with($with)->findByPk($client_id);
        else
            return self::model()->findByPk($client_id);
    }
    /**
     * Return favorite reader id for current client
     *
     * @param integer $client_id
     * @return integer
     */
    public static function getFavoriteReader($client_id){
        $pref = Preference::getFavouriteReader($client_id);
        return $pref;
        ///return $pref->value;
    }

    public static function getByLoginOrEmail($LoginOrEmail){
        return self::model()->find(array(
            'condition' => 'login = :log_data OR emailaddress = :eml_data',
            'params' => array(':log_data' => $LoginOrEmail, ':eml_data' => $LoginOrEmail)));
    }

    
	public function getScreenName()
	{
		return $this->getFirstName();
	}

	/*
     * Get cc data
     *
     */
    private function _creditCard()
    {
    	if(!isset($this->creditCard)) {
        	$this->creditCard = CreditCard::getCardInfo($this->getId());
    	}
        
        return $this->creditCard;
    }
	
    /*
     * Get First name from Credit Card Table (T1_4 now)
     *
     */
    public function getFirstName()
    {
    	$cc = $this->_creditCard();
        return $cc['firstname'];
    }

    public function getBillingCountry()
    {
    	$cc = $this->_creditCard();
        return $cc['billingcountry'];
    }

    public function getBalance(){
		return $this->balance;
    }

    public function setBalance($balance){
        	$this->balance = round($balance);
		$this->save();        
    }

    public function addToBalance($time){
        $this->balance += $time;
        $this->save();
    }
    
    
}
?>

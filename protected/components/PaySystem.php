<?php
/**
 * Payment system absrtact class
 */

class PaySystem {

	var $success;
	var $init_data;
	var $raw_response;

	
	/**
	 * Init payment system class 
	 *
	 * @param array $init_data
	 */
	
	function init($init_data)
	{
	}
	
	/**
	 * Payment processing
	 *
	 * @param array $pay_data
	 */
	function pay($pay_data)
	{
	}

	/**
	 * getType
	 *
	 */
	function getType()
	{
		return "Unknown";
	}

	
	function get_random() {
		mt_srand ((double) microtime() * 1000000);
		return mt_rand();
	}
        
        public function supportsAuthOnly()
        {            
        }
	
}
?>
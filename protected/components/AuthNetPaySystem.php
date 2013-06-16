<?php
/**
 * Authorize.net payment system class
 */

class AuthNetPaySystem extends PaySystem {
	
//	var $server="https://test.authorize.net/gateway/transact.dll"; // TEST MODE
//	var $server="https://developer.authorize.net/param_dump.asp"; // TEST MODE
	var $server="https://secure.authorize.net/gateway/transact.dll"; // LIVE MODE
	
	var $address_success;
        public $currency;
        
        
        public function __construct($currency) 
        {
            $this->currency = $currency;
        }
	/**
	 * Init payment system class 
	 *
	 * @param array $init_data
	 */
	
	function init($init_data)
	{
		
		$this->init_data	= array
		 (
		 	"x_login"				=> $init_data["transaction_login"],
			"x_version"				=> "3.1",
			"x_delim_char"			=> "|",
			"x_delim_data"			=> "TRUE",
			"x_url"					=> "FALSE",
			"x_type"				=> $init_data["transaction_type"],
			"x_method"				=> $init_data["transaction_method"],
		 	"x_relay_response"		=> "FALSE",
		 	"x_tran_key"			=> $init_data["transaction_key"], // merchant transaction key
		 	"x_test_request"			=> ($init_data["test_mode"])?"TRUE":"FALSE" // merchant transaction key
		 	
		 );
	}

	/**
	 * Authorize
	 *
	 * @param array $pay_data
	 */
	function authorize($pay_data)
	{
		$this->init_data["x_type"] = "AUTH_ONLY";
		$this->init_data["x_method"] = "CC";
		return $this->pay($pay_data);
	}
	
	/**
	 * Payment processing
	 *
	 * @param array $pay_data
	 */
	function pay($pay_data)
	{
	/*
			$ch = curl_init("https://secure.authorize.net/gateway/transact.dll"); 
		curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($ch, CURLOPT_POSTFIELDS, "x_card_num=12345678899&x_exp_date=0412&x_description=&x_first_name=Alexis&x_last_name=Karpovitz&x_address=56&x_city=defuniak+springs&x_state=Florida&x_amount=11.95&x_zip=32433&x_email=karpovsoft%40gmail.com&x_cust_id=22168&x_card_code=274&x_trans_id=&x_login=93WCWcd7e&x_version=3.1&x_delim_char=%7C&x_delim_data=TRUE&x_url=FALSE&x_type=AUTH_ONLY&x_method=CC&x_relay_response=FALSE&x_tran_key=6M5edbs2BJ7L6L24&x_test_request=FALSE"); // use HTTP POST to send form data
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway
		$resp = curl_exec($ch); //execute post and get results
		curl_close ($ch);
		if(!$resp) echo "Error"; else echo $resp;
die();		
		*/

		$tr_data= array
		(
		"x_card_num"			=> $pay_data["cc_number"],
		"x_exp_date"			=> str_pad($pay_data["ccexp_month"], 2, "0", STR_PAD_LEFT).substr($pay_data["ccexp_year"], -2),
		"x_description"			=> $pay_data["description"],
		"x_first_name"			=> $pay_data["first_name"],
		"x_last_name"			=> $pay_data["last_name"],
		"x_address"				=> $pay_data["billing_address"],
		"x_city"				=> $pay_data["billing_city"],
		"x_state"				=> $pay_data["billing_state"],
		"x_amount"				=> $pay_data["grand_total"],
		"x_zip"					=> $pay_data["billing_zip"],
		"x_email"               => $pay_data["billing_email"],
		"x_cust_id"             => $pay_data["client_id"],
		"x_card_code"           => $pay_data["cnp_security"],
		"x_trans_id"            => $pay_data["transaction_id"]
		);
		
		$tr_data=array_merge($tr_data, $this->init_data);
		
		$fields = "";
		foreach( $tr_data as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";
		$ch = curl_init($this->server); 
		curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway
		$resp = curl_exec($ch); //execute post and get results
		curl_close ($ch);
		if(!$resp) {
			throw new Exception('Problem to access pay system');
		}
			
		$text = $resp;
		
		$this->raw_response=$resp;
		$tok = strtok($text,"|");
		$tok = explode("|", $text);
		
		$this->success=($tok[0]=="1");
		$this->address_success=true;
		
		$resp_info=array();

		$resp_info["TRANS_ID"]=$tok[6];
		
		if(strlen($resp_info["TRANS_ID"])==0)
		{
			$resp_info["TRANS_ID"]=time()."";
		}
		$resp_info["ORDER_NUMBER"]=$resp_info["TRANS_ID"];
		
		$resp_info["AUTH_CODE"]="";
		
		$resp_info["TRAN_AMOUNT"]=$tok[9];
		$resp_info["ERROR_MESSAGE"]=$tok[3];
		
		$resp_info["DECLINE_CODE"]=$tok[2];
		
		$resp_info["TRAN_DATE"]=date("m/d/Y");
		if(!$this->success) {
			$this->address_success = (strpos($resp_info["ERROR_MESSAGE"], "AVS mismatch")===false);
		}
	
		return $resp_info;
		
	}
	
	/**
	 * getType
	 *
	 */
	function getType()
	{
		return "AUTH";
	}
        
        public function supportsAuthOnly()
        {
            return true;
        }
	
}
?>
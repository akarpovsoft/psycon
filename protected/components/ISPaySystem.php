<?php

class ISPaySystem extends PaySystem
{
        var $server="https://secure.internetsecure.com/process.cgi"; // LIVE MODE

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
		
		$this->init_data = array
		 (
		 	"merchant_number" => $init_data["transaction_login"],
			"test_mode" => $init_data["test_mode"],
		 );
	}
	
        public function authorizeAndCapture($pay_data)
        {
            $this->set_transaction_type();
            $resp_info = $this->pay($pay_data);
            return $resp_info;
        }
        
        public function set_transaction_type()
        {
            $this->init_data['transaction_type'] = "00";
        }
        
	/**
	 * Payment processing
	 *
	 * @param array $pay_data
	 */
	function pay($pay_data)
	{
//		$this->init_data["test_mode"] = 1;

		if($this->currency=="TEST")
			$this->init_data["test_mode"] = 1;

		$flag=($this->init_data["test_mode"]?"{TEST}":"{".$this->currency."}");
		
//		$pay_data["grand_total"]=2;
		$this->init_data["transaction_type"] ="00";
		$xxxRequestData ="<TranxRequest><MerchantNumber>".$this->init_data["merchant_number"]."</MerchantNumber><Products>".$pay_data["grand_total"]."::1::".$pay_data['product_code']."::".$pay_data['product_name']."::".$flag."</Products><xxxName>".$pay_data["first_name"]." ".$pay_data["last_name"]."</xxxName><xxxCompany>Company</xxxCompany><xxxAddress>".$pay_data["billing_address"]."</xxxAddress><xxxCity>".$pay_data["billing_city"]."</xxxCity><xxxProvince>".$pay_data["billing_state"]."</xxxProvince><xxxPostal>".$pay_data["billing_zip"]."</xxxPostal><xxxCountry>".$pay_data["billing_country"]."</xxxCountry><xxxPhone>".$pay_data["phone"]."</xxxPhone><xxxEmail>".$pay_data["email"]."</xxxEmail><xxxCard_Number>".$pay_data["cc_number"]."</xxxCard_Number><xxxCCMonth>".$pay_data["ccexp_month"]."</xxxCCMonth><xxxCCYear>".$pay_data["ccexp_year"]."</xxxCCYear><CVV2>".$pay_data["cnp_security"]."</CVV2><CVV2Indicator>1</CVV2Indicator><xxxTransType>".$this->init_data["transaction_type"]."</xxxTransType></TranxRequest>";
//		die($this->init_data["merchant_number"]);
		$stock_xml_to_send = "xxxRequestMode=X&xxxRequestData=".urlencode($xxxRequestData);
//		die($this->server."?".$stock_xml_to_send);
		$ch = curl_init($this->server);
		curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($ch, CURLOPT_POSTFIELDS, $stock_xml_to_send); // use HTTP POST to send form data
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway
		
		$this->raw_response = curl_exec($ch); // run the whole process
		curl_close($ch);
		
		$xml_parser = xml_parser_create();
		xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, TRUE);
		xml_parse_into_struct($xml_parser, $this->raw_response, &$vals, &$indexz);
		xml_parser_free($xml_parser);
/*
		print_r($this->raw_response);
		echo "=========VALS=================<br/>\r\n";
		print_r($vals);
		echo "==============================<br/>\r\n";
*/		
//		die;
		$resp_info=array();
		while(list($key, $val) = each($vals)){
			$val[tag] = strtoupper($val[tag]);
			$loc_val = $val[tag];
			$resp_info[$loc_val] = $val[value];
		}
		
		$resp_info["TRAN_DATE"]=date("m/d/Y");
		$resp_info["ERROR_MESSAGE"]=$resp_info["VERBIAGE"];
//		if(is_null($resp_info["ERROR_MESSAGE"])) $resp_info["ERROR_MESSAGE"] = "Some error occured";
		$resp_info["ORDER_NUMBER"]=$resp_info["RECEIPTNUMBER"];
		
		$resp_info["AUTH_CODE"]=$resp_info["APPROVALCODE"];
		
		$resp_info["TRAN_AMOUNT"]=$resp_info["TOTALAMOUNT"];
		$resp_info["DECLINE_CODE"]=$resp_info["PAGE"];
/*		
		echo "=========RESP=================<br/>\r\n";
		print_r($resp_info);
		echo "==============================<br/>\r\n";
*/		
		$this->address_success=true;
		$this->success=(($resp_info["PAGE"]=="90000" or ($resp_info["PAGE"]=="2000" && $this->init_data['test_mode'])) and ('M' == $resp_info["CVV2RESPONSECODE"] or 'U' == $resp_info["CVV2RESPONSECODE"]));
//		die("success=".strval($this->success));		
		$this->address_success=($resp_info["AVSRESPONSECODE"]=='X' || $resp_info["AVSRESPONSECODE"]=='Y');
		return $resp_info;
	}
	
	function getType()
	{
		return "IS";
	}
        
        public function supportsAuthOnly()
        {
            return false;
        }
}
?>

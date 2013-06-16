<?php
/**
 * Payment system absrtact class
 */

class EchoPaySystem extends PaySystem {

    var $echoPHP;
    var $server="https://wwws.echo-inc.com/scripts/INR200.EXE";
    var $success;
    var $address_success;
    var $cnp_success;

    const DEBUG = false;
    
    public $currency;
    
    public function __construct($currency) 
    {
        $this->currency = $currency;
    }
    
	/**
	 * Authorize
	 *
	 * @param array $pay_data
	 */
	function authorize($pay_data)
	{
		$this->echoPHP->set_order_type('S');
        if(($pay_data['client_status'] != 'preferred') || ($pay_data['billing_country'] == 'United States')) {
            $this->set_transaction_type('AD');
            $resp_info=$this->pay($pay_data);
            $address_success=($resp_info["AVS_RESULT"]!='N');
            $cnp_success=($resp_info["SECURITY_RESULT"]=='M' ||
                            $resp_info["SECURITY_RESULT"]=='U' ||
                            $resp_info["SECURITY_RESULT"]=='P' ||
                            $_POST['CVV_check']=='on'); // Pass requests for non cvv cards
            if(!$address_success) {
                $this->success == false;
                $resp_info["ERROR_MESSAGE"]="Address mismatches";
                return $resp_info;
            }
            if(!$cnp_success) {
                $this->success == false;
                $resp_info["ERROR_MESSAGE"]="CVV mismatches";
                return $resp_info;
            }

        }
        $this->set_transaction_type('AV');
        $resp_info=$this->pay($pay_data);
        return $resp_info;
	}
    
	public function set_transaction_type($type) {
		$this->echoPHP->set_transaction_type($type);
	}
	
    /**
     * Init payment system class
     *
     * @param array $init_data
     */
    function init($init_data) {
        $this->echoPHP = new EchoPHP();
        $this->echoPHP->set_EchoServer($this->server);
        $this->echoPHP->set_merchant_echo_id($init_data["transaction_login"]); // use your own id here
        $this->echoPHP->set_merchant_pin($init_data["transaction_key"]);        // use your onw pin here
        $this->echoPHP->set_billing_ip_address($_SERVER['REMOTE_ADDR']);
        $this->echoPHP->set_merchant_trace_nbr($init_data["merchant_trace_num"]);
        $this->echoPHP->set_debug(self::DEBUG);
    }

    /**
     * Pay
     *
     * @param array $pay_data
     */
    function pay($pay_data) {
        $this->echoPHP->set_billing_first_name($pay_data["first_name"]);
        $this->echoPHP->set_billing_last_name($pay_data["last_name"]);
        $this->echoPHP->set_billing_address1($pay_data["billing_address"]);
        $this->echoPHP->set_billing_city($pay_data["billing_city"]);
        $this->echoPHP->set_billing_state($pay_data["billing_state"]);
        $this->echoPHP->set_billing_zip($pay_data["billing_zip"]);
        $this->echoPHP->set_billing_country($pay_data["billing_country"]);
        $this->echoPHP->set_billing_email($pay_data["billing_email"]);

        $this->echoPHP->set_cc_number($pay_data["cc_number"]);
        $this->echoPHP->set_grand_total($pay_data["grand_total"]);
        $this->echoPHP->set_ccexp_month($pay_data["ccexp_month"]);
        $this->echoPHP->set_ccexp_year($pay_data["ccexp_year"]);

        if(isset($pay_data["cnp_security"]))
            $this->echoPHP->set_cnp_security($pay_data["cnp_security"]);

        if (isset($pay_data['auth_code']))
            $this->echoPHP->set_authorization($pay_data['auth_code']);

        $rnd_count = $this->echoPHP->getRandomCounter();
        $this->echoPHP->set_counter($rnd_count);
        $merchant_trace_nbr = '19310'.$rnd_count;
        $this->echoPHP->set_merchant_trace_nbr($merchant_trace_nbr);

        $this->echoPHP->Submit();

        // Parses response
        $echo_2split = "<xml>".$this->echoPHP->echotype3."</xml>";
        $xml_parser = xml_parser_create();
        xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, TRUE);
        xml_parse_into_struct($xml_parser, $echo_2split, &$vals, &$indexz);
        xml_parser_free($xml_parser);

        foreach($vals as $key => $val) {
            $val['tag'] = strtoupper($val['tag']);
            $loc_val = $val['tag'];
            if(isset($val['value']))
                $resp_info[$loc_val] = $val['value'];
        }

        if(!$this->echoPHP->EchoSuccess) {

            $resp_info["ERROR_MESSAGE"] = strip_tags($this->echoPHP->echotype2);
        }

        $this->success=$this->echoPHP->EchoSuccess;

        $this->address_success=true;
        $this->cnp_success=true;
        return $resp_info;
    }
    
	/**
	 * getType
	 *
	 */
	function getType()
	{
		return "ECHO";
	}
        
        public function supportsAuthOnly()
        {
            return true;
        }
}
?>
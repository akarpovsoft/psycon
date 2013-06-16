<?php

class AuthNet extends PaySystem
{
    public $server = "https://secure.authorize.net/gateway/transact.dll";
    public $address_success;
    
    public function init($init_data)
    {
        $this->init_data = array
         (
            "x_login" => $init_data["transaction_login"],
            "x_version" => "3.1",
            "x_delim_char" => "|",
            "x_delim_data" => "TRUE",
            "x_url" => "FALSE",
            "x_type" => $init_data["transaction_type"],
            "x_method" => $init_data["transaction_method"],
            "x_relay_response" => "FALSE",
            "x_tran_key" => $init_data["transaction_key"], // merchant transaction key
            "x_test_request" => ($init_data["test_mode"])?"TRUE":"FALSE" // merchant transaction key
         );
    }
    
    public function pay($pay_data) 
    {
        $tr_data= array
        (
            "x_card_num" => $pay_data["cc_number"],
            "x_exp_date" => str_pad($pay_data["ccexp_month"], 2,"0", STR_PAD_LEFT).substr($pay_data["ccexp_year"], -2),
            "x_description" => $pay_data["description"],
            "x_first_name" => $pay_data["first_name"],
            "x_last_name" => $pay_data["last_name"],
            "x_address" => $pay_data["billing_address"],
            "x_city" => $pay_data["billing_city"],
            "x_state" => $pay_data["billing_state"],
            "x_amount" => $pay_data["grand_total"],
            "x_zip" => $pay_data["billing_zip"],
            "x_email" => $pay_data["billing_email"],
            "x_cust_id" => $pay_data["client_id"],
            "x_card_code" => $pay_data["cnp_security"],
            "x_trans_id" => $pay_data["transaction_id"]
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

        $text = $resp;

        $this->raw_response=$resp;

        $tok = strtok($text,"|");
        $tok = explode("|", $text);

        $this->success=($tok[0]=="1");
        $resp_info=array();

        $resp_info["TRANS_ID"]=$tok[6];

        if(strlen($resp_info["TRANS_ID"])==0)
        {
                $resp_info["TRANS_ID"]=time()."";
        }
        $resp_info["ORDER_NUMBER"]=$resp_info["TRANS_ID"];

        $resp_info["AUTH_CODE"]=$tok[4];

        $resp_info["TRAN_AMOUNT"]=$tok[9];
        $resp_info["ERROR_MESSAGE"]=$tok[3];
        $resp_info["AVS"] = $tok[5];
        $resp_info["DECLINE_CODE"]=$tok[2];

        $resp_info["TRAN_DATE"]=date("m/d/Y");
        
        $this->address_success=true;
	return $resp_info;
    }
    
    
}
?>

<?php   
    
class FirstDataPaySystem extends PaySystem
{
    public $testcards = array(
        'visa' => '4111111111111111',
        'mastercard' => '5419840000000003',
        'discover' => '6011000993010978',
        'ae' => '372700997251009'
    );
    
    public $server="https://ws.merchanttest.firstdataglobalgateway.com/fdggwsapi/services/order.wsdl";
    public $currency;
    public $success;
    public $address_success;
    
    public function __construct($currency) 
    {
        $this->currency = $currency;
    }
    
    public function init($init_data)
    {
        $this->init_data = array
         (
            'userid' => $init_data["transaction_login"],
            'password' => $init_data["transaction_key"],
            'pemlocation' => "/usr/home/psychi/domains/psychic-contact.com/public_html/advanced/data/FD/WS1909551896._.1.pem",
            'kslocation' => "/usr/home/psychi/domains/psychic-contact.com/public_html/advanced/data/FD/WS1909551896._.1.key",
            'keyname' => "ckp_1324486057",
            'transaction_type' => 'PostAuth',
            'debug_mode' => true
         );
    }
    
    public function authorize($pay_data)
    {
        $resp_info = $this->pay($pay_data);
        
        return $resp_info;
    }
    
    public function pay($pay_data)
    {
         $body = $this->makeRequest($pay_data);
         $resp = $this->sendingData($body);
         return $this->processResponse($resp);    
    }
    
    public function makeRequest($pay_data)
    {
        $body = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $body .= "<SOAP-ENV:Envelope xmlns:SOAP-    ENV=\"http://schemas.xmlsoap.org/soap/envelope/\">";
        $body .= "<SOAP-ENV:Header />";
        $body .= "<SOAP-ENV:Body>";
        $body .= "<fdggwsapi:FDGGWSApiOrderRequest xmlns:fdggwsapi= \"http://secure.linkpt.net/fdggwsapi/schemas_us/fdggwsapi\">";
        $body .= "<v1:Transaction xmlns:v1= \"http://secure.linkpt.net/fdggwsapi/schemas_us/v1\">";
        $body .= "<v1:CreditCardTxType>";
        $body .= "<v1:Type>".$this->init_data['transaction_type']."</v1:Type>";
        $body .= "</v1:CreditCardTxType>";
        $body .= "<v1:CreditCardData>";
        $body .= "<v1:CardNumber>".$pay_data['cc_number']."</v1:CardNumber>";
        $body .= "<v1:ExpMonth>".$pay_data['ccexp_month']."</v1:ExpMonth>";
        $body .= "<v1:ExpYear>".$pay_data['ccexp_year']."</v1:ExpYear>";
        $body .= "</v1:CreditCardData>";
        $body .= "<v1:Payment>";
        $body .= "<v1:ChargeTotal>".$pay_data['grand_total']."</v1:ChargeTotal>";
        $body .= "</v1:Payment>";
        $body .= "<v1:TransactionDetails>";
        $body .= "<v1:UserID>".$pay_data['client_id']."</v1:UserID>";
        $body .= "</v1:TransactionDetails>";
        $body .= "<v1:Billing>";
        $body .= "<v1:Name>".$pay_data['first_name']."</v1:Name>";
        $body .= "<v1:Address1>".$pay_data['billing_address']."</v1:Address1>";
        $body .= "<v1:City>".$pay_data['billing_city']."</v1:City>";
        $body .= "<v1:State>".$pay_data['billing_state']."</v1:State>";
        $body .= "<v1:Zip Code>".$pay_data['billing_zip']."</v1:Zip Code>";
        $body .= "<v1:Country>".$pay_data['billing_country']."</v1:Country>";
        $body .= "<v1:Email>".$pay_data['billing_email']."</v1:Email>";
        $body .= "</v1:Billing>";
        $body .= "</v1:Transaction>";
        $body .= "</fdggwsapi:FDGGWSApiOrderRequest>";
        $body .= "</SOAP-ENV:Body>";
        $body .= "</SOAP-ENV:Envelope>";
        
        return $body;
    }
    
    public function sendingData($body)
    {        
        $ch = curl_init($this->server);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->init_data['userid'].':'.$this->init_data['password']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSLCERT, $this->init_data['pemlocation']);
        curl_setopt($ch, CURLOPT_SSLKEY, $this->init_data['kslocation']);
        curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $this->init_data['keyname']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $err = curl_error($ch);
        var_dump($err);
        echo '<br><br>';
        curl_close($ch);
        
        return $result;
    }
    
    public function processResponse($response)
    {
        $resp_info = $this->parseResponse($response);
        
        if($resp_info["TRAN_RESULT"] == "APPROVED")
            $this->success = true;
        else
            $this->success = false;
        
        return $resp_info;
    }
    
    function getType()
    {
            return "FIRSTDATA";
    }

    public function supportsAuthOnly()
    {
        return true;
    }
    
    private function parseResponse($response)
    {
        $fd_info = array();
        
        preg_match_all('/<fdggwsapi:(.*)\>(.*)\</', $response, $matches);
        
        for($i=0; $i<count($matches[1]); $i++)
        {
            $fd_info[$matches[1][$i]] = trim($matches[2][$i]);
        }
        $resp_info["ERROR_MESSAGE"] = $fd_info['ErrorMessage'];
        $resp_info["TRANS_ID"] = $fd_info['TransactionID'];
        $resp_info["ORDER_NUMBER"] = $fd_info['OrderId'];
        $resp_info["DECLINE_CODE"] = substr($fd_info['ErrorMessage'], 4, 6);
        $resp_info["TRAN_DATE"] = $fd_info['TDate'];
        $resp_info["TRAN_RESULT"] = $fd_info['TransactionResult'];
        return $resp_info;
    }
    
}
?>

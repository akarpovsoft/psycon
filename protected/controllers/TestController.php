<?php

class TestController extends CController
{
    public function actionFreetime(){
            $freeTime = RemoveFreetime::getFreeTime(9615);
            $freeTime->addHalfPaidTime(60);
    }

	public function actionTestRequest(){
        // Start data
        $reader_id = 19310;
        $client_id = 9615;
        $subject = 'Testing New chat tool (5 mins)';

        $request = new ChatRequests();
        $request->reader_id = $reader_id;
        $request->client_id = $client_id;
        $request->checkRequest(); // Delete record if exists
        $request->subject = $subject;
        $request->chat_type = 1; // todo: change chat type to real value (now only type 1 - debug)
        $request->duration = floor(5*60);

        $request_id = $request->addNewRequest();

        /// insert in chat_context table
                    /*
        $chatContext = new ChatContext();
        $chatContext->add($request_id, $_POST['client_id'], $_POST['reader'], $_POST['time']*60 );
                    */

        $cs=Yii::app()->clientScript;
        $baseUrl=Yii::app()->baseUrl;//$this->module->assetsUrl;
        //$cs->registerScriptFile('http://code.jquery.com/jquery-1.4.2.js');
                    ///$cs->registerScriptFile($baseUrl.'/js/jquery.ajax_queue.js');

                    $cs->registerScriptFile(Yii::app()->params['http_addr']."/js/jquery-1.4.4-tuned.js");
                    $cs->registerScriptFile(Yii::app()->params['http_addr']."/js/command.js");
                    $cs->registerScriptFile(Yii::app()->params['http_addr']."/js/util.js");

        $cs->registerScriptFile($baseUrl.'/js/jquery.json-2.2.js');

        $this->renderPartial('testRequest',
                array('reader_id'   => $reader_id,
                      'client_id'   => $client_id,
                      'subject'     => $subject,
                      'request_id'  => $request_id
        ));
    }

    public function actionTestMonitor(){

        $reader_id = 19310;

        $requesters = ChatRequests::getReadersRequest($reader_id);
        $client = ChatRequests::getReadersRequestNew($reader_id);

        $this->renderPartial('testMonitor',
                array('active' => $client->client_id,
                      'reader_id' => $reader_id)
                );
    }

    public function actionTestCheck(){
        $reader_id = 19310;

        $criteria = new CDbCriteria;
        $criteria->select = 'client_id, subject, reader_id, rr_record_id';
        $criteria->condition = 'reader_id = :reader_id
                                    AND (to_days(now())=to_days(t.laststatusupdate)
                                         and (time_to_sec(now()) - time_to_sec(t.laststatusupdate) < 10))';
	$criteria->params = array(':reader_id' => $reader_id);

        $cl_models = ChatRequests::model()->findAll($criteria);

        $a = array();

        if(!empty($cl_models)){
            foreach($cl_models as $mod){
                $a['update'] = 'update';
                $a['request_id'] = $mod->rr_record_id;
            }
        }
        else
            $a['update'] = 'continue';

        echo json_encode($a);


    }
    
    public function actionTestVisitors()
    {
       $report = VisitorsCounter::report('2011-10-17 04:50:00', '2011-10-17 22:00:00');
       $total = 0;
       $grand_total = 0;
       
       $subject = 'Visitor Count TEST';
       $body = 'Visitors report:<br/><br/>
            Home page<br/>';
       
       foreach($report['home'] as $aff => $count)
       {
           $body .= $aff.' = '.$count.'<br/>';
           $total += $count;
       }
       $grand_total += $total;
       $body .= 'Total: '.$total.' hosts<br/><br/>';
       
       $body .= 'Signup page<br/>';
       $total = 0;
       foreach($report['signup'] as $aff => $count)
       {
           $body .= $aff.' = '.$count.'<br/>';
           $total += $count;
       }
       $grand_total += $total;
       $body .= 'Total: '.$total.' hosts<br/><br/>';
       $body .= 'Grand total: '.$grand_total.' hosts';
       
       PsyMailer::send('den.smart@gmail.com', $subject, $body);
       die();
    }
    
    
    public function actionTestFD()
    {
        $fd = new FirstDataPaySystem('USD');
        
        
        $pay_data = array("first_name" => "Steve", 
            "last_name" =>  'Landes', 
            "billing_address" => 'efwwefew', 
            "billing_city" => 'wefewfewgewgew', 
            "billing_state" => 'wefwwefefewfew', 
            "billing_zip" => 'wefwfweuifiew', 
            "billing_country" => 'qwdqwjdnqwd', 
            "billing_email" => 'steve@el.com',
            "cc_number" => '4111111111111111', 
            "grand_total" => '19.95',
            // Payment amount
            "ccexp_month" => '11', "ccexp_year" => '2016', 
            "cnp_security" => '123', 
            "client_id" => '9615'
        );
        $req = $fd->makeRequest($pay_data);
        
        $ch = curl_init("https://ws.merchanttest.firstdataglobalgateway.com/fdggwsapi/services/order.wsdl");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_USERPWD, 'WS1909551896._.1:sDOSDvOH');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSLCERT, "/usr/home/psychi/domains/psychic-contact.com/public_html/advanced/data/FD/WS1909551896._.1.pem");
        curl_setopt($ch, CURLOPT_SSLKEY, "/usr/home/psychi/domains/psychic-contact.com/public_html/advanced/data/FD/WS1909551896._.1.key");
        curl_setopt($ch, CURLOPT_SSLKEYPASSWD, "ckp_1324486057");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        var_dump($result);
    }
    
    public function actionTestAccParams()
    {    
       $pa = PayAccount::getParams('2', '2');
       
       echo $pa->login.' '.$pa->pin;
       die();
    }
}

?>

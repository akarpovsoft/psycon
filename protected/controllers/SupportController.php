<?php


class SupportController extends PsyController
{
    public function filters($params = null)
     {
        $a = array(
                'accessControl', // perform access control for CRUD operations
            );
        return parent::filters($a);
     }

    public function accessRules()
     {
        return array(
                array('allow', // allow authenticated user
                        'actions'=>array('FeedBack', 'NrrRequest'),
                        'users'=>array('@'),
                ),
                array('deny',  // deny all users
                        'users'=>array('*'),
                ),
        );
    }

    public function actionFeedBack(){
        $this->layout = $this->without_left_menu;
        Yii::app()->getModule('chat');
        $session_key = Yii::app()->request->getParam('session_key');
        // feedback from user to reader/admin
        if(Yii::app()->user->type == 'client'){
            $context = ChatContext::getContext($session_key, 1);
            $type = 'client';
        } else { //feedback from reader to client/admin
            $context = ChatContext::getContext($session_key, 2);
            $type = 'reader';
        }

        $reader_data = Readers::getReader($context->reader_id);
        $client_data = Clients::getClient($context->client_id);
        $userName = (Yii::app()->user->type == 'client') ? $reader_data->login : $client_data->login;
        $fromName = (Yii::app()->user->type == 'client') ? $client_data->login : $reader_data->login;

        if(isset($_POST['send'])){
            $message = new Messages();
            $message->From_user = Yii::app()->user->id;
            $message->From_name = $_POST['from_name'];
            $message->To_user = '1';
            $message->Subject = $_POST['subject'];
            $message->Body = $_POST['text'];
            $message->attachment = 'no';
            $message->user_type = Yii::app()->user->type;
            if(!$message->validate()){ // Some error occured
                $err = $message->getErrors();
                $this->render('feed_back',array(
                                                'reader_id' => $context->reader_id,
                                                'client_id' => $context->client_id,
                                                'type' => $type,
                                                'user_name' => $userName,
                                                'from_name' => $fromName,
                                                'errors' => $err
                                       ));
                return;
            }
            
            // Feed back to admin
            $text2send = "Reader : ".$reader_data->login." Client : ".$client_data->login." Session : ".
                        $context->session_id."<br>".$_POST['text'];
            if($_POST['to'] == 'admin'){ // sendind email to adminEmail and message through message center
                
                
                PsyMailer::send(Yii::app()->params['adminEmail'], $_POST['subject'], $text2send);                
                $message->send();
                // Feed Back to reader/client and admin
            } else if($_POST['to'] == 'both'){ 
                PsyMailer::send(Yii::app()->params['adminEmail'], $_POST['subject'], $text2send);
                $message->send();                
                $receiver_id = (Yii::app()->user->type == 'client') ? $_POST['reader_id'] : $_POST['client_id'];
                $receiver = users::getUser($receiver_id);
                $message->To_user = $receiver_id;
                $message->send();
                $email_text = ($message->Status != 'pending') ? $_POST['text'] : '';
                $body = "Hello ".$receiver->name.",\n
                        You have received a letter in your message center at www.psychic-contact.com\n
                        From: ".$_POST['from_name']."\n---------\n".$email_text;
                PsyMailer::send($receiver->emailaddress, "PSYCHAT - You have received a message", $body);
            } else {
                $receiver = users::getUser($_POST['to']);
                $message->To_user = $_POST['to'];
                $message->send();
                $email_text = ($message->Status != 'pending') ? $_POST['text'] : '';
                $body = "Hello ".$receiver->name.",\n
                        You have received a letter in your message center at www.psychic-contact.com\n
                        From: ".$_POST['from_name']."\n---------\n".$email_text;
                PsyMailer::send($receiver->emailaddress, "PSYCHAT - You have received a message", $body);
            }
            $this->render('feed_back', array('success' => 1));
            return;
        }

        $this->render('feed_back', array('reader_id' => $context->reader_id,
                                         'client_id' => $context->client_id,
                                         'type' => $type,
                                         'user_name' => $userName,
                                         'from_name' => $fromName
                                    ));
    }

    public function actionNrrRequest(){
        $this->layout = $this->without_left_menu;
        Yii::app()->getModule('chat');
        $session_key = Yii::app()->request->getParam('session_key');
        $client = Clients::getClient(Yii::app()->user->id, 'credit_cards');
        $session = ChatContext::getContext($session_key, 1);
        $reader = Readers::getReader($session->reader_id);

        if($_POST['act'] == 'save'){
            $freetime = RemoveFreetime::getFreeTime(Yii::app()->user->id);
            $freetime->Count ++;
            $freetime->save();

            $subject="NRR Request Form(".$client->credit_cards->firstname." / ".$client->login."), Reader: ".$reader->login;

            $message="NRR Request Form<br>";
            $message.="CLIENT :     ".$client->credit_cards->firstname." (".$client->login.")<br>";
            $message.="READER :     ".$reader->login."<br>";
            $message.="SESSION DATE :     ".$_POST['session_date']."<br><br>";
            $message.="TIME BACK :     ".(!empty($_POST['time_back']) ? $_POST['time_back'] :
                    (!empty($_POST['time_back2']) ? $_POST['time_back2'] : $_POST['time_back3']))."<br><br>";

            switch ($_POST['nrr']) {
                case 1 :
                    $message .= "<br>Time put back ".$_POST['time_back']."<br>";
                    break;
                case 2 :
                    break;
                case 3 : {
                    switch ($_POST['nrr3_1']) {
                            case 1 :
                                $selected_option = "I WILL TRY TO RE-ENTER THE CHAT ASAP<br>";
                                break;
                            case 2 :
                                $selected_option = "I WILL SAVE MY TIME FOR ANOTHER SESSION AND/OR READER<br>";
                                break;
                            case 3 :
                                $selected_option = "I AM REQUESTING THAT THE READER ADD TIME BACK TO MY ACCOUNT IN THE AMOUNT OF ".$_POST['time_back2'];
                                break;
                    }
                    $message .= "Unfortunately due to various reasons (most likely internet related)-  your chat with ".$reader['login']." ended abruptly - We apologize for this occurrence.  Please clear your browser cache, <u>RE-BOOT</u> and try again- ALSO LOOK FOR EMAIL FROM YOUR READER!.<br>
We strongly recommend that you use Mozilla FireFox browser<br>
Best regards, Psychic-contact Admin<br>";
                    if ($selected_option)
                        $message .= " <br>Customer has chosen : ".$selected_option;
                    break;
		}
                case 4 : {
                    $message .= "Reason why client is unhappy :".$_POST['unhappy']."<br><br>";
                    $message .= "WE ARE SORRY THAT YOU ARE NOT SATISFIED WITH THE SESSION THAT JUST ENDED WITH: ".$reader->login."<br>
OUR POLICY IS THAT WE GUARANTEE SATISFACTION FOR ALL SESSIONS<br>
IF YOU HONESTLY FEEL THAT THE READER DID NOT LIVE UP TO YOUR EXPECTATIONS- WE WILL GLADLY ADD TIME BACK TO YOUR ACCOUNT<br>
BUT! IN THE FUTURE YOU MUST TELL THE READER WITHIN THE FIRST 5MINS AFTER HITTING THE 'HIRE ME' BUTTON AND THE READER WILL EITHER GIVE YOU YOUR TIME BACK OR PAUSE THE TIMER SO THAT YOU AND THE READER CAN TRY TO COMMUNICATE BETTER.<br>
NEXT TIME PLEASE DISCUSS YOUR FEELINGS ABOUT THE SESSION WITH THE READER";
                    break;
                }
                default:
            }

           // Email to admin, reader and client
           PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $message);
           PsyMailer::send($reader->emailaddress, $subject, $message);
           PsyMailer::send($client->emailaddress, $subject, $message);

            // Add new nrr request to db
            $nrr = new NrrRequests();
            $nrr->reader_id = $reader->rr_record_id;
            $nrr->client_id = Yii::app()->user->id;
            $nrr->nrr_notes = $message;
            $nrr->addNewRequest();

            if (strlen($_POST['suggest']) > 0){
                $subject = "NRR : Clients suggestions(".$client->credit_cards->firstname." / ".$client->login."), Reader: ".$reader->login;
                $email_text = $_POST['suggest'];
                PsyMailer::send(Yii::app()->params['adminEmail'], $subject, $email_text);
                PsyMailer::send($reader->emailaddress, $subject, $email_text);
            }

            $this->render('nrr_request', array('success' => 1));
            return;
        }

        $this->render('nrr_request',
                array('client' => $client,
                      'session' => $session,
                      'reader' => $reader
                    ));
    }
}

?>

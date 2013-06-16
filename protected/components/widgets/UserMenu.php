<?php

class UserMenu extends CWidget {

    private $type;
    private $user_id;
    private $mess_cnt;
    private $mess_cnt_new;
    private $pending_mess;
    private $count_upd;
    public $page_type;

    public function init() {
        $this->type = Yii::app()->user->type;
        $this->user_id = Yii::app()->user->id;
        $connection = Yii::app()->db;

        $sql = "SELECT COUNT(*) FROM messages
                    WHERE To_user = :To_user
                    AND Status = 'ok'
                GROUP BY Read_message";

        $command=$connection->createCommand($sql);
        $command->bindValue(":To_user", Yii::app()->session['login_operator_id'], PDO::PARAM_STR);

        $rowCount=$command->query();

        foreach($rowCount as $row) {
            if(empty($this->mess_cnt_new))
                $this->mess_cnt_new = $row['COUNT(*)'];
            $this->mess_cnt += $row['COUNT(*)'];
        }
        $this->mess_cnt = (!empty($this->mess_cnt)) ? $this->mess_cnt : 0;
        $this->mess_cnt_new = (!empty($this->mess_cnt_new)) ? $this->mess_cnt_new : 0;
        
        $model = new Messages();
        if($this->type == 'Administrator')
                $this->pending_mess = $model->count(array(
                                    'condition' => 'Status = :status',
                                    'params' => array(':status' => 'pending')
                                ));
        else
                $this->pending_mess = $model->count(array(
                                    'condition' => 'To_user = :To_user AND Status = :status',
                                    'params' => array(':To_user' => Yii::app()->user->id,
                                                      ':status' => 'pending')
                                ));


        $sql = "SELECT rr_record_id from Reader_Info_Temp";
        $command=$connection->createCommand($sql);
        $this->count_upd = $command->execute();
    }

    public function run() {
        switch($this->type) {
            case 'reader':
                $cat = array(
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/chat/emailreadings_scedual.php',
                                'title' => Yii::t('lang','Update'),
                                'add' => '<font color="#ff0000">'.Yii::t('lang','Email_readings_schedule').'</font>'
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['http_addr'].'messages',
                                'title' => Yii::t('lang','Message_Center'),
                                'add' => '<b>'.Yii::t('lang','Inbox').': '.$this->mess_cnt.' '.Yii::t('lang','New').': '.$this->mess_cnt_new.'</b>'
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/arrowsmall.gif',
                                'link' => Yii::app()->params['http_addr'].'users/readersClients',
                                'title' => Yii::t('lang','List_of_clients_youve_read_for')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/entersmall.gif',
                                'link' => Yii::app()->params['http_addr'].'users/readerBalance',
                                'title' => Yii::t('lang','Your_Balance')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['http_addr'].'messages/messageToConstClient?client_id=admin', 
                                'title' => Yii::t('lang','Contact_the_Admin')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['http_addr'].'messages/messageToConstClient?client_id=admin&sma=1',
                                'title' => Yii::t('lang','Submit_Magazine_Article')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['http_addr'].'users/priceRate', 
                                'title' => Yii::t('lang','Current_Price_Rate_Structure')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/arrowsmall.gif',
                                'link' => Yii::app()->params['http_addr'].'users/invoice',
                                'title' => Yii::t('lang','Invoice')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/arrowsmall.gif',
                                'link' => Yii::app()->params['http_addr'].'site/ourreaders?oo=1',
                                'title' => Yii::t('lang','Readers_online')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/arrowsmall.gif',
                                'link' => Yii::app()->params['http_addr'].'users/userBan', ///.PsyConstants::getName(PsyConstants::PAGE_USERBAN),
                                'title' => Yii::t('lang','User_Ban')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/arrowsmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/chat/wp_controlpanel.php', ///.PsyConstants::getName(PsyConstants::PAGE_WP_CONTROLPANEL),
                                'title' => Yii::t('lang','WebPhone_Control_Panel'),
                                'add' => '&nbsp;&nbsp;&nbsp;<a href="'.Yii::app()->params['site_domain'].'/chat/wp_deposits.php">'.Yii::t('lang','Webphone_Deposits').'</a>'
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['http_addr'].'emailreadings/pending', ///.PsyConstants::getName(PsyConstants::PAGE_EMAILREADINGS_READER),
                                'title' => Yii::t('lang','Pending_Email_Readings'),
                                'add' => '&nbsp;&nbsp;&nbsp;<a href="'.Yii::app()->params['http_addr'].'emailreadings/rates">'.Yii::t('lang','Set_Special_Email_Rates').'</a>'
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['http_addr'].'testimonials', ///.PsyConstants::getName(PsyConstants::PAGE_EMAILREADINGS_READER),
                                'title' => 'Testimonials manager',                                
                        )
                );
                break;
            case 'client':
                $cat = array(
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['ssl_addr'].'pay/chat', ///.PsyConstants::getName(PsyConstants::PAGE_CHATADDFUNDS),
                                'title' => Yii::t('lang','Fund'),
                                'add' => Yii::t('lang','chatmain_txt_4')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/arrowsmall.gif',
                                'link' => Yii::app()->params['http_addr'].'site/ourreaders', ///.PsyConstants::getName(PsyConstants::PAGE_CHATOURREADERS),
                                'title' => Yii::t('lang','Select_your_reader')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/entersmall.gif',
                                'link' => Yii::app()->params['http_addr'].'chat/client/chatStart',
                                'title' => Yii::t('lang','Start'),
                                'add' => Yii::t('lang','Your_reading')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['http_addr'].'messages/index', ///.PsyConstants::getName(PsyConstants::PAGE_MESSAGECENTER),
                                'title' => Yii::t('lang','Message_Center'),
                                'add' => '<b>'.Yii::t('lang','Inbox').': '.$this->mess_cnt.' '.Yii::t('lang','New').': '.$this->mess_cnt_new.'</b>'
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['http_addr'].'history', ///.PsyConstants::getName(PsyConstants::PAGE_MESSAGECENTER),
                                'title' => 'Chat History',
                                'add' => '&nbsp;/&nbsp;<a href="'.Yii::app()->params['http_addr'].'emailreadings/list">E-Readings history</a>'
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/arrowsmall.gif',
                                'link' => Yii::app()->params['http_addr'].'users/purchases', ///.PsyConstants::getName(PsyConstants::PAGE_PURCHASES),
                                'title' => Yii::t('lang','Purchases')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['http_addr'].'users/pageReader', ///.PsyConstants::getName(PsyConstants::PAGE_NOTIFYREADERS),
                                'title' => Yii::t('lang','Page_Reader')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['http_addr'].'profile/userprofile', ///.PsyConstants::getName(PsyConstants::PAGE_NOTIFYREADERS),
                                'title' => 'Profile'
                        ),
                );
                break;
            case 'Administrator':
                $cat = array(
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/entersmall.gif',
                                'link' => Yii::app()->params['http_addr'].'admin/whoIsChatting', ///.PsyConstants::getName(PsyConstants::PAGE_WHOISCHATTING),
                                'title' => Yii::t('lang','who_is_chatting'),
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/arrowsmall.gif',
                                'link' => Yii::app()->params['http_addr'].'users/clientAdministration', ///.PsyConstants::getName(PsyConstants::PAGE_CLIENT_ADMIN),
                                'title' => Yii::t('lang','Our_clients')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['http_addr'].'chat/payouts.php', ///.PsyConstants::getName(PsyConstants::PAGE_PAYOUTS),
                                'title' => Yii::t('lang','Payouts'),
                                'add' => '  |   <a class="LinkMedium" href="'.Yii::app()->params['site_domain'].'/chat/payout_historylist.php'/*.PsyConstants::getName(PsyConstants::PAGE_PAYOUT_HISTORY)*/.'">'.Yii::t('lang','Payout_history').'</a>'
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/chat/messagecenter_sent.php', ///.PsyConstants::getName(PsyConstants::PAGE_MESSAGECENTER),
                                'title' => Yii::t('lang','Message_Center'),
                                'add' => '<b>'.Yii::t('lang','Inbox').': '.$this->mess_cnt.' '.Yii::t('lang','New').': '.$this->mess_cnt_new.'</b>  <a class="LinkMedium" href="'.Yii::app()->params['site_domain'].'/chat/messagecenter_sent.php' /*.PsyConstants::getName(PsyConstants::PAGE_MESSAGECENTER_SENT)*/.'">'.Yii::t('lang','Sent').'</a>'
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_MESSAGEPENDING),
                                'title' => Yii::t('lang','Pending_Messages'),
                                'add' => '('.$this->pending_mess.' '.Yii::t('lang','Pending_Approval').')'
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_READEREDIT_TMP),
                                'title' => Yii::t('lang','Pending_Readers_info_Upgrade'),
                                'add' => '('.$this->count_upd.' '.Yii::t('lang','Records').')'
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_MESSAGESETTING),
                                'title' => Yii::t('lang','Message_Center_Settings')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/arrowsmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_BANNEDLIST),
                                'title' => Yii::t('lang','System_of_banning_users')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/entersmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_AFFILIATE),
                                'title' => Yii::t('lang','Affiliate')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/entersmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_READERBALANCE),
                                'title' => Yii::t('lang','Readers_Balance')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/arrowsmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_SETTINGS),
                                'title' => Yii::t('lang','Settings_Price')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/entersmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_CRONLIST),
                                'title' => Yii::t('lang','List_of_waiting_charges')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/entersmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_WP_LIST),
                                'title' => Yii::t('lang','List_of_Webphone Readers'),
                                'add' => '<a href="'.Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_WP_LIST).'">'.Yii::t('lang','Webphone_logs').'</a>&nbsp;&nbsp;
                                          <a href="'.Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_WP_CLIENTS).'">'.Yii::t('lang','Webphone_Clients').'</a>'
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/entersmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_WP_FUNDSREADERS),
                                'title' => Yii::t('lang','Webphone/E-mail_Amounts')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/arrowsmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_CHATSESSION),
                                'title' => Yii::t('lang','Edit/Remove_chat_sessions')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/arrowsmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_ADD_FUNDSREADERS),
                                'title' => Yii::t('lang','Add_Edit_funds_for_Readers')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_SENDEMAILS),
                                'title' => Yii::t('lang','Send_E-mails')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_EMAILREADERS),
                                'title' => Yii::t('lang','Email_Reading_Readers')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_EMAILREADINGS_ADMIN),
                                'title' => Yii::t('lang','Email_Reading_Requests_Readings')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_REFSTAT),
                                'title' => Yii::t('lang','Referrer_statistics')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_NOTIFYREADERS),
                                'title' => Yii::t('lang','Page_Reader')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/envelopesmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_ARCHIVE),
                                'title' => Yii::t('lang','Remove_old_history_records')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/arrowsmall.gif',
                                'link' => Yii::app()->params['http_addr'].'admin/doubleReaders',
                                'title' => Yii::t('lang','Double_readers')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/arrowsmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_SEARCHTRANCE),
                                'title' => Yii::t('lang','Search_transactions')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/arrowsmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_READERSVISIBILITY),
                                'title' => Yii::t('lang','readers_visibillity_msg_4')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/arrowsmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_CHATCONTROL),
                                'title' => Yii::t('lang','Chat_control')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/arrowsmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_RETTIMESTAT),
                                'title' => Yii::t('lang','Return_time_statstics')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/arrowsmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/'.PsyConstants::getName(PsyConstants::PAGE_FORCE_AUTO_INVOICING),
                                'title' => Yii::t('lang','Force_auto_invoicing')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/arrowsmall.gif',
                                'link' => Yii::app()->params['http_addr'].'goFish/manage/index',
                                'title' => 'Go Fish Administration'
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/arrowsmall.gif',
                                'link' => Yii::app()->params['http_addr'].'admin/featuredReader',
                                'title' => 'Edit featured reader'
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'images/entersmall.gif',
                                'link' => Yii::app()->params['http_addr'].'admin/caPaymentsInUSD',
                                'title' => 'List of Canadians who pay in USD'
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'images/entersmall.gif',
                                'link' => Yii::app()->params['site_domain'].'/chat/articles_admin.php',
                                'title' => 'Edit articles'
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'images/entersmall.gif',
                                'link' => Yii::app()->params['http_addr'].'testimonials/pending',
                                'title' => 'Edit/Delete pending reader testimonials'
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'images/entersmall.gif',
                                'link' => Yii::app()->params['http_addr'].'staticPage',
                                'title' => 'Create/Update/Delete static pages'
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'images/entersmall.gif',
                                'link' => Yii::app()->params['http_addr'].'admin/disconnectReader',
                                'title' => 'Disconnect readers'
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'images/entersmall.gif',
                                'link' => Yii::app()->params['http_addr'].'util/online',
                                'title' => 'Reader online sessions'
                        ),
                );
                break;
            case 'gift_chat':
                $cat = array(
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/entersmall.gif',
                                'link' => Yii::app()->params['http_addr'].'chat/client/chatStart',
                                'title' => Yii::t('lang','Start'),
                                'add' => Yii::t('lang','Your_reading')
                        ),
                        array(
                                'pic' => Yii::app()->params['http_addr'].'/images/arrowsmall.gif',
                                'link' => Yii::app()->params['ssl_addr'].'site/signup?register=1&gift='.$this->user_id,
                                'title' => Yii::t('lang','Register_full_account')
                        ),
                );
                break;
            case 'gift_chat_pending':
                 $cat = array(
                         array(
                                'pic' => Yii::app()->params['http_addr'].'/images/arrowsmall.gif',
                                'link' => Yii::app()->params['ssl_addr'].'site/signup?register=1&gift='.$this->user_id,
                                'title' => Yii::t('lang','Register_full_account')
                         ),
                 );
                 break;
        }
        $this->render('userMenu', array('menues' => $cat, 'page_type' => $this->page_type));
    }
}
?>
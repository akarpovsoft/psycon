<?php

class EmailreadingsController extends PsyController
{
    /**
     * @return array action filters
     */
    public function filters($params = null) {
        $a = array(
                'accessControl', 
                'readerAccess + pending, rates, show'
        );
        return parent::filters($a);
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
                array('allow', // allow authenticated user to perform 'create' and 'update' actions
                        'actions'=>array('show', 'list', 'pending', 'rates', 'one'),
                        'users'=>array('@'),
                ),
                array('deny',  // deny all users
                        'users'=>array('*'),
                ),
        );
    }
    
    public function filterReaderAccess($filterChain)
    {
        if(Yii::app()->user->type != 'reader')
            $this->redirect(Yii::app()->params['http_addr'].'users/mainmenu');
        $filterChain->run();
    }
    
    /* -- Reader Actions -- */
    public function actionPending()
    {
        if((isset($_POST['search']))||(isset($_GET['paging']))){
            if(isset($_GET['paging']))
                $q = $_GET['query'];
            else
                $q = $_POST['query'];
            $readings = EmailQuestions::searchPendingEmails(Yii::app()->user->id, $q);
        }
        else
            $readings = EmailQuestions::getPendingEmails(Yii::app()->user->id);
        
        $this->render('pending', array('data' => $readings));
    }
    
    public function actionRates()
    {
        $reader_rates = EmailReaders::getReaderRates(Yii::app()->user->id);
        if(isset($_POST['save'])){
            if(empty($_POST['enable']))
                $reader_rates->special = 0;
            else
                $reader_rates->special = $_POST['special'];
            $reader_rates->save();
        }        
        $affiliate_rates = PsyConstants::getName(PsyConstants::EMAILREADINGS_AFF_AMOUNT);
        $this->render('rates', array('reader' => $reader_rates, 'aff' => $affiliate_rates));
    }
    
    public function actionShow($id)
    {
        $reading = EmailQuestions::getReading($id);
        
        $readingType = Tariff::getReadingTypeByPrice($reading->b_reading_type);
        $currentRate = EmailReaders::getCurrentRate(Yii::app()->user->id, $readingType);
        
        if(isset($_POST['goReply']))
        {    
        	/*
            if($reading->status != 'active')
            {
                $this->redirect(Yii::app()->params['http_addr'].'emailreadings/pending');
            }
            */
            if(isset($_POST['extra_info']))
            {
                $reading->sendAnswer($_POST['reply'], 'extra');                
            }
            elseif($reading->status == 'active')
            {
                ReaderPayment::add($reading->reader_id, $reading->qs_id, $currentRate, $reading->chatlocation, 'email');
                $reading->sendAnswer($_POST['reply']);
                // Email to soper  
//                PsyMailer::send('akarpovsoft@yahoo.com', 'Reading Info', "Reading id: ".$reading->qs_id."\n<br>Reader id: ".$reading->reader_id."\n<br>Transaction id: ".$reading->transaction);              
                mail('akarpovsoft@yahoo.com', 'Reading Info', "Reading id: ".$reading->qs_id."\n<br>Reader id: ".$reading->reader_id."\n<br>Transaction id: ".$reading->transaction);
                // Mark transaction as used
                $tran = Transactions::getTransaction($reading->transaction);
                if($tran) {
	                $tran->Amount_used = $tran->TRAN_AMOUNT;
    	            $tran->save();
                }
            }
            if(!$reading->getErrors())
                $this->redirect(Yii::app()->params['http_addr'].'emailreadings/pending');
        }        
        $this->render('show', array('reading' => $reading, 'rate' => $currentRate, 'type' => $readingType, 'active' => ($reading->status == 'active')));
    }
    
    /* -- Client Actions -- */
    public function actionOne($id)
    {
        $reading = EmailQuestions::getReading($id);
        $readingType = Tariff::getReadingTypeByPrice($reading->b_reading_type);
        
        $this->render('one', array('reading' => $reading, 'readingType' => $readingType));
    }
    
    public function actionList()
    {
        $this->layout = $this->without_left_menu;
        if(isset($_POST['filter']))
        {
            //echo $_POST['filter_year'].' '.$_POST['month'].' '.$_POST['period'];
            
            $opt = Util::createCriteriaForChatHistory($_POST['filter_year'], $_POST['month'] , $_POST['period']);            
            $data = EmailQuestions::getCompleteReadings(Yii::app()->user->id, $opt);
        } 
        else if(isset($_GET['paging']))
        {
            $opt = Util::createCriteriaForChatHistory($_GET['year'], $_GET['month'], $_GET['day']);
            $data = EmailQuestions::getCompleteReadings(Yii::app()->user->id, $opt);
        } 
        else 
        {
            $data = EmailQuestions::getCompleteReadings(Yii::app()->user->id);
        }
        $this->render('list', array('data' => $data));
    }
}
?>

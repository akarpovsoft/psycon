<?php

class UserInfo extends CWidget
{
    public $type;
    
	public function init()
	{

	}

	public function run()
	{
		$this->renderContent();
	}

	protected function renderContent()
	{
	    $logged = Clients::getClient(Yii::app()->user->id);
	    //Check if client balance low than 3 minutes and show message
	    $balance_warning = false;
	    if($logged->balance < 3 && ($logged->type != 'reader') ) {
	        $balance_warning = true;
	    }
//            var_dump($balance_warning);
//            die();
	    $account_page = false;
	    $route = Yii::app()->urlManager->parseUrl(Yii::app()->getRequest());
	    if($route == 'users/mainmenu') {
	        $account_page = true;
	    }
//            echo $nrrQuests;
//            die();
            $this->render('userInfo',array(
				'username' => CHtml::encode($logged->name),
				'email' => CHtml::encode($logged->emailaddress),
				'status' => ucfirst($logged->type),
				'balance' => CHtml::encode($logged->balance),
				'balance_warning' => $balance_warning,
				'account_page' => $account_page,
                                'type' => $this->type
		));
	}
}
<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

        public function actionLogin()
        {
            $model = new LoginForm;

            if (isset($_POST['LoginForm']))
            {
                $model->attributes = $_POST['LoginForm'];
                $model->site_type = 'mobile';
                if(!$model->validate())
                {
                    $this->render('login', array('errors' => $model->getErrors()));
                    return;
                }
                else
                {
                    $this->redirect('../mobile');
                }
            }
            $this->render('login');
        }

        public function actionLogout()
        {
            Yii::app()->user->logout();
            $this->redirect('../mobile');
        }

        public function actionReaders()
        {
            $this->render('our_readers');
        }
        
        public function actionProfile()
        {
            if(isset($_POST['save'])){
                $client = Clients::getClient(Yii::app()->user->id, 'credit_cards');
                $client->password = $_POST['password'];
                $client->emailaddress = $_POST['emailaddress'];
                $client->save();
            }            
            $this->render('profile', array('client' => Clients::getClient(Yii::app()->user->id, 'credit_cards')));
        }
        
        public function actionFaq()
        {
            $this->render('faq');
        }
        
        public function actionSupport()
        {
            $this->render('support');
        }
}
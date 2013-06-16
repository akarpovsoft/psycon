<?php

class UsersController extends Controller
{
        /* Action for blog users registration  */
        public function actionRegister(){
            if(isset($_POST['begin_reg'])){
                 $model = new SignupForm();
                 $model->first_name = $_POST['first_name'];
                 $model->last_name = $_POST['last_name'];
                 $model->login = $_POST['login'];
                 $model->password = $_POST['password'];
                 if(!$model->validate()){
                    $err = $model->getErrors();
                    $this->render('register', array('errors' => $err));
                    return;
                 } else {
                    $model->registerBlogUser();
                    $this->render('register', array('success' => 1));
                 }
            }
            $this->render('register');
        }

        public function actionLogin(){
            if(isset($_POST['begin_log'])){
                $model = new LoginForm();
                $model->login = $_POST['login'];
                $model->password = $_POST['password'];
                $model->key = $_COOKIE['PHPSESSID'];
                if($model->validate())
                        $this->redirect(Yii::app()->params['http_addr'].'blog/articles');
            }
            $this->render('login');
        }

        public function actionLogout(){
            BlogSessions::deleteSession($_COOKIE['PHPSESSID']);
            $this->redirect(Yii::app()->params['http_addr'].'blog/users/login');
        }

        public function actionIndex(){

            $this->render('index');
	}
}
<?php

class DefaultController extends Controller
{
    public function actionIndex(){
        $blog_user = BlogSessions::getSession($_COOKIE['PHPSESSID']);

        if(empty($blog_user))
            $user_state = 'guest';
        else {
            $log = unserialize($blog_user->session_value);
            $user = BlogUsers::getBlogUser($log['id']);
            if($user->approved == 0)
                $user_state = 'guest';
        }

        if($user_state == 'guest')
            $this->redirect(Yii::app()->params['http_addr'].'blog/users/login');
        else
            $this->redirect(Yii::app()->params['http_addr'].'blog/articles');
    }
}

?>

<?php
/**
 * 
 */

class HybridMenu extends CWidget
{
    public $guest;

    public function init()
    {
        $this->guest = Yii::app()->user->isGuest;
    }

    public function run()
    {
        if($this->guest)
        {
            $links = array(
                array(
                    'Title' => 'Home',
                    'Link' => Yii::app()->params['http_addr'].'mobile'
                ),
                array(
                    'Title' => 'Log In',
                    'Link' => Yii::app()->params['http_addr'].'mobile/login'
                ),
                array(
                    'Title' => 'Our Readers',
                    'Link' => Yii::app()->params['http_addr'].'mobile/ourreaders'
                ),
                array(
                    'Title' => 'NEW Clients - 10 FREE minutes',
                    'Link' => Yii::app()->params['http_addr'].'mobile/faq'
                ),
                array(
                    'Title' => 'FREEBIE F.A.Q.s',
                    'Link' => Yii::app()->params['http_addr'].'mobile/faq'
                ),
                array(
                    'Title' => 'Contact Support',
                    'Link' => Yii::app()->params['http_addr'].'mobile/suppport'
                ),
            );
        }
        else
        {
            $links = array(
                array(
                    'Title' => 'Home',
                    'Link' => Yii::app()->params['http_addr'].'mobile'
                ),
                array(
                    'Title' => 'Select a Reader',
                    'Link' => Yii::app()->params['http_addr'].'mobile/readers'
                ),
                array(
                    'Title' => 'Your account',
                    'Link' => Yii::app()->params['http_addr'].'mobile/account'
                ),
                array(
                    'Title' => 'History',
                    'Link' => Yii::app()->params['http_addr'].'mobile/history'
                ),
                array(
                    'Title' => 'Profile',
                    'Link' => Yii::app()->params['http_addr'].'mobile/profile'
                ),
                array(
                    'Title' => 'Contact Support',
                    'Link' => Yii::app()->params['http_addr'].'mobile/suppport'
                ),
                array(
                    'Title' => 'Log Out',
                    'Link' => Yii::app()->params['http_addr'].'mobile/logout'
                ),
            );
        }

        $this->render('hybridMenu', array('links' => $links));
    }
}

?>

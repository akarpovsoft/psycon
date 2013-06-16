<?php
/**
 * Hybrid menu for mobile version
 */

class HybridMenu extends CWidget
{
    public $guest;
    public $articles;
    
    public function init()
    {
        $this->guest = Yii::app()->user->isGuest;
        
        $threads = new PsyArThread("");
        $this->articles = $threads->_list();
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
                    'Link' => Yii::app()->params['http_addr'].'mobile/readers'
                ),
                array(
                    'Title' => 'Register Here',
                    'Link' => Yii::app()->params['http_addr'].'mobile/signup'
                ),
                array(
                    'Title' => '10 FREE minutes',
                    'Link' => Yii::app()->params['http_addr'].'mobile/faq'
                ),
                array(
                    'Title' => 'Contact Support',
                    'Link' => Yii::app()->params['http_addr'].'mobile/support'
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
                    'Title' => 'Chat Start',
                    'Link' => Yii::app()->params['http_addr'].'chat/client/chatStart'
                ),
                array(
                    'Title' => 'Select a Reader',
                    'Link' => Yii::app()->params['http_addr'].'mobile/readers'
                ),
                array(
                    'Title' => 'Your Acount',
                    'Link' => Yii::app()->params['http_addr'].'users/mainmenu'
                ),
                array(
                    'Title' => 'Add Funds',
                    'Link' => Yii::app()->params['http_addr'].'mobile/addFunds'
                ),
                 array(
                    'Title' => 'History',
                    'Link' => Yii::app()->params['http_addr'].'users/clientchathistory'
                ),
                array(
                    'Title' => 'Profile',
                    'Link' => Yii::app()->params['http_addr'].'profile/userprofile'
                ),
                array(
                    'Title' => 'Contact Support',
                    'Link' => Yii::app()->params['http_addr'].'mobile/support'
                ),
                array(
                    'Title' => 'Log Out',
                    'Link' => Yii::app()->params['http_addr'].'mobile/logout'
                ),
            );
        }

        $this->render('hybridMenu', array('links' => $links, 'leftArt' => $this->articles));
    }
}

?>

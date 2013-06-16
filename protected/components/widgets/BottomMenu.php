<?php
class BottomMenu extends CWidget {

    private $guest;
    public $type;
    
    public function init() {
        $this->guest = Yii::app()->user->isGuest;
    }

    public function run() {
        if ($this->guest) {
            $links = array(
                    array(
                            'title'=>Yii::t('lang','Home'),
                            'link'=>Yii::app()->params['http_addr'],
                            'class'=>'bottom_nav'
                    ),
                    array(
                            'title'=>Yii::t('lang','Register_here'),
                            'link'=>Yii::app()->params['ssl_addr'].'site/signup?register=1',
                            'class'=>'bottom_nav'
                    ),
                    array(
                            'title'=>Yii::t('lang','Login'),
                            'link'=>Yii::app()->params['ssl_addr'].'site/login',
                            'class'=>'bottom_nav'
                    ),
                    array(
                            'title'=>Yii::t('lang','Customer_service'),
                            'link'=>Yii::app()->params['http_addr'].'site/contact',
                            'class'=>'bottom_nav'
                    ),
                    array(
                            'title' => Yii::t('lang','Affiliate_program'),
                            'link' => 'http://www.psychic-contact.com/affpro/affiliate/join.php',
                            'class' => 'bottom_nav'
                    ),
                    array(
                            'title' => Yii::t('lang','Employment'),
                            'link' => Yii::app()->params['http_addr'].'site/employment',
                            'class' => 'bottom_nav'
                    ),
                    array(
                            'title' => Yii::t('lang','Disclaimer'),
                            'link' => Yii::app()->params['http_addr'].'site/disclaimer',
                            'class' => 'bottom_nav'
                    ),
                    array(
                            'title'=> Yii::t('lang','Refund_policy'),
                            'link'=>Yii::app()->params['http_addr'].'site/refundPolicy',
                            'class'=>'bottom_nav'
                    ),
                    array(
                            'title' => Yii::t('lang','Privacy_policy'),
                            'link' => Yii::app()->params['http_addr'].'site/privacyPolicy',
                            'class' => 'bottom_nav'
                    ),
                array(
                            'title' => 'Old design',
                            'link' => Yii::app()->params['site_domain'].'/chat/index.php',
                            'class' => 'bottom_nav'
                    ),
            );
        }
        else {
            $links = array(
                    array(
                            'title'=>Yii::t('lang','Home'),
                            'link'=>Yii::app()->params['http_addr'],
                            'class'=>'bottom_nav'
                    ),
                    array(
                            'title'=>Yii::t('lang','Log_out'),
                            'link'=>Yii::app()->params['http_addr'].'site/logout',
                            'class'=>'bottom_nav'
                    ),
                    array(
                            'title'=>Yii::t('lang','Customer_service'),
                            'link'=>Yii::app()->params['http_addr'].'site/contact',
                            'class'=>'bottom_nav'
                    ),
                    array(
                            'title' => Yii::t('lang','Affiliate_program'),
                            'link' => 'http://www.psychic-contact.com/affpro/affiliate/join.php',
                            'class' => 'bottom_nav'
                    ),
                    array(
                            'title' => Yii::t('lang','Employment'),
                            'link' => Yii::app()->params['http_addr'].'site/employment',
                            'class' => 'bottom_nav'
                    ),
                    array(
                            'title' => Yii::t('lang','Disclaimer'),
                            'link' => Yii::app()->params['http_addr'].'site/disclaimer',
                            'class' => 'bottom_nav'
                    ),
                    array(
                            'title'=>Yii::t('lang','Refund_policy'),
                            'link'=>Yii::app()->params['http_addr'].'site/refundPolicy',
                            'class'=>'bottom_nav'
                    ),
                    array(
                            'title' => Yii::t('lang','Privacy_policy'),
                            'link' => Yii::app()->params['http_addr'].'site/privacyPolicy',
                            'class' => 'bottom_nav'
                    ),
                    array(
                            'title' => 'Old design',
                            'link' => Yii::app()->params['site_domain'].'/chat/index.php',
                            'class' => 'bottom_nav'
                    ),
            );
        }
        if($type == 'new')
            $this->render('newBottomMenu',array('links'=>$links));
        else
            $this->render('bottomMenu',array('links'=>$links));
    }
}
?>

<?php
class SslLeftMenu extends CWidget {

    private $guest;
    private $layout_type;
    private $user_type;
    private $user_id;
    public $articles;

    public function init() {
       $this->guest = Yii::app()->user->isGuest;
       if(!$this->guest){
           $this->user_type = Yii::app()->user->type;
           $this->user_id = Yii::app()->user->id;
       }
       $this->layout_type = Yii::app()->session['layout_type'];

       Yii::app()->getModule('blog');
       $this->articles = Threads::_list();
//       foreach($this->articles as $art)
//               echo CController::createUrl('thread', array('id' => $art['alias']));
//       die();
    }

    public function run() {
        if ($this->guest) {
        	$links = array(
        	   array('title'=>Yii::t('lang','Home'),
        	         'link'=>Yii::app()->params['http_addr'],
        	         'class'=>'navigation',),
        	   array('title'=>Yii::t('lang','Login'),
        	         'link'=>Yii::app()->params['ssl_addr'].'site/login',
        	         'class'=>'navigation',),
        	   array('title'=>Yii::t('lang','Our_readers'),
        	         'link'=>Yii::app()->params['http_addr'].'site/ourreaders', /// PsyConstants::getName(PsyConstants::PAGE_CHATOURREADERS),
        	         'class'=>'navigation',),
        	   array('title'=>Yii::t('lang','Register_here'),
        	         'link'=>Yii::app()->params['ssl_addr'].'site/signup?register=1',
        	         'class'=>'navigation',),
        	   array('title'=>Yii::t('lang','New_clients_10_free_min'),
        	         'link'=>Yii::app()->params['http_addr'].'site/faq',
        	         'class'=>'navigation',),
        	   array('title'=>Yii::t('lang','Freebie_FAQ'),
        	         'link'=>Yii::app()->params['http_addr'].'site/faq', /// PsyConstants::getName(PsyConstants::PAGE_FREEBIE_FAQ),
        	         'class'=>'navigation',),
        	   array('title'=>Yii::t('lang','Contact_support'),
        	         'link'=>Yii::app()->params['http_addr'].'site/contact',///   '/'.PsyConstants::getName(PsyConstants::PAGE_CONTACT_US),
        	         'class'=>'navigation',),
        	);
        }
        else {
            if($this->user_type == 'gift_chat_pending')
                $links = array(
        	   array('title'=>Yii::t('lang','Home'),
        	         'link'=>Yii::app()->params['http_addr'],
        	         'class'=>'navigation',),
        	   array('title'=>Yii::t('lang','Your_account'),
        	         'link'=>Yii::app()->params['http_addr'].'users/mainmenu',
        	         'class'=>'navigation',),
        	   array('title'=>Yii::t('lang','Register_full_account') ,
        	         'link'=>Yii::app()->params['ssl_addr'].'site/signup?register=1&gift='.$this->user_id,
        	         'class'=>'navigation',),
        	   array('title'=>Yii::t('lang','History'),
        	         'link'=>Yii::app()->params['http_addr'].'users/chathistory',   ///'/'.PsyConstants::getName(PsyConstants::PAGE_CHAT),
        	         'class'=>'navigation',),
        	   array('title'=>Yii::t('lang','Contact_support'),
        	         'link'=>Yii::app()->params['http_addr'].'site/contact', ///'/'.PsyConstants::getName(PsyConstants::PAGE_CONTACT_US),
        	         'class'=>'navigation',),
        	   array('title'=>Yii::t('lang','Log_out'),
        	         'link'=>Yii::app()->params['http_addr'].'site/logout',
        	         'class'=>'navigation',),
        	);
             else
                $links = array(
                       array('title'=>Yii::t('lang','Home'),
                             'link'=>Yii::app()->params['http_addr'],
                             'class'=>'navigation',),
                       array('title'=>Yii::t('lang','Start_your_reading'),
                             'link'=>Yii::app()->params['http_addr'].'chat/client/chatStart',
                             'class'=>'navigation',),
                       array('title'=>Yii::t('lang','Select_a_reader'),
                             'link'=>Yii::app()->params['http_addr'].'site/ourreaders', ////.PsyConstants::getName(PsyConstants::PAGE_CHATOURREADERS),
                             'class'=>'navigation',),
                       array('title'=>Yii::t('lang','Your_account'),
                             'link'=>Yii::app()->params['http_addr'].'users/mainmenu',
                             'class'=>'navigation',),
                       array('title'=>Yii::t('lang','Add_funds'),
                             'link'=>Yii::app()->params['ssl_addr'].'pay/chat', ///.PsyConstants::getName(PsyConstants::PAGE_CHATADDFUNDS),
                             'class'=>'navigation',),
                       array('title'=>Yii::t('lang','History'),
                             'link'=>Yii::app()->params['http_addr'].'users/chathistory', ///.PsyConstants::getName(PsyConstants::PAGE_CHAT),
                             'class'=>'navigation',),
                       array('title'=>Yii::t('lang','Profile'),
                             'link'=>Yii::app()->params['http_addr'].'profile/userprofile', ///.PsyConstants::getName(PsyConstants::PAGE_PROFILE),
                             'class'=>'navigation',),
                       array('title'=>Yii::t('lang','Contact_support'),
                             'link'=>Yii::app()->params['http_addr'].'site/contact', ///.PsyConstants::getName(PsyConstants::PAGE_CONTACT_US),
                             'class'=>'navigation',),
                       array('title'=>Yii::t('lang','Log_out'),
                             'link'=>Yii::app()->params['http_addr'].'site/logout',
                             'class'=>'navigation',),
                    );
        }

        if($this->layout_type)
            $this->render('sslLeftMenu',array('links'=>$links, 'leftArt' => $this->articles, 'layout' => $this->layout_type));
        else $this->render('sslLeftMenu',array('links'=>$links, 'leftArt' => $this->articles, 'layout' => 'default'));
    }
}
?>

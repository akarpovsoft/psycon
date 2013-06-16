<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class PsyController extends Controller {


    /**
     * @return array action filters
     */
    public function filters($params = null) {
        $a = array(
                'setLayout', // choose main layout
                'setLanguage', // choose language
                'setMetaTags',
                array('application.components.DevAccess')
        );

        if(!is_null($params)) {
            foreach($params as $param)
                array_push($a, $param);
        }
        
        return $a;
    }
    /**
     * @var string the default layout for the controller view. Defaults to 'application.views.layouts.column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $iPhone_layout;
    public $without_left_menu;
    public $homePage;
    public $ssl_without_left_menu;
    
    public $lang;

    /**
     * Sets layouts from session var
     */
    public function filterSetLayout($filterChain) {
        $path = 'application.views.layouts';
        $client = Clients::getClient(Yii::app()->user->id); 
        
        Yii::app()->theme = 'newdesign';
       
        if($client->type == 'client' && $client->design_theme == 0) 
        {
            if($client->site_type == 'US' || !$client->site_type)
            {
                $this->layout = $path.'.new';
                $this->without_left_menu = $path.'.new_without_left_menu';
                $this->iPhone_layout = $path.'.iPhone';
                $this->homePage = $path.'.new_HomePage';
                $this->ssl_without_left_menu = $path.'.ssl.new_ssl_without_left_menu';                    
            }
            else
            {
                $this->layout = 
                $this->without_left_menu = 
                $this->iPhone_layout = 
                $this->homePage = $path.'.zero';
            }
        } 
        else if($client->type == 'reader' || $client->type == 'Administrator' || $client->design_theme == 1) 
        {
            $this->layout = $path.'.main';
            $this->without_left_menu = $path.'.main_without_left_menu';
            $this->iPhone_layout = $path.'.iPhone';
            $this->ssl_without_left_menu = $path.'.ssl.ssl_without_left_menu';
            Yii::app()->theme = '';
        } 
        else 
        {
            $this->layout = Yii::app()->params['default_layout'];
            $this->iPhone_layout = Yii::app()->params['default_iPhone_layout'];
            $this->without_left_menu = Yii::app()->params['default_without_left_menu'];
            $this->homePage = $path.'.new_HomePage';  
            $this->ssl_without_left_menu = $path.'.ssl.new_ssl_without_left_menu';
        }
        $filterChain->run();
    }
    /**
     * Sets language from session var
     */
    public function filterSetLanguage($filterChain) {
        $lang = Yii::app()->session['lang'];
        if($lang) 
            Yii::app()->language = Yii::app()->session['lang'];
        else
            Yii::app()->language = Yii::app()->params['default_lang'];
        $filterChain->run();
    }
    
    public function filterSetMetaTags($filterChain) 
    {
        Yii::app()->clientScript->registerMetaTag("Psychic Contact offers Online Psychic Chat and Email Readings - free reading for first time clients", "Description");
        Yii::app()->clientScript->registerMetaTag("Free Psychic Chat, Online Psychic Chat Readings, Free Psychic Readings, chat live online, psychic readers online, free pschic readings, psychics, psychic reading, psychic readings, psychic chat reading, Email Readings, tarot reading, free psychic online chat", "Keywords");
        $filterChain->run();
    }


}
<?php

class StaticPageController extends PsyController
{
    
    public function filters() {
        $a = array(
                'accessControl - show',
            );
        return parent::filters($a);
    }
    public function accessRules() {
        return array(
                array('allow',
                        'actions' => array('Index','Update','Create','Delete'),
                        'users' => array('Jayson') // @todo: add roles to filter
                    ),
                array('deny',  // deny all users
                        'users'=>array('*'),
                    ),
            );
    }
    
    /**
     * Manage page
     */
    public function actionIndex()
    {        
        $data = new CActiveDataProvider('Pages');
        
        $this->render('index', array('data' => $data));
    }
    
    public function actionCreate()
    {
        $cs = Yii::app()->clientScript;        
        $cs->registerScriptFile(Yii::app()->baseUrl . '/js/tiny_mce/tiny_mce.js');

        $page = new Pages;
        
        if($_POST)
        {
            foreach($_POST as $key => $val)
                $page->$key = $val;
            
            if(!$page->validate())
            {                
                $this->render('update', array(
                    'page' => $page, 
                    'errors' => $page->getErrors())
                );
                return;
            }
            else
            {
                $page->save();
                $this->redirect(Yii::app()->params['http_addr'].'staticPage');
            }
        }        
        $this->render('update', array('page' => $page));
    }


    public function actionUpdate($id)
    {
        $cs = Yii::app()->clientScript;        
        $cs->registerScriptFile(Yii::app()->baseUrl . '/js/tiny_mce/tiny_mce.js');
        
        $page = Pages::getPage($id);
        
        if($_POST)
        {
            foreach($_POST as $key => $val)
                $page->$key = $val;
            
            if(!$page->validate())
            {                
                $this->render('update', array('page' => $page, 'errors' => $page->getErrors()));
                return;
            }
            else
            {
                Yii::app()->cache->delete('Page_'.$page->alias);
                $page->save();
                $this->redirect(Yii::app()->params['http_addr'].'staticPage');
            }
        }        
        $this->render('update', array('page' => $page));
    }
    
    public function actionDelete($id)
    {
        $page = Pages::getPage($id);   
        Yii::app()->cache->delete('Page_'.$model->alias);
        if($page->alias != 'index')
            $page->delete();
    }
    
    /**
     * Renders page
     */
    public function actionShow($id)
    {
        $page = Pages::getPageByAlias($id);
        
        $cs = Yii::app()->clientScript;
        $cs->reset();
        $cs->registerMetaTag($page->keywords, 'Keywords', CClientScript::POS_HEAD);
        $cs->registerMetaTag($page->description, 'Description', CClientScript::POS_HEAD);
        
        $this->render('show', array('page' => $page));
    }    
}
 
?>

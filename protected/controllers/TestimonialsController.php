<?php

class TestimonialsController extends PsyController
{
    
    public function filters($params = null)
    {
        $a = array( //'application.components.PsyNetController + index',
            'pageControlAccess - show',
            'adminControlAccess + pending');
        return parent::filters($a);
    }
    
    public function filterPageControlAccess($filterChain)
    {
        if (Yii::app()->user->isGuest)
        {
            $site = Yii::app()->session['site_type'];
            $this->redirect(Yii::app()->params['http_addr'] . 'site/login');
        }
        if (!isset(Yii::app()->user->type))
            $this->redirect(Yii::app()->params['http_addr'] . 'site/index');
        if (Yii::app()->user->type != 'reader' && !Yii::app()->user->isAdmin())
            $this->redirect(Yii::app()->params['http_addr'] . 'site/index');
        $filterChain->run();
    }
    public function filterAdminControlAccess($filterChain)
    {
        if(!Yii::app()->user->isAdmin())
            $this->redirect(Yii::app()->params['http_addr'] . 'testimonials');
        $filterChain->run();
    }
    
    public function actionIndex()
    {
        $testimonials = Testimonials::getByReaderId(Yii::app()->user->id, 'grid');
        
        $message = Yii::app()->request->getParam('message');
        
        $this->render('index', array('data' => $testimonials, 'message' => Testimonials::$messages[$message]));
    }
    
    public function actionAdd()
    {
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile(Yii::app()->baseUrl.'/js/tiny_mce/tiny_mce.js');
        
        if(isset($_POST['add']))
        {
            $testimonial = new Testimonials;
            $testimonial->tm_date = $_POST['date'];
            $testimonial->tm_member = $_POST['member'];
            $testimonial->tm_text = $_POST['text'];
            $testimonial->reader_id = Yii::app()->user->id;
            $testimonial->addTestimonial();   
            
            if($testimonial->getErrors())
            {
                $this->render('add', array('error' => $testimonial->getErrors()));
                return;
            }
            $this->redirect('index?message=1');
            return;
        }
        
        $this->render('add');
    }
    
    public function actionEdit()
    {
        $id = Yii::app()->request->getParam('id');
        
        $testimonial = Testimonials::getById($id);
        $testimonials = Testimonials::getByReaderId(Yii::app()->user->id, 'grid');
        
        
        if(!$testimonial->isMine())
        {            
            $this->render('edit', array('data' => $testimonials, 'error' => 'Invalid testimonial id'));
            return;
        }
        
        if(isset($_POST['edit']))
        {
            $testimonial->tm_date = $_POST['date'];
            $testimonial->tm_text = $_POST['text'];
            $testimonial->save();
            if($testimonial->getErrors())
            {
                $this->render('edit', array('error' => $testimonial->getErrors()));
                return;
            }
            if(Yii::app()->user->isAdmin())
                $this->redirect('pending');
            else
                $this->redirect('index');
            return;
        }
        
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile(Yii::app()->baseUrl.'/js/tiny_mce/tiny_mce.js');
        
        $this->render('edit', array('data' => $testimonials, 'testimonial' => $testimonial));
    }   
    
    public function actionDelete()
    {
        $testimonial = Testimonials::getById($_GET['id']);
        if(!$testimonial->isMine())
        {
            echo "ERROR! Invalid testimonial id";
            return;
        }
        $testimonial->delete();
    }
    
    public function actionShow()
    {
        $testimonials = Testimonials::getByReaderId($_GET['reader_id'], 'grid');
        
        $this->renderPartial('show', array('data' => $testimonials));
    }
    
    public function actionApprove($id)
    {
        $testimonial = Testimonials::getById($_GET['id']);
        $testimonial->approve();  
        
        //$this->redirect('pending');
    }
    
    public function actionPending()
    {
        $testimonials = Testimonials::getPending();
        
        $this->render('pending', array('data' => $testimonials));
    }
}
?>

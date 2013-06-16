<?php

class AnswersModule extends CWebModule
{
    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the applicatio
        $this->layout = 'application.views.layouts.old';
        // import the module-level models and components
        $this->setImport(array(
			'answers.models.*',
			'answers.components.*',
		));
    }


    public function beforeControllerAction($controller, $action)
    {

       
        if (parent::beforeControllerAction($controller, $action))
        {
            //delete old sessions 
            $criteria = new CDbCriteria;
            $timeZ = time()-3600; // 1 hour live 
            $criteria->condition = '`create` < '.$timeZ; 
            SiteQuestionSessions::model()->deleteAll($criteria);

            $route = $controller->id . '/' . $action->id;
            $publicPages = array('default/login', 'default/register' , 'default/questions', 'default/question');


            if (in_array($route, $publicPages))
            {
                return true;
            } else
                if (!Yii::app()->user->isGuest)
                {
                    if (Yii::app()->user->type == 'reader' || Yii::app()->user->type ==
                        'Administrator')
                    {
                        return true;
                    } else
                    {
                        Yii::app()->request->redirect(Yii::app()->params['http_addr'] . answersFunc::
                            Adres() . '/default/login');
                        return false;
                    }
                } else
                    if (isset($_GET['session_key']))
                    {
                        if (SiteQuestionSessions::model()->findByAttributes(array ('key' => $_GET['session_key'])))
                        {
                            //update session time
                            $sessionUser =  SiteQuestionSessions::model()->findByAttributes(array ('key' => $_GET['session_key']));
                            $sessionUser->create = time();
                            $sessionUser->save();
                            
                            return true;
                        } else
                        {
                            Yii::app()->request->redirect(Yii::app()->params['http_addr'] . answersFunc::
                                Adres() . '/default/login');
                            return false;
                        }
                    } else
                    {
                        Yii::app()->request->redirect(Yii::app()->params['http_addr'] . answersFunc::
                            Adres() . '/default/login');
                        return false;
                    }
        } else
            return false;
    }

}

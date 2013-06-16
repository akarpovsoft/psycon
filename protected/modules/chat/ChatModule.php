<?php

class ChatModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'chat.models.*',
			'chat.components.*',
                     'chat.components.widgets.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
                        $client = Clients::getClient(Yii::app()->user->id);
                        switch (Yii::app()->session['layout_type'])
                        {
                            case 1:                                 
                                $controller->layout = (!$client->site_type || $client->site_type == 'US')
                                        ? 'application.views.layouts.new' : 'application.views.layouts.zero';
                                Yii::app()->theme = 'newdesign';
                                break;
                            case 3: 
                                $controller->layout = 'application.views.layouts.mobile';
                                Yii::app()->theme = 'mobile';
                                break;
                            default:
                                $controller->layout = 'main';
                                break;
                        }
                                                
                        return true;
		}
		else
			return false;
	}
}

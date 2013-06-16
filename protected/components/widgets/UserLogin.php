<?php

class UserLogin extends CWidget
{
	public $visible=true;

	public function init()
	{
		if(!$this->visible)
			return;
	}

	public function run()
	{
		if(!$this->visible)
			return;
		$this->renderContent();
	}
	
	protected function renderContent()
	{
	   
		$form=new LoginForm;
		if(isset($_POST['LoginForm']))
		{
			$form->attributes=$_POST['LoginForm'];
			if($form->validate()) {
				$redirect = $this->controller->createUrl('users/mainmenu');
				$this->controller->redirect($redirect);
			}
		}
		$this->render('userLogin',array('form'=>$form));
	}
}
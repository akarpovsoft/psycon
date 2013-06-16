<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class SignupForm extends CFormModel
{
    public $first_name;
    public $last_name;
    public $login;
    public $password;

    public function rules() {
        return array(
                // payment_method are required
                array('first_name, last_name, login, password', 'required'),
                // cvv validation
                array('first_name, last_name', 'validateUnique'),
        );
    }

    public function validateUnique(){
        $check = BlogUsers::uniqueCheck($this->login);
        if($check != true)
            $this->addError('first_name', 'Sorry, but this login is already registered');
    }

    public function registerBlogUser(){
        $blog_user = new BlogUsers();
        $blog_user->first_name = $this->first_name;
        $blog_user->last_name = $this->last_name;
        $blog_user->login = $this->login;
        $blog_user->password = $this->password;
        $blog_user->userRegister();
    }
}

?>

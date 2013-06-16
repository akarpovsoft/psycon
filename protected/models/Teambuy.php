<?php

class Teambuy extends CFormModel
{
    public $name;
    public $dob_day;
    public $dob_month;
    public $dob_year;
    public $email;
    public $login;
    public $pwd;
    public $code;
    
     public function rules() {
        return array(
                array('name, login, pwd, email, dob_month,
                      dob_day, dob_year, code', 'required'),
                array('email', 'email'),
                array('dob_day, dob_year', 'type', 'type'=>'integer'),
        );
     }
    
}
?>

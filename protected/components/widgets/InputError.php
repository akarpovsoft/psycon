<?php
class InputError extends CWidget {

    public $errors;

    public function init(){
    }

    public function run(){        
        $this->render('inputError', array('err' => $this->errors));
    }
}

?>

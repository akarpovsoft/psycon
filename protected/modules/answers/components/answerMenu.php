<?php

class answerMenu extends CWidget {

    private $type;
    
    public function init() {
        $this->type = answersFunc::userType();
    }

    public function run() {
        
            $this->render('answerMenu', array ('type'=>$this->type));
    }
}
?>
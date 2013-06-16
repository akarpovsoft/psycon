<?php
class AlertMsg extends CWidget
{
    public $message;

    public function run(){
        $this->render('alertMsg', array('msg' => $this->message));
    }
}

?>

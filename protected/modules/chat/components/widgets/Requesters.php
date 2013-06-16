<?php


class Requesters extends CWidget
{
    
    public function run()
    {
        $this->render('requesters', array('reader_id' => Yii::app()->user->id));
    }
}

?>

<?php

class SlideShow extends CWidget
{
    public function run()
    {
        $images = array(
            '/advanced/new_images/banner03.jpg',
            '/advanced/new_images/banner02.jpg',
            '/advanced/new_images/banner01.jpg'
        );
        
        $cs = Yii::app()->clientScript;
        $baseUrl=Yii::app()->baseUrl;
        $cs->registerScriptFile($baseUrl.'/js/rotator.js');
        
        $this->render('slideShow', array('images' => $images));
    }
}
?>

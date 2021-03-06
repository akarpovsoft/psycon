<?php

class OldTopMenu extends CWidget {

    public function run(){
        $links = array(
        	   array(
                       'img' => Yii::app()->params['http_addr'].'images/source/PsychicOnline.jpg',
        	       'link'=> Yii::app()->params['http_addr'].'site/ourreaders?oo=1'
        	   ),
                   array(
                       'img' => Yii::app()->params['http_addr'].'images/source/ChatReadings.jpg',
                       'link' => Yii::app()->params['http_addr'].'site/chatreadings'
                   ),
                   array(
                       'img' => Yii::app()->params['http_addr'].'images/source/PhoneReadings.jpg',
                       'link' => Yii::app()->params['http_addr'].'site/phonereaders'
                   ),
                   array(
                       'img' => Yii::app()->params['http_addr'].'images/source/EmailReadings.jpg',
                       'link' => Yii::app()->params['ssl_addr'].'pay/emailreading'
                   ),
                   array(
                       'img' => Yii::app()->params['http_addr'].'images/source/OurPrices.jpg',
                       'link' => Yii::app()->params['http_addr'].'site/ourprices'
                   ),
            );
        $this->render('oldTopMenu', array('links' => $links));
    }

}

?>

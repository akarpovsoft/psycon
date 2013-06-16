<?php

class ReaderShow extends CWidget
{
    public $reader_id;
    
    private function getStatusImage($stat)
    {
        switch($stat)
        {
            case 'online': 
                $img = Yii::app()->params['http_addr'].'new_images/stat_avail.png';
                break;
            case 'busy':
                $img = Yii::app()->params['http_addr'].'new_images/stat_chatting.png';
                break;
            case 'offline':
                $img = Yii::app()->params['http_addr'].'new_images/stat_away.png';
                break;                        
        }
        if(preg_match('/break\d/', $stat))
            $img = Yii::app()->params['http_addr'].'new_images/stat_backsoon.png';
        
        return $img;
    }
    
    public function run()
    {
        $reader = Readers::getReader($this->reader_id);
        $StatImage = $this->getStatusImage($reader->getStatus());
                
        $this->render('readerShow', array('Reader' => $reader, 'Status' => $StatImage));
    }
}

?>

<?php

class FeaturedReaderShow extends CWidget
{
    public $FReader;
    
    public function run()
    {
        $FR = new FeaturedReader();
        $fr_data = $FR->getFRData();
        
        $this->FReader = Readers::getReaderByName(rtrim($fr_data['name']));         
        $status = $this->getStatusImage($this->FReader->getStatus());
        
        $this->render('featuredReaderShow', array('FR' => $FR, 'FData' => $fr_data, 'FReader' => $this->FReader, 'Status' => $status));
    }
    
    private function getStatusImage($stat)
    {
        switch($stat)
        {
            case 'online': 
                $img = '/advanced/images/online.jpg';
                break;
            case 'busy':
                $img = '/advanced/users/getBusyImage?reader_id='.$this->FReader->rr_record_id;
                break;
            case 'offline':
                $img = '/advanced/images/offline.jpg';
                break;                        
        }
        if(preg_match('/break\d/', $stat))
            $img = '/advanced/images/break.jpg';
        
        return $img;
    }
}
?>

<?php

class ReadersList extends CWidget
{
    public $cat;
    public $online;
    public $fmr;
    public $type;
    public $count;
    public $keyword;
        
    public function getStatusImage($stat)
    {
        switch($stat)
        {
            case 'online': 
                $img = Yii::app()->params['http_addr'].'images/online.jpg';
                break;
            case 'busy':
                $img = Yii::app()->params['http_addr'].'images/busy.jpg';
                break;
            case 'offline':
                $img = Yii::app()->params['http_addr'].'images/offline.jpg';
                break;                        
        }
        if(preg_match('/break\d/', $stat))
            $img = Yii::app()->params['http_addr'].'images/break.jpg';
        
        return $img;
    }
    
    public function run()
    {
        if (!is_null($this->cat))
        {
            $category = '&cat=' . $this->cat;
        } 
        else
        {
            $category = '';
        }
        
        if(!is_null($this->keyword))
        {
            $keyword = '&keyword=' . $this->keyword;
        }
        else
            $keyword = '';
        
        $adres = Yii::app()->params['http_addr']."api/xml/showReaders?gr=1" . $category . $keyword;
        
        if ($this->online == 1)
        {
            $adres = Yii::app()->params['http_addr']."api/xml/showReaders?online=1&gr=1" . $category . $keyword;
        } 
        if(isset($this->fmr))
        {
            $adres = Yii::app()->params['http_addr']."api/xml/showReaders?fmr=1&gr=1" . $category . $keyword;
        }
        
        $dataXML = file_get_contents($adres);
        $xml = XmlWork::xmlstr_to_array($dataXML);
        
        $FR = new FeaturedReader();
        $FRData = $FR->getFRData();
        
                
        $online = $xml['reader'];
        $onlyOne = false;
        if(!empty($online))
        {
            $keys = array_keys($online);
            if($keys[0] === 'area') $onlyOne = true;        
        }
        $i = 0;
        
        if($this->count)
        {   
            if($this->type == 'rest')
            {
                foreach($online as $key=>$val)
                {
                    if($i < $this->count)
                        unset($xml['reader'][$key]);
                    $i++;
                }
                $this->type = 'OurReaders';
            }
            else 
            {
                if(!$onlyOne){
                    foreach($online as $key=>$val)
                    {
                        if($val['@attributes']['name'] == rtrim($FRData['name']) && $this->type != 'OurReaders')
                            unset($xml['reader'][$key]);
                        if($i >= $this->count)
                            unset($xml['reader'][$key]);
                        $i++;
                    }
                }
            }                          
        }
        $this->render('readersList', array('xml' => $xml, 'type' => $this->type, 'onlyOne' => $onlyOne));
    }
}
?>

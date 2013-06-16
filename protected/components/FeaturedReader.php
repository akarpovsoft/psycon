<?php

class FeaturedReader
{
    const HTTP_ADDR = 'http://www.psychic-contact.com/chat/'; 
    private $path;
    
    public function __construct($path = '/home/psychi/domains/psychic-contact.com/public_html/chat/data') 
    {
        $this->path = $path;
    }
    
    public function setFRData($name, $desc, $image)
    {
        if(empty($image))
        {
            $data = $this->getFRData();
            $image = str_replace("\n", "", $data['image']);
        }
        $file = fopen($this->path.'/featuredReader.txt', 'w+');
        fwrite($file, $name."\n");        
        fwrite($file, $image."\n");
        fwrite($file, rtrim($desc)."\n");
        fclose($file);
    }
    
    public function getFRData()
    {
        $data = array();
        $file = file($this->path.'/featuredReader.txt');
        $data['name'] = $file[0];
        $data['image'] = $file[1];
        $data['desc'] = '';
        for($i=2;$i<count($file);$i++)
        {
            $data['desc'] .= $file[$i];
        }        
        return $data;
    }
    
    
    
    public function saveFRImage($photo)
    {
        if(!empty($photo['name']))
        {
            if($photo['type'] == 'image/jpeg' || $photo['type'] == 'image/gif' || $photo['type'] == 'image/png' || $photo['type'] == 'image/jpg')
            {
                if(move_uploaded_file($photo["tmp_name"], $this->path.'/'.$photo["name"]))
                    return true;
                else
                    return 'Error. Could not upload image';
            }
            else
                return 'Error. Only JPG, GIF and PNG are allowed';
        }
        return false;
    }
    
    public function loadFRImage()
    {
        $data = $this->getFRData();
        
        if(!empty($data['image']))
            return '<img src="/chat/data/'.trim($data['image']).'" border = "0" class = "featured_reader">';
        else
            return false;
    }    
    
    public function drawFRBox()
    {
        $data = $this->getFRData();
        if(!empty($data['name']) && !empty($data['desc']))
        {
            $data['name'] = substr($data['name'], 0, strlen($data['name']) - 1);
            $reader = Readers::getReaderByName($data['name']);

            echo '<a href="'.self::HTTP_ADDR.'chatourreaders_one.php?operator_id='.$reader->rr_record_id.'" style="text-decoration: none;">';
            echo '<table borderwidth="0" width="95%" border="1" bordercolor="#660099" cellpadding="0" cellspacing="0" style="margin-left: 7px;">
                <tbody><tr><td align="center">
                <a href="'.Yii::app()->params['http_addr'].'reader/Psychic_'.$data['name'].'">
                <br>'.$this->loadFRImage().'<br><br>
                <b>'.$data['name'].'</b></a><b><br>'.nl2br($data['desc']).'</td></tr></tbody></table></a>';
        }
    }
}
?>

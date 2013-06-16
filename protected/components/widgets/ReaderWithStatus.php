<?php

class ReaderWithStatus extends CWidget
{
    public $reader_id;
    public $status;
    public $img_width;
    public $css_class;
    public $place;
    
    public function run()
    {
        $reader = Readers::getReader($this->reader_id);
        
        $this->render('reader_with_status',array(
            'reader_name' => $reader->name,
            'photo_src' => $reader->operator_location,
            'reader' => $reader
        ));
    }
    
    public function getStatusImage($stat)
    {
        switch($stat)
        {
            case 'online': 
                $img = '/advanced/images/online.jpg';
                break;
            case 'busy':
                $img = '/advanced/users/getBusyImage?reader_id='. htmlentities($this->reader_id).'&mr='.rand(0, 4000000);
                break;
            case 'offline':
                $img = '/advanced/images/offline.jpg';
                break;                        
        }
        if(preg_match('/break\d/', $stat))
            $img = '/advanced/images/break.jpg';
        
        return $img;
    }
    
    public static function getOldStatusImage($status, $mins)
    {        
        switch($status){
            case 'offline':                
                $img =  Yii::app()->params['http_addr'].'images/status_offline.jpg';
                break;
            case 'online':
                $img = Yii::app()->params['http_addr'].'images/status_online.jpg';
                break;
            case 'break'.$mins:
                $img = Yii::app()->params['http_addr'].'images/status_break'.$mins.'.jpg';
                break;
            case 'busy':
                $img = Yii::app()->params['http_addr'].'images/status_busy.jpg';
                break;
        }
        return $img;
    }
    
    public static function getBusyStatImg($reader_id)
    {
	$reader_id = mysql_real_escape_string($reader_id);
        $connect = Yii::app()->db;
        $strsql = "SELECT * FROM `chat_context` WHERE `reader_id` = '$reader_id'";
        $command = $connect->createCommand($strsql);
	$rs = $command->query();
	foreach($rs as $row) 
        {
            $Balance_rest = $row['balance'];
            $Balance_rest_min = round($Balance_rest/60);
        }
        
	if( !isset($Balance_rest))
	{
            $strsql = "SELECT Balance from Ping_chat where Reader = '".$reader_id."' order by Reader_ping desc limit 0, 1";
            $command = $connect->createCommand($strsql);
            $rs = $command->query();
            foreach($rs as $row) {
                $Balance_rest = $row[Balance];
                $Balance_rest_min = round($Balance_rest/60);
            }				
        }        
        $src = self::drawNotAvailImage($Balance_rest_min);
        return $src;
    }
    
    public function drawNotAvailImage($period = '0', $img = 'images/busy_new.jpg')
    {
        global $readimg;

        $readimg = false;
        $h_begin = 60;
        $name    = "Back in $period mins";
        $quality = 100;

        $r = 255;
        $g = 255;
        $b = 255;

        //Header("Content-type: image/jpeg");
        $im		= imagecreatefromjpeg ("$img");
        $w		= imagesx($im);
        $h		= imagesy($im);
        $tx_col = imagecolorallocate($im, $r, $g, $b);

        $beginw_name = $w_begin;
        $beginh_name = $h_begin;
        $szf		 = 11;
        $ang		 = 0;
		
		$font = "/usr/home/psychi/domains/psychic-contact.com/public_html/advanced/fonts/ariblk.ttf";

        $sz=imagettfbbox($szf, $angl, $font, $name);

        $beginw_name = $sz[4]-30;
        $beginw_name = $w/2-$beginw_name;

        //$beginh_name = $sz[1]/2;
        //$beginh_name = $h/2-$beginh_name;
        $beginh_name = $beginh_name;

        if($period > 0)
        {
            imagettftext($im, $szf, $ang, $beginw_name, $beginh_name, $tx_col, $font, $name);
        }
		
        Imagejpeg($im, '', $quality);
        ImageDestroy($im);
    }
}
?>

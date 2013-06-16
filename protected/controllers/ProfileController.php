<?php

class ProfileController extends PsyController {

    /**
     * Action of reader's profile page
     */
    public function actionIndex() {
        
        if(!isset($_GET['id'])){
            $this->redirect(Yii::app()->params['http_addr']);
            return;
        }
        

        $exp = explode('_', $_GET['id']);  
        $reader_id = Readers::getReaderByName($exp[1])->rr_record_id;
        
        if(!$reader_id)
        {
            $this->redirect(Yii::app()->params['http_addr']);
            return;
        }
        $this->layout = $this->without_left_menu;
        $reader = Readers::getReader($reader_id);
        $special = Tariff::loadEmailReadingPrice($reader->rr_record_id, 'SPECIAL');
        $is_online = Readers::getReaderOnline($reader_id);
        if(!empty($is_online))
            $reader_online = true;
        else $reader_online = false;
        $reader->comments = ereg_replace("\r", "", $reader->comments);
        $reader->comments = ereg_replace("\n", "<BR>", $reader->comments);
        $emailPeriodFile = '../emailperiod/'.(int)$reader->rr_record_id.'.txt';
        if(file_exists($emailPeriodFile)) {
            $fp = fopen($emailPeriodFile, "r");
            $emailPeriod = fgets($fp, 1024);
            fclose($fp);
        }
        else {
            $emailPeriod = 2;
        }
        if(0 == (int)$emailPeriod) {
            $emailPeriod = 2;
        }
        // Creating a readers mini photo
        $big_image = getimagesize($_SERVER['DOCUMENT_ROOT'].'/chat/'.$reader->operator_location);
        if($big_image[0] > 150){
            $image_width = 150;   
            $image_height = $big_image[1] - ($big_image[0] - 150);
            $w_h = 'width="'.$image_width.'" heigth="'.$image_height.'"';
        } else {
            $image_width = $big_image[0];
            $w_h = '';
        }

        $avatar = '<img src="'.Yii::app()->params['site_domain'].'/chat/'.$reader->operator_location.'" '.$w_h.'>';
        $status = $reader->getStatus();
        
        $OT = new OnlineTime();
        $OT->reader_id = $reader->rr_record_id;
        $totalOT = $OT->getTotalOnlineTime();
        
        if(isset(Yii::app()->user->type))
                $customer = Yii::app()->user->type;
        
        $testimonials = Testimonials::getByReaderId($reader->rr_record_id);

        $this->render('readerview', array('reader' => $reader, 
            'emailperiod' => $emailPeriod, 
            'spec' => $special, 
            'online' => $reader_online, 
            'avatar' => $avatar, 
            'totalOnline' => $totalOT,
            'customer' => $customer,
            'testimonials' => $testimonials,
            'status' => $status,
            'img_width' => $image_width
        ));
    }

    /**
     * Client profile page
     */
    public function actionUserprofile(){
        if(Yii::app()->user->type == 'client'){
            if(isset($_POST['save'])){
                $client = Clients::getClient($_POST['client_id'], 'credit_cards');
                $client->password = $_POST['password'];
                $client->emailaddress = $_POST['emailaddress'];
                $client->design_theme = $_POST['theme'];
                $client->save();
                Yii::app()->session['layout_type'] = ($_POST['theme'] == 1) ? 2 : 1;
                Yii::app()->theme = ($_POST['theme'] == 1) ? '' : 'newtheme';
                
                $this->render('clientprofile', array('client' => $client));
                return;
            } else {
                $client = Clients::getClient(Yii::app()->user->id, 'credit_cards');
                $this->render('clientprofile', array('client' => $client));
            }
        }
        if(Yii::app()->user->type == 'reader'){
            $tempInfo = ReaderInfoTemp::getInfoByReaderId(Yii::app()->user->id);            
            $reader = ($tempInfo) ? $tempInfo : Readers::getReader(Yii::app()->user->id);
            
            if(isset($_POST['save'])){
                foreach($_POST as $key=>$val){
                    if($key != 'save')
                        $data[$key] = $val;
                }

                $photo = CUploadedFile::getInstanceByName('photo');
                if($photo){
                    $file_name = $photo->getName();
                    $file_type = $photo->getType();
                    $file_size = $photo->getsize();
                    // File size check
                    if($file_size > 1024*3*1024){
                        $this->render('readerprofile', array('reader' => $reader, 'error' => 'Too big file size'));
                        return;
                    }
                    // File type check
                    $valid = 'invalid';
                    if(($file_type == 'image/jpeg')||($file_type == 'image/png')||($file_type == 'image/gif')){
                        $valid = 'valid';
                    }
                    if($valid == 'invalid'){
                        $this->render('readerprofile', array('reader' => $reader, 'error' => 'Invalid file type'));
                        return;
                    }
                }
                // All clear
                $reader = Readers::updateReaderInfo(Yii::app()->user->id, $data, $photo);
                
                $this->render('readerprofile', array('reader' => $reader, 'success' => 'Your info has been send for admin\'s approve'));
            }            
            $this->render('readerprofile', array('reader' => $reader));
        }
    }
    
    public function actionPreview()
    {
        if(!isset($_GET['id'])){
            $this->redirect(Yii::app()->params['http_addr']);
            return;
        }
        $exp = explode("_", $_GET['id']);
        
        $this->render('preview', array('name' => $exp[1]));
    }

	public function actionImage($id)
    {
        $reader = Readers::getReader($id);
//        echo '/chat/'.trim($reader['operator_location']);
        readfile('/home/psychi/domains/psychic-contact.com/public_html/chat/'.trim($reader['operator_location']));
        die;
    }
     
    
}

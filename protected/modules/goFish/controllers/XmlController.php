<?php

class XmlController extends PsyController
{
    public function actionRegister()
    {
        if (isset($_GET['fname'])) {
            if (!$_GET['lname']) {
                $error[] = 'Last Name cannot be blank';
            }
            if ($_GET['email']) {
                if (!preg_match("%^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z])+$%",
                    $_GET['email'])) {
                    $error[] = 'Invalid email';
                }
            } else {
                $error[] = 'Email cannot be blank';
            }
            if (!$_GET['dob']) {
                $error[] = 'Date of Birth cannot be blank';
            }
            if ($error) {
                // go error xml
                foreach ($error as $err) {
                    $textError .= $err . '. ';
                }
                $dom = new DOMDocument('1.0', 'utf-8');
                $root = $dom->createElement('result');
                $root->setAttribute('code', 'error');
                $text = $dom->createElement('message');
                $cont = $dom->createCDATASection($textError);
                $text->appendChild($cont);
                $root->appendChild($text);
                $dom->appendChild($root);
                $this->renderPartial('goXML', array('dom' => $dom));
                die();
            } else {                
                $model = new GoFishUsers;
                $model->fname = $_GET['fname'];
                $model->lname = $_GET['lname'];
                $model->email = $_GET['email'];
                $model->DOB = $_GET['dob'];
                if ($model->save()) {
                    $model->emailToClient();
                    $model->emailToAdmin();
                    $dom = new DOMDocument('1.0', 'utf-8');
                    $root = $dom->createElement('result');
                    $root->setAttribute('code', 'ok');
                    // check, if reader is 50th in this month, give him bonus time
                    $monthCount = GoFishUsers::getMonthClientsCount();
                    $bonusCount = GoFishUsers::getBonusCount();
                    if($monthCount % $bonusCount == 0){
                        $model->code = md5($model->id);
                        $model->save();
                        $root->setAttribute('bonusReaderCode', $model->code);
                    }
                    
                    $dom->appendChild($root);
                    $this->renderPartial('goXML', array('dom' => $dom));
                    die();
                }
            }
        }
    }

    public function actionGetFishList()
    {
        $group_id = GoFishModule::GROUP_ID;
        $category = $_GET['cat'];
        $online_only = null;
        $main_reader = 0;
        $readerList = ReadersOnline::availableReaders($online_only, $category, $main_reader,
            $group_id);
        $dom = new DOMDocument('1.0', 'utf-8');
        $root = $dom->createElement('readers');
        $aurora_online = false;
        $aurora_offline = false;
        $offline_cnt = 0;
        $jayson_id = 1;
        if ($readerList) {
			
			//online first
   			$readersTemp = array();
		 	$readerNew = array();	
			foreach ($readerList as $reader) {
        		if ( substr_count($reader['status'],'busy') || substr_count($reader['status'],'break') ) {
        			$readersTemp[] = $reader;
        		} else {
        			if ($reader['status']=='online') {
        				$readerNew[] = $reader;
        			}
        			if ($reader['status']=='offline') {
        				if (count($readersTemp)){
        					$readerNew = array_merge ($readerNew,$readersTemp);
        					unset($readersTemp);
        				} else {
        					$readerNew[] = $reader;
        				}
					}
        		}	
        	}
        	$readerList = $readerNew;
        	
            foreach ($readerList as $reader) {
                if (GoFishFishes::haveModel($reader['rr_record_id'])) {
                    
                    if($reader['status'] != 'offline')
                    {
                        if($reader['name'] == 'Aurora') $aurora_online = true;
                        $readerDom = $dom->createElement('reader');
                        $root->appendChild($readerDom);
                        $readerDom->setAttribute('id', $reader['rr_record_id']);

                        $fish_name = GoFishFunc::getFishName($reader['rr_record_id']);

                        if(!empty($fish_name))
                            $readerDom->setAttribute('shortName', $fish_name);
                        else
                            $readerDom->setAttribute('shortName', $reader['name']);
                            $readerDom->setAttribute('name', $reader['name']);
                            $readerDom->setAttribute('fish', GoFishFunc::getFishModel($reader['rr_record_id']));
                            $readerDom->setAttribute('status', $reader['status']);
                    }
                    else
                    {
                        if($offline_cnt < 2)
                        {
                            if($aurora_online)
                            {                                
                                $offline_cnt++;
                            }
                            else
                            {
                                if(($reader['name'] != 'Aurora')&&!$aurora_offline)
                                {
                                    $reader = Readers::getReaderByName('Aurora');
                                    $aurora_offline = true;
                                }                                
                                $offline_cnt++;
                            }
                            $readerDom = $dom->createElement('reader');
                            $root->appendChild($readerDom);
                            $readerDom->setAttribute('id', $reader['rr_record_id']);

                            $fish_name = GoFishFunc::getFishName($reader['rr_record_id']);

                            if(!empty($fish_name))
                                $readerDom->setAttribute('shortName', $fish_name);
                            else
                                $readerDom->setAttribute('shortName', $reader['name']);
                                $readerDom->setAttribute('name', $reader['name']);
                                $readerDom->setAttribute('fish', GoFishFunc::getFishModel($reader['rr_record_id']));
                                $readerDom->setAttribute('status', $reader['status']);
                        }
                        else
                        {
                            break;
                        }
                    }                    
                }
            }
            // Jayson
            $readerDom = $dom->createElement('reader');
            $root->appendChild($readerDom);
            $readerDom->setAttribute('id', $jayson_id);
            $fish_name = GoFishFunc::getFishName($jayson_id);
            if(!empty($fish_name))
                $readerDom->setAttribute('shortName', $fish_name);
            else
                $readerDom->setAttribute('shortName', 'Jayson');
            $readerDom->setAttribute('name', 'Jayson');
            $readerDom->setAttribute('fish', GoFishFunc::getFishModel($jayson_id));
            $readerDom->setAttribute('status', 'offline');
        }        
        // @TODO: this test code with reader, which status is always changing
//        $filePath = Yii::app()->params['project_root'].'/advanced/data/fish_status_cnt.txt';
//        $readerDom = $dom->createElement('reader');
//        $root->appendChild($readerDom);
//        $readerDom->setAttribute('id', '123123');
//        $readerDom->setAttribute('shortName', 'Test');
//        $readerDom->setAttribute('name', 'Test');
//        $readerDom->setAttribute('fish', 'fish1');
//        $cntFile = file($filePath);
//        $status_counter = $cntFile[0];
//        if($status_counter >= 3)
//            $status_counter = 0;
//        switch($status_counter) {
//            case 0:
//                $readerDom->setAttribute('status', 'online');
//                break;
//            case 1:
//                $readerDom->setAttribute('status', 'offline');
//                break;
//            case 2:
//                $readerDom->setAttribute('status', 'busy');
//                break;
//        }
//        $status_counter++;
//        $cntFile = fopen($filePath, 'w');
//        fwrite($cntFile, $status_counter);
//        fclose($cntFile);
//        // -------------------------------------------------------
        $dom->appendChild($root);
        $this->renderPartial('goXML', array('dom' => $dom));
        die();
    }


    public function actionGetAllFishList()
    {
        $group_id = GoFishModule::GROUP_ID;
        $category = $_GET['cat'];
        $online_only = null;
        $main_reader = 0;
        $jayson_id = 1;
        $readerList = ReadersOnline::availableReaders($online_only, $category, $main_reader,
            $group_id);
        $dom = new DOMDocument('1.0', 'utf-8');
        $root = $dom->createElement('readers');
        if ($readerList) {
            foreach ($readerList as $reader) {
                if (GoFishFishes::haveModel($reader['rr_record_id'])) {
                    $readerDom = $dom->createElement('reader');
                    $root->appendChild($readerDom);
                    $readerDom->setAttribute('id', $reader['rr_record_id']);

                    $fish_name = GoFishFunc::getFishName($reader['rr_record_id']);

                    if(!empty($fish_name))
                        $readerDom->setAttribute('shortName', $fish_name);
                    else
                        $readerDom->setAttribute('shortName', $reader['name']);
                    $readerDom->setAttribute('name', $reader['name']);
                    $readerDom->setAttribute('fish', GoFishFunc::getFishModel($reader['rr_record_id']));
                    $readerDom->setAttribute('status', $reader['status']);
                    $text = $dom->createElement('area');
                    $cont = $dom->createCDATASection($reader['area']);
                    $text->appendChild($cont);
                    $readerDom->appendChild($text);
                }
            }
        }
        // Jayson
        $readerDom = $dom->createElement('reader');
        $root->appendChild($readerDom);
        $readerDom->setAttribute('id', $jayson_id);
        $fish_name = GoFishFunc::getFishName($jayson_id);
        if(!empty($fish_name))
            $readerDom->setAttribute('shortName', $fish_name);
        else
            $readerDom->setAttribute('shortName', 'Jayson');
        $readerDom->setAttribute('name', 'Jayson');
        $readerDom->setAttribute('fish', GoFishFunc::getFishModel($jayson_id));
        $readerDom->setAttribute('status', 'offline');
        $text = $dom->createElement('area');
        $cont = $dom->createCDATASection('');
        $text->appendChild($cont);
        $readerDom->appendChild($text);
        
        $dom->appendChild($root);
        $this->renderPartial('goXML', array('dom' => $dom));
        die();
    }

    public function actionGetFish()
    {
        if ((isset($_GET['id']) && !isset($_GET['name'])) || (!isset($_GET['id']) &&
            isset($_GET['name']))) {

            if (isset($_GET['id'])) {
                $reader = Readers::getReader($_GET['id']);
            } else
                if (isset($_GET['name'])) {
                    $reader = Readers::getReaderByName($_GET['name']);
                }
            $dom = new DOMDocument('1.0', 'utf-8');
            if ($reader) {
                $reader->comments = ereg_replace("\r", "", $reader->comments);
                $reader->comments = ereg_replace("\n", "<BR>", $reader->comments);
                $root = $dom->createElement('reader');
                $root->setAttribute('id', $reader->rr_record_id);

                $fish_name = GoFishFunc::getFishName($reader->rr_record_id);
                if(!empty($fish_name))
                    $root->setAttribute('shortName', $fish_name);
                else
                    $root->setAttribute('shortName', $reader->name);
                $root->setAttribute('name', $reader->name);
                $root->setAttribute('fish', GoFishFunc::getFishModel($reader->rr_record_id));
                $root->setAttribute('status', $reader->getStatus());
                $text0 = $dom->createElement('area');
                $cont0 = $dom->createCDATASection($reader->area);
                $text0->appendChild($cont0);
                $root->appendChild($text0);
                $text = $dom->createElement('comments');
                $cont = $dom->createCDATASection(iconv("cp1251", "UTF-8", $reader->comments));
                $text->appendChild($cont);
                $root->appendChild($text);
                $text2 = $dom->createElement('testimonials');
                $cont2 = $dom->createCDATASection(iconv("cp1251", "UTF-8", $reader->
                    testimonials));
                $text2->appendChild($cont2);
                $root->appendChild($text2);
                $dom->appendChild($root);
            } else {
                $dom = new DOMDocument('1.0', 'utf-8');
                $root = $dom->createElement('result');
                $root->setAttribute('code', 'error');
                $dom->appendChild($root);
            }
            $this->renderPartial('goXML', array('dom' => $dom));
            //end xml
            die();
        }
    }

    public function actionGetPredictionText()
    {
        if (isset($_GET['id'])) {
            $answer = GoFishAnswers::getRandomAnswer($_GET['id']);
            $dom = new DOMDocument('1.0', 'utf-8');
            if ($answer) {
                $root = $dom->createElement('prediction');
                $root->setAttribute('id', $answer->id);
                $cont = $dom->createCDATASection($answer->content);
                $root->appendChild($cont);
                $dom->appendChild($root);
            }
            $this->renderPartial('goXML', array('dom' => $dom));
            die();
        }
    }

    public function actionGetPredictionSound()
    {
        if (isset($_GET['id'])) {
            $answer = GoFishAnswers::getByPk($_GET['id']);
            if ($answer->sound) {
                $file = Yii::app()->params['project_root'].'/advanced/sound/'.$answer->sound;
                if (file_exists($file)) {                
                    if (substr_count($answer->sound, '.ogg')) {
                        header("Content-Type: audio/ogg");
                    } else {
                        header("Content-Type: audio/mpeg");
                    }  
                    header('Content-Length: '.filesize($file));
                    header('Content-Disposition: attachment;filename=' . basename($file));
                    header('X-Content-Type-Options: nosniff');
                    header('X-Frame-Options: SAMEORIGIN');
                    header('X-XSS-Protection: 1; mode=block');
                    ob_clean();
                    flush();
                    readfile($file);               
                } else {
                    header("HTTP/1.0 404 Not Found");
                    echo 404;
                }
                exit;
            }
        }
    }

    public function actionGetPrediction()
    {
        if (isset($_GET['id'])) {
            $answer = GoFishAnswers::getRandomAnswer($_GET['id']);
            $dom = new DOMDocument('1.0', 'utf-8');
            if ($answer) {
                $root = $dom->createElement('prediction');
                $root->setAttribute('id', $answer->id);
                $text = $dom->createElement('text');
                $cont = $dom->createCDATASection($answer->content);
                $text->appendChild($cont);
                $root->appendChild($text);
                $urlMp3 = $dom->createElement('soundUrlMp3', Yii::app()->params['http_addr'].'sound/'.$answer->sound_mp3);
                $root->appendChild($urlMp3);
                $urlOgg = $dom->createElement('soundUrlOgg', Yii::app()->params['http_addr'].'sound/'.$answer->sound_ogg);
                $root->appendChild($urlOgg);
                $dom->appendChild($root);
            }
            $this->renderPartial('goXML', array('dom' => $dom));
            die();
        }
    }

    public function actionWakeUp()
    {
        $body = 'DATE : ' . date("M j, Y") . '<br>TIME SENT: ' . date("h:i a") .
            '<br>FROM: GoFish User<br>
            PLEASE TRY TO LOG ON AS SOON AS YOU RECEIVE THIS EMAIL';
        if (isset($_GET['id'])) {
            $reader = Readers::getReader($_GET['id']);
            if ($reader) {
                $subject = 'CHAT REQUEST from GoFish to You';
                PsyMailer::send($reader->emailaddress, $subject, $body);
            }
        } else {
            $subject = 'CHAT REQUEST from GoFish to all Readers';
            PsyMailer::sendToAllOfflineReaders($subject, $body);
        }

    }
}
